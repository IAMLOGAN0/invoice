@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Invoices Card --}}
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Invoices</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalInvoices }}</p>
                    <p class="text-xs text-gray-500 mt-2">All time</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Today's Sales Card --}}
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Today's Sales</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">₹{{ number_format($todaySales ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-2">This day</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Monthly Sales Card --}}
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Monthly Sales</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">₹{{ number_format($monthlySales ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-2">This month</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm text-white p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Welcome Back, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-blue-100">Manage your invoices and track your sales in one place.</p>
            </div>
            <div class="hidden md:block text-6xl opacity-20">📊</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('invoices.create') }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100 group">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Create New Invoice</h3>
                    <p class="text-gray-600 text-sm">Start creating a new invoice for your customers</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('invoices.index') }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100 group">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">View All Invoices</h3>
                    <p class="text-gray-600 text-sm">See and manage all your invoices in one place</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Recent Activity Section (Optional) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Invoice Status</span>
                </div>
                <span class="text-gray-600">{{ $totalInvoices }} total created</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Total Revenue</span>
                </div>
                <span class="text-gray-600 font-semibold">₹{{ number_format(($todaySales ?? 0) + ($monthlySales ?? 0), 2) }}</span>
            </div>
        </div>
    </div>
@endsection
