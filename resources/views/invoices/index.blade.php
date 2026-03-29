@extends('layouts.app')

@section('page_title', 'Invoices')

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>New Invoice</span>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="mb-6">
        <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" placeholder="Search by invoice number, customer name or phone..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                <select name="payment_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
                <select name="payment_method" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                    <option value="">All Payment Types</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="google_pay" {{ request('payment_method') == 'google_pay' ? 'selected' : '' }}>Google Pay</option>
                    <option value="phone_pe" {{ request('payment_method') == 'phone_pe' ? 'selected' : '' }}>Phone Pe</option>
                    <option value="paytm" {{ request('payment_method') == 'paytm' ? 'selected' : '' }}>Paytm</option>
                    <option value="others" {{ request('payment_method') == 'others' ? 'selected' : '' }}>Others</option>
                </select>
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium transition text-sm">
                    Search
                </button>
                @if(request('search') || request('payment_status') || request('payment_method'))
                    <a href="{{ route('invoices.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium transition text-sm text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Invoice #</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Customer</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-right sm:text-left text-xs sm:text-sm font-semibold text-gray-900">Amount</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Status</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Payment</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-blue-600">{{ $invoice->invoice_no ?? $invoice->number ?? 'N/A' }}</td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4">
                                <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $invoice->customer->name ?? 'N/A' }}</div>
                                <div class="sm:hidden text-xs text-gray-500 mt-1">
                                    {{ $invoice->invoice_date ?? ($invoice->date ? $invoice->date->format('M d, Y') : 'N/A') }}
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-700">{{ $invoice->invoice_date ?? ($invoice->date ? $invoice->date->format('M d, Y') : 'N/A') }}</td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 text-right sm:text-left text-xs sm:text-sm font-semibold text-gray-900">₹{{ number_format($invoice->grand_total ?? $invoice->total ?? 0, 2) }}</td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm">
                                @if($invoice->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($invoice->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                                @else
                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                @endif
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-700">
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
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $methodLabels[$invoice->payment_method] ?? ucfirst($invoice->payment_method ?? 'cash') }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 text-center">
                                <div class="flex justify-center">
                                    <div class="relative group">
                                        <button class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.5 1.5H9.5V3.5H10.5V1.5Z"></path>
                                                <path d="M10.5 8.5H9.5V10.5H10.5V8.5Z"></path>
                                                <path d="M10.5 15.5H9.5V17.5H10.5V15.5Z"></path>
                                            </svg>
                                        </button>
                                        
                                        <div class="absolute right-0 mt-2 w-40 sm:w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center space-x-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 first:rounded-t-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>View</span>
                                            </a>
                                            
                                            <a href="{{ route('invoices.downloadPdf', $invoice->id) }}" class="flex items-center space-x-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span>Download</span>
                                            </a>
                                            
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="border-t border-gray-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center space-x-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-red-700 hover:bg-red-50 last:rounded-b-lg transition" onclick="return confirm('Are you sure?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-2 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-xs sm:text-sm">No invoices found</p>
                                    <a href="{{ route('invoices.create') }}" class="mt-2 sm:mt-4 text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Create your first invoice</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $invoices->links() }}
    </div>

@endsection
