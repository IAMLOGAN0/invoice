@extends('layouts.app')

@section('page_title', 'Due Payments')

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Due Payments</h1>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('dues.index') }}" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" placeholder="Search by customer name or phone..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('dues.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Customers with Dues Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">#</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Customer</th>
                        <th class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Phone</th>
                        <th class="hidden lg:table-cell px-2 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-gray-900">Invoices</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-semibold text-gray-900">Amount</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-semibold text-gray-900">Due</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $index => $customer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-500">{{ $customers->firstItem() + $index }}</td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4">
                                <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="sm:hidden text-xs text-gray-500 mt-1">
                                    @if($customer->phone)
                                        {{ $customer->phone }}
                                    @endif
                                </div>
                                @if($customer->address)
                                    <div class="hidden md:block text-xs text-gray-500 mt-1">{{ Str::limit($customer->address, 30) }}</div>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-700">{{ $customer->phone ?: '-' }}</td>
                            <td class="hidden lg:table-cell px-2 sm:px-6 py-3 sm:py-4 text-center">
                                <span class="text-xs sm:text-sm font-medium text-gray-900">{{ $customer->invoices_count }}</span>
                                @if($customer->pending_invoices_count > 0)
                                    <span class="block text-xs text-yellow-700 mt-0.5">{{ $customer->pending_invoices_count }} due</span>
                                @endif
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 text-right">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900">₹{{ number_format($customer->total_amount ?? 0, 0) }}</div>
                                <div class="sm:hidden text-xs text-gray-500 mt-0.5">Paid: ₹{{ number_format($customer->total_paid ?? 0, 0) }}</div>
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-right">
                                <span class="text-xs sm:text-sm font-bold text-red-600">₹{{ number_format($customer->total_due ?? 0, 0) }}</span>
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-center">
                                @if(($customer->total_due ?? 0) > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Clear</span>
                                @endif
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 text-center">
                                <a href="{{ route('dues.show', $customer) }}" class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded transition">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="hidden sm:inline sm:ml-1">View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-2 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-xs sm:text-sm">No customers with invoices yet</p>
                                    <p class="text-gray-400 text-xs mt-1">Create an invoice to see customer payment details.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
@endsection
