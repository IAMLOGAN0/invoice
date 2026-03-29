@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header with Actions -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Invoice</h1>
                <p class="text-gray-500 text-lg mt-1">{{ $invoice->invoice_no }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('invoices.downloadPdf', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    Download PDF
                </a>
                <a href="{{ route('invoices.receipt', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Thermal Print
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2m-6 4H7a2 2 0 01-2-2v-6a2 2 0 012-2h10a2 2 0 012 2v6a2 2 0 01-2 2z" /></svg>
                    Print
                </button>
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition">
                    Back
                </a>
            </div>
        </div>

        <!-- Main Invoice Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Invoice Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 sm:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div>
                        <h2 class="text-white text-2xl sm:text-3xl font-bold">TAX INVOICE</h2>
                        <p class="text-indigo-100 mt-2">Invoice #{{ $invoice->invoice_no }}</p>
                    </div>
                    <div class="sm:text-right text-indigo-100">
                        <p class="text-sm">Invoice Date</p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="p-4 sm:p-8">
                <!-- Seller & Customer Details -->
                <div class="grid md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                    <!-- Seller Details -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Seller Details</h3>
                        <div class="space-y-2">
                            <p class="text-xl font-bold text-gray-900">{{ $invoice->shop->name }}</p>
                            <p class="text-gray-600">{{ $invoice->shop->address }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500">GSTIN</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $invoice->shop->gstin }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Bill To</h3>
                        <div class="space-y-2">
                            <p class="text-xl font-bold text-gray-900">{{ $invoice->customer->name }}</p>
                            <p class="text-gray-600">{{ $invoice->customer->address }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500">GSTIN</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $invoice->customer->gstin }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Quantity</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Unit Price</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">GST Amount</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($invoice->items as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4">
                                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-right text-gray-600">{{ $item->qty }}</td>
                                        <td class="px-4 py-4 text-right text-gray-600">₹{{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-4 text-right text-gray-600">₹{{ number_format($item->gst_amount, 2) }}</td>
                                        <td class="px-4 py-4 text-right font-semibold text-gray-900">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="flex justify-end">
                    
                    <!-- Total Summary -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200 w-full sm:w-80 md:w-96">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900 font-semibold">₹{{ number_format($invoice->subtotal, 2) }}</span>
                            </div>

                            @if ($invoice->cgst > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">CGST (9%)</span>
                                    <span class="text-gray-900 font-semibold">₹{{ number_format($invoice->cgst, 2) }}</span>
                                </div>
                            @endif

                            @if ($invoice->sgst > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">SGST (9%)</span>
                                    <span class="text-gray-900 font-semibold">₹{{ number_format($invoice->sgst, 2) }}</span>
                                </div>
                            @endif

                            @if ($invoice->igst > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">IGST (18%)</span>
                                    <span class="text-gray-900 font-semibold">₹{{ number_format($invoice->igst, 2) }}</span>
                                </div>
                            @endif

                            @if ($invoice->discount_amount > 0)
                                <div class="flex justify-between items-center text-red-600">
                                    <span>Discount 
                                        @if ($invoice->discount_type === 'coupon')
                                            ({{ $invoice->coupon->code ?? 'N/A' }})
                                        @else
                                            (Flat)
                                        @endif
                                    </span>
                                    <span class="font-semibold">-₹{{ number_format($invoice->discount_amount, 2) }}</span>
                                </div>
                            @endif

                            <div class="pt-3 border-t-2 border-gray-300 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Grand Total</span>
                                <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-indigo-700 bg-clip-text text-transparent">₹{{ number_format($invoice->grand_total, 2) }}</span>
                            </div>

                            <div class="pt-3 border-t border-gray-200 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Paid Amount</span>
                                    <span class="text-green-600 font-semibold">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Due Amount</span>
                                    <span class="{{ $invoice->due_amount > 0 ? 'text-red-600' : 'text-green-600' }} font-semibold">₹{{ number_format($invoice->due_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Payment Method</span>
                                    @php
                                        $methodLabels = [
                                            'cash' => 'Cash',
                                            'card' => 'Card',
                                            'google_pay' => 'Google Pay',
                                            'phone_pe' => 'Phone Pe',
                                            'paytm' => 'Paytm',
                                            'others' => 'Others',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $methodLabels[$invoice->payment_method] ?? ucfirst($invoice->payment_method ?? 'cash') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status</span>
                                    @if($invoice->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                    @elseif($invoice->payment_status === 'partial')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 sm:px-8 py-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    Thank you for your business! This is a computer-generated invoice and does not require a signature.
                </p>
            </div>
        </div>

        <!-- Print Styles -->
        <style>
            @media print {
                .bg-gradient-to-br, .bg-gradient-to-r, .from-indigo-600, .to-indigo-700 {
                    background: #4f46e5 !important;
                }
                button, a {
                    display: none !important;
                }
            }
        </style>
    </div>
</div>

@if (session('success'))
    <script>
        setTimeout(function() {
            if (typeof showToast === 'function') {
                showToast(@json(session('success')), 'success');
            }
        }, 100);
    </script>
@endif
@endsection
