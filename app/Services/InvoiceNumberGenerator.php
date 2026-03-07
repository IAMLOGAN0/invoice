<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceNumberGenerator
{
    /**
     * Generate a new unique invoice number for the current year.
     * Format: INV/YYYY/XXXX (e.g., INV/2026/0001)
     *
     * @return string
     */
    public function generate(): string
    {
        $currentYear = Carbon::now()->year;

        // Find the last invoice number for the current year
        $latestInvoice = Invoice::whereYear('invoice_date', $currentYear)
                                ->latest('invoice_number') // Assuming invoice_number is stored as a string, get the latest by string comparison, or extract number part if necessary
                                ->first();

        $lastInvoiceNumber = 0;

        if ($latestInvoice) {
            // Extract the numeric part (XXXX) from the latest invoice number (INV/YYYY/XXXX)
            // Example: "INV/2026/0015" -> 15
            $parts = explode('/', $latestInvoice->invoice_number);
            if (count($parts) === 3 && is_numeric($parts[2])) {
                $lastInvoiceNumber = (int) $parts[2];
            }
        }

        $nextInvoiceNumber = $lastInvoiceNumber + 1;

        // Format the new invoice number with leading zeros (e.g., 1 -> 0001)
        return sprintf('INV/%s/%04d', $currentYear, $nextInvoiceNumber);
    }
}
