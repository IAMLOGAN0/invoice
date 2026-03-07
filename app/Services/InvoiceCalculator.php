<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Support\Collection;

class InvoiceCalculator
{
    public function calculate(Shop $shop, Customer $customer, Collection $items): array
    {
        $subtotal = 0;
        $totalGst = 0;
        $invoiceItems = [];

        foreach ($items as $item) {
            $product = $item['product'];
            $qty = $item['qty'];
            $price = $product->price;
            $gstPercentage = $product->gst_percentage;

            $itemTotal = $price * $qty;
            $gstAmount = ($itemTotal * $gstPercentage) / 100;

            $subtotal += $itemTotal;
            $totalGst += $gstAmount;

            $invoiceItems[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $price,
                'gst_amount' => $gstAmount,
                'total' => $itemTotal,
            ];
        }

        $cgst = 0;
        $sgst = 0;
        $igst = 0;

        if ($shop->state_code === $customer->state_code) {
            $cgst = $totalGst / 2;
            $sgst = $totalGst / 2;
        } else {
            $igst = $totalGst;
        }

        $grandTotal = $subtotal + $totalGst;

        return [
            'subtotal' => $subtotal,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'grand_total' => $grandTotal,
            'items' => $invoiceItems,
        ];
    }
}
