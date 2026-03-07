<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DueController extends Controller
{
    /**
     * List all customers with payment summary.
     */
    public function index()
    {
        $customers = Customer::withCount('invoices')
            ->withSum('invoices as total_amount', 'grand_total')
            ->withSum('invoices as total_paid', 'paid_amount')
            ->withSum('invoices as total_due', 'due_amount')
            ->withCount(['invoices as pending_invoices_count' => function ($query) {
                $query->where('due_amount', '>', 0);
            }])
            ->having('invoices_count', '>', 0)
            ->orderByDesc('total_due')
            ->paginate(15);

        return view('dues.index', compact('customers'));
    }

    /**
     * Show all invoices and payment history for a specific customer.
     */
    public function show(Customer $customer)
    {
        $invoices = Invoice::where('customer_id', $customer->id)
            ->with('payments')
            ->latest()
            ->get();

        $totalDue = $invoices->sum('due_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $totalAmount = $invoices->sum('grand_total');

        return view('dues.show', compact('customer', 'invoices', 'totalDue', 'totalPaid', 'totalAmount'));
    }

    /**
     * Record a payment against an invoice.
     */
    public function settle(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->due_amount,
            'payment_method' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        $amount = $request->amount;
        $receiptInvoice = null;

        $invoice->load('items');

        DB::transaction(function () use ($invoice, $amount, $request, &$receiptInvoice) {
            // Record the payment
            $invoice->payments()->create([
                'customer_id' => $invoice->customer_id,
                'amount' => $amount,
                'payment_method' => $request->input('payment_method', 'cash'),
                'note' => $request->note,
            ]);

            // Update original invoice amounts
            $newPaidAmount = $invoice->paid_amount + $amount;
            $newDueAmount = $invoice->grand_total - $newPaidAmount;
            $paymentStatus = $newDueAmount <= 0 ? 'paid' : 'partial';

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => max(0, $newDueAmount),
                'payment_status' => $paymentStatus,
            ]);

            // Create a payment receipt invoice
            $receiptInvoice = Invoice::create([
                'invoice_no' => 'REC-' . time(),
                'shop_id' => $invoice->shop_id,
                'customer_id' => $invoice->customer_id,
                'invoice_date' => now()->toDateString(),
                'apply_gst' => false,
                'subtotal' => $amount,
                'cgst' => 0,
                'sgst' => 0,
                'igst' => 0,
                'grand_total' => $amount,
                'coupon_id' => null,
                'discount_type' => null,
                'discount_amount' => 0,
                'paid_amount' => $amount,
                'due_amount' => 0,
                'payment_status' => 'paid',
            ]);

            // Add a line item referencing the original invoice
            $receiptInvoice->items()->create([
                'product_id' => $invoice->items->first()->product_id,
                'qty' => 1,
                'price' => $amount,
                'gst_amount' => 0,
                'total' => $amount,
            ]);
        });

        return redirect()->route('invoices.show', $receiptInvoice)
            ->with('success', '₹' . number_format($amount, 2) . ' payment recorded for ' . $invoice->invoice_no . '. Receipt invoice generated.');
    }

    /**
     * Show payment history for an invoice.
     */
    public function history(Invoice $invoice)
    {
        $invoice->load('payments', 'customer');
        return view('dues.history', compact('invoice'));
    }
}
