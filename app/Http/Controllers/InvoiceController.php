<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Coupon;
use App\Services\InvoiceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(10)->withQueryString();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $coupons = Coupon::where('is_active', true)->get();
        return view('invoices.create', compact('customers', 'products', 'coupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InvoiceCalculator $calculator)
    {
        try {
            // Custom validation messages
            $messages = [
                'items.required' => 'Please add at least one line item to the invoice',
                'items.min' => 'Please add at least one line item to the invoice',
                'items.*.product_id.required' => 'Product is required for each line item',
                'items.*.product_id.exists' => 'Selected product does not exist',
                'items.*.quantity.required' => 'Quantity is required for each line item',
                'items.*.quantity.min' => 'Quantity must be at least 1',
                'items.*.unit_price.required' => 'Unit price is required for each line item',
                'items.*.unit_price.numeric' => 'Unit price must be a valid number',
                'items.*.unit_price.min' => 'Unit price cannot be negative',
            ];

            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string',
                'customer_gstin' => 'nullable|string|max:20',
                'customer_state_code' => 'nullable|string|max:2',
                'invoice_date' => 'required|date',
                'apply_gst' => 'boolean',
                'discount_option' => 'nullable|in:none,coupon,flat',
                'coupon_id' => 'nullable|exists:coupons,id',
                'flat_discount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
            ], $messages);

            // Validate that either customer_id or customer_name is provided
            if (!$request->customer_id && !$request->customer_name) {
                throw new \Exception('Please select an existing customer or create a new one');
            }

            // Validate that when creating a new customer, required fields are provided
            if (!$request->customer_id && $request->customer_name) {
                if (!$request->customer_address) {
                    throw new \Exception('Customer address is required when creating a new customer');
                }
                if (!$request->customer_state_code) {
                    throw new \Exception('Customer state code is required when creating a new customer');
                }
            }

            $shop = auth()->user()->shop;
            
            // Create or get customer
            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone ?? '',
                    'address' => $request->customer_address,
                    'gstin' => $request->customer_gstin ?? '',
                    'state_code' => $request->customer_state_code,
                    'shop_id' => $shop->id,
                ]);
            }

        $items = collect($request->items)->map(function ($item) {
            return [
                'product' => Product::findOrFail($item['product_id']),
                'qty' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $item['tax_rate'] ?? 0,
            ];
        });

        // Get the apply_gst flag
        $applyGst = $request->has('apply_gst') ? 1 : 0;

        // Calculate using the form's unit prices instead of product prices
        $subtotal = 0;
        $totalGst = 0;
        $invoiceItems = [];

        foreach ($items as $item) {
            $product = $item['product'];
            $qty = $item['qty'];
            $price = $item['unit_price'];
            $gstPercentage = $applyGst ? ($item['tax_rate'] ?? 0) : 0;

            $itemTotal = $price * $qty;
            $gstAmount = ($itemTotal * $gstPercentage) / 100;

            $subtotal += $itemTotal;
            $totalGst += $gstAmount;

            $invoiceItems[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $price,
                'gst_amount' => $gstAmount,
                'total' => $itemTotal + $gstAmount,
            ];
        }

        $cgst = 0;
        $sgst = 0;
        $igst = 0;

        // Only calculate CGST, SGST, IGST if GST is applied
        if ($applyGst) {
            if ($shop->state_code === $customer->state_code) {
                $cgst = $totalGst / 2;
                $sgst = $totalGst / 2;
            } else {
                $igst = $totalGst;
            }
        }

        $grandTotal = $subtotal + $totalGst;

        // Handle coupon or flat discount
        $discountAmount = 0;
        $discountType = null;
        $couponId = null;

        if ($request->discount_option === 'coupon' && $request->coupon_id) {
            $coupon = Coupon::findOrFail($request->coupon_id);
            
            // Calculate discount amount based on coupon type
            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($grandTotal * $coupon->discount_value) / 100;
            } else {
                $discountAmount = $coupon->discount_value;
            }

            // Apply max discount limit if set
            if ($coupon->max_discount > 0 && $discountAmount > $coupon->max_discount) {
                $discountAmount = $coupon->max_discount;
            }

            // Check minimum amount requirement
            if ($coupon->min_amount > 0 && $grandTotal < $coupon->min_amount) {
                throw new \Exception('Order amount must be atleast ₹' . $coupon->min_amount . ' to apply this coupon');
            }

            $discountType = 'coupon';
            $couponId = $coupon->id;
        } elseif ($request->discount_option === 'flat' && $request->flat_discount > 0) {
            $discountAmount = $request->flat_discount;
            $discountType = 'flat';
        }

        // Apply discount to grand total
        $grandTotal = max(0, $grandTotal - $discountAmount);

        // Calculate payment amounts
        $paidAmount = min($request->input('paid_amount', 0), $grandTotal);
        $dueAmount = $grandTotal - $paidAmount;
        $paymentStatus = $dueAmount <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid');

        $invoice = null;
        DB::transaction(function () use ($request, $shop, $customer, $subtotal, $cgst, $sgst, $igst, $grandTotal, $invoiceItems, $applyGst, $discountAmount, $discountType, $couponId, $paidAmount, $dueAmount, $paymentStatus, &$invoice) {
            $invoice = Invoice::create([
                'invoice_no' => 'INV-' . time(),
                'shop_id' => $shop->id,
                'customer_id' => $customer->id,
                'invoice_date' => $request->invoice_date,
                'apply_gst' => $applyGst,
                'subtotal' => $subtotal,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
                'grand_total' => $grandTotal,
                'coupon_id' => $couponId,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
            ]);

            foreach ($invoiceItems as $itemData) {
                $invoice->items()->create($itemData);
            }

            // Record initial payment if amount paid > 0
            if ($paidAmount > 0) {
                $invoice->payments()->create([
                    'customer_id' => $customer->id,
                    'amount' => $paidAmount,
                    'payment_method' => 'cash',
                    'note' => 'Initial payment on invoice creation',
                ]);
            }
        });
        
        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()])->withInput();
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer', 'shop');
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $invoice->items()->delete();
            $invoice->delete();
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Download invoice as PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer', 'shop');
        
        $pdf = \PDF::loadView('invoices.gst_invoice', [
            'invoice' => $invoice,
            'customer' => $invoice->customer,
            'shop' => $invoice->shop,
        ]);
        
        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }
}
