@extends('layouts.app')

@section('page_title', 'Payment History - ' . $customer->name)

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dues.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            </div>
            <p class="text-gray-500 mt-1 ml-9">{{ $customer->phone }} {{ $customer->address ? '· ' . $customer->address : '' }}</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm text-gray-500 mb-1">Total Invoice Amount</p>
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalAmount, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
            <p class="text-sm text-gray-500 mb-1">Total Paid</p>
            <p class="text-2xl font-bold text-green-600">₹{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-sm text-gray-500 mb-1">Total Due</p>
            <p class="text-2xl font-bold text-red-600">₹{{ number_format($totalDue, 2) }}</p>
        </div>
    </div>

    <!-- Invoices & Payment History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">All Invoices & Payment History</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <!-- Invoice Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800 transition">
                                    {{ $invoice->invoice_no }}
                                </a>
                                @if($invoice->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($invoice->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <span>Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
                                <span>Total: <strong class="text-gray-900">₹{{ number_format($invoice->grand_total, 2) }}</strong></span>
                                <span>Paid: <strong class="text-green-600">₹{{ number_format($invoice->paid_amount, 2) }}</strong></span>
                                <span>Due: <strong class="text-red-600">₹{{ number_format($invoice->due_amount, 2) }}</strong></span>
                            </div>

                            <!-- Payment History -->
                            @if($invoice->payments->count() > 0)
                                <div class="mt-3">
                                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                        View Payment History ({{ $invoice->payments->count() }})
                                    </button>
                                    <div class="hidden mt-2 bg-gray-50 rounded-lg p-3">
                                        <table class="w-full text-xs">
                                            <thead>
                                                <tr class="text-gray-500">
                                                    <th class="text-left pb-2">Date</th>
                                                    <th class="text-right pb-2">Amount</th>
                                                    <th class="text-left pb-2">Method</th>
                                                    <th class="text-left pb-2">Note</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($invoice->payments as $payment)
                                                    <tr>
                                                        <td class="py-1.5 text-gray-600">{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                                                        <td class="py-1.5 text-right font-semibold text-green-600">₹{{ number_format($payment->amount, 2) }}</td>
                                                        <td class="py-1.5 text-gray-600 capitalize">{{ $payment->payment_method }}</td>
                                                        <td class="py-1.5 text-gray-500">{{ $payment->note ?: '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Settle Form (only if dues remain) -->
                        @if($invoice->due_amount > 0)
                        <div class="lg:w-80 bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <form action="{{ route('dues.settle', $invoice) }}" method="POST">
                                @csrf
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Record Payment</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Amount (Max: ₹{{ number_format($invoice->due_amount, 2) }})</label>
                                        <input type="number" name="amount" step="0.01" min="0.01" max="{{ $invoice->due_amount }}" value="{{ $invoice->due_amount }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Payment Method</label>
                                        <select name="payment_method" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="cash">Cash</option>
                                            <option value="upi">UPI</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="card">Card</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Note (optional)</label>
                                        <input type="text" name="note" placeholder="Payment note..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition">
                                            Record Payment
                                        </button>
                                        <button type="button" onclick="this.closest('form').querySelector('input[name=amount]').value='{{ $invoice->due_amount }}'" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2 px-3 rounded-lg transition" title="Pay full due amount">
                                            Full
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">No invoices found for this customer.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
