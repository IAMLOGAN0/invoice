@extends('layouts.app')

@section('page_title', isset($invoice) ? 'Edit Invoice' : 'Create Invoice')

@section('content')
<style>
/* Custom Product Select Styles */
.product-select-wrapper {
    position: relative;
    width: 100%;
    overflow: visible;
}

.product-select-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 40px;
}

.product-select-header:hover {
    border-color: #d1d5db;
}

.product-select-header.focused {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    outline: none;
}

.product-select-value {
    flex: 1;
    color: #374151;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-select-value.placeholder {
    color: #9ca3af;
}

.product-select-icons {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: 8px;
}

.product-select-clear {
    display: none;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #9ca3af;
    transition: color 0.2s;
}

.product-select-clear:hover {
    color: #6b7280;
}

.product-select-clear.visible {
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-select-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    color: #6b7280;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.product-select-header.open .product-select-arrow {
    transform: rotate(180deg);
}

.product-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    z-index: 9999;
    max-height: 320px;
    overflow-y: auto;
    display: none;
    min-width: 280px;
}

/* Scrollbar styling */
.product-select-dropdown::-webkit-scrollbar {
    width: 8px;
}

.product-select-dropdown::-webkit-scrollbar-track {
    background: transparent;
}

.product-select-dropdown::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
    border-radius: 4px;
}

.product-select-dropdown::-webkit-scrollbar-thumb:hover {
    background-color: #9ca3af;
}

/* Firefox scrollbar */
.product-select-dropdown {
    scrollbar-color: #d1d5db transparent;
    scrollbar-width: thin;
}

.product-select-dropdown.open {
    display: block;
}

.product-select-search {
    position: sticky;
    top: 0;
    padding: 8px;
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.product-select-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.product-select-search input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.product-select-options {
    padding: 4px 0;
}

.product-select-option {
    padding: 10px 12px;
    cursor: pointer;
    transition: background-color 0.15s;
    border: none;
    width: 100%;
    text-align: left;
    background: none;
    font-size: 14px;
    color: #374151;
}

.product-select-option:hover {
    background-color: #f3f4f6;
}

.product-select-option.selected {
    background-color: #eef2ff;
    color: #4f46e5;
    font-weight: 500;
}

.product-select-option-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.product-select-option-name {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0;
}

.product-select-option-hsn {
    font-size: 12px;
    color: #9ca3af;
    white-space: nowrap;
}

.product-select-option.selected .product-select-option-hsn {
    color: #818cf8;
}

.product-select-empty {
    padding: 16px 12px;
    text-align: center;
    color: #9ca3af;
    font-size: 14px;
}

.product-select-checkmark {
    display: none;
    color: #4f46e5;
    font-weight: bold;
}

.product-select-option.selected .product-select-checkmark {
    display: inline;
}

/* Custom Customer Select Styles */
.customer-select-wrapper {
    position: relative;
    width: 100%;
    overflow: visible;
}

.customer-select-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 42px;
}

.customer-select-header:hover {
    border-color: #d1d5db;
}

.customer-select-header.focused {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    outline: none;
}

.customer-select-value {
    flex: 1;
    color: #374151;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.customer-select-value.placeholder {
    color: #9ca3af;
}

.customer-select-icons {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: 8px;
}

.customer-select-clear {
    display: none;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #9ca3af;
    transition: color 0.2s;
}

.customer-select-clear:hover {
    color: #6b7280;
}

.customer-select-clear.visible {
    display: flex;
    align-items: center;
    justify-content: center;
}

.customer-select-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    color: #6b7280;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.customer-select-header.open .customer-select-arrow {
    transform: rotate(180deg);
}

.customer-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    z-index: 9999;
    max-height: 320px;
    overflow-y: auto;
    display: none;
}

.customer-select-dropdown::-webkit-scrollbar {
    width: 8px;
}

.customer-select-dropdown::-webkit-scrollbar-track {
    background: transparent;
}

.customer-select-dropdown::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
    border-radius: 4px;
}

.customer-select-dropdown {
    scrollbar-color: #d1d5db transparent;
    scrollbar-width: thin;
}

.customer-select-dropdown.open {
    display: block;
}

.customer-select-search {
    position: sticky;
    top: 0;
    padding: 8px;
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.customer-select-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.customer-select-search input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.customer-select-options {
    padding: 4px 0;
}

.customer-select-option {
    padding: 10px 12px;
    cursor: pointer;
    transition: background-color 0.15s;
    border: none;
    width: 100%;
    text-align: left;
    background: none;
    font-size: 14px;
    color: #374151;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.customer-select-option:hover {
    background-color: #f3f4f6;
}

.customer-select-option.selected {
    background-color: #eef2ff;
    color: #4f46e5;
    font-weight: 500;
}

.customer-select-option-name {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.customer-select-option-phone {
    font-size: 12px;
    color: #9ca3af;
    white-space: nowrap;
    margin-left: 8px;
}

.customer-select-option.selected .customer-select-option-phone {
    color: #818cf8;
}

.customer-select-checkmark {
    display: none;
    color: #4f46e5;
    font-weight: bold;
    margin-left: 8px;
}

.customer-select-option.selected .customer-select-checkmark {
    display: inline;
}

/* Custom Coupon Select Styles */
.coupon-select-wrapper {
    position: relative;
    width: 100%;
    overflow: visible;
}

.coupon-select-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #bfdbfe;
    border-radius: 6px;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 40px;
}

.coupon-select-header:hover {
    border-color: #93c5fd;
    background-color: #eff6ff;
}

.coupon-select-header.focused {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.coupon-select-value {
    flex: 1;
    color: #374151;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.coupon-select-value.placeholder {
    color: #9ca3af;
}

.coupon-select-icons {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: 8px;
}

.coupon-select-clear {
    display: none;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #9ca3af;
    transition: color 0.2s;
}

.coupon-select-clear:hover {
    color: #6b7280;
}

.coupon-select-clear.visible {
    display: flex;
    align-items: center;
    justify-content: center;
}

.coupon-select-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    color: #6b7280;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.coupon-select-header.open .coupon-select-arrow {
    transform: rotate(180deg);
}

.coupon-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background-color: #ffffff;
    border: 2px solid #bfdbfe;
    border-radius: 6px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    z-index: 9999;
    max-height: 280px;
    overflow-y: auto;
    display: none;
    min-width: 100%;
}

.coupon-select-dropdown::-webkit-scrollbar {
    width: 8px;
}

.coupon-select-dropdown::-webkit-scrollbar-track {
    background: transparent;
}

.coupon-select-dropdown::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
    border-radius: 4px;
}

.coupon-select-dropdown::-webkit-scrollbar-thumb:hover {
    background-color: #9ca3af;
}

.coupon-select-dropdown {
    scrollbar-color: #d1d5db transparent;
    scrollbar-width: thin;
}

.coupon-select-dropdown.open {
    display: block;
}

.coupon-select-search {
    position: sticky;
    top: 0;
    padding: 8px;
    background-color: #dbeafe;
    border-bottom: 1px solid #bfdbfe;
}

.coupon-select-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #bfdbfe;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.coupon-select-search input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.coupon-select-options {
    padding: 4px 0;
}

.coupon-select-option {
    padding: 10px 12px;
    cursor: pointer;
    transition: background-color 0.15s;
    border: none;
    width: 100%;
    text-align: left;
    background: none;
    font-size: 14px;
    color: #374151;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coupon-select-option:hover {
    background-color: #dbeafe;
}

.coupon-select-option.selected {
    background-color: #bfdbfe;
    color: #1e40af;
    font-weight: 500;
}

.coupon-select-option-main {
    flex: 1;
}

.coupon-select-option-code {
    display: block;
    font-weight: 600;
    color: #1e40af;
}

.coupon-select-option-detail {
    display: block;
    color: #1e40af;
    font-size: 12px;
}

.coupon-select-checkmark {
    display: none;
    color: #2563eb;
    font-weight: bold;
    margin-left: 8px;
}

.coupon-select-option.selected .coupon-select-checkmark {
    display: inline;
}
</style>

<div class="max-w-7xl mx-auto py-6">
    @if ($errors->any())
        <script>
            setTimeout(function() {
                if (typeof showToast === 'function') {
                    @foreach ($errors->all() as $error)
                        showToast(@json($error), 'error');
                    @endforeach
                }
            }, 100);
        </script>
    @endif

    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ isset($invoice) ? 'Edit Invoice' : 'Create Invoice' }}</h1>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Main Form -->
        <div class="col-span-2">
            <form method="POST" action="{{ isset($invoice) ? route('invoices.update', $invoice) : route('invoices.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6">
                @csrf
                @if(isset($invoice))
                    @method('PUT')
                @endif

                <!-- Customer Selection or Creation -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <label class="flex items-center">
                            <input type="radio" name="customer_type" value="existing" id="customerTypeExisting" checked onclick="toggleCustomerMode()">
                            <span class="ml-2">Select Existing Customer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="customer_type" value="new" id="customerTypeNew" onclick="toggleCustomerMode()">
                            <span class="ml-2">Create New Customer</span>
                        </label>
                    </div>

                    <!-- Existing Customer -->
                    <div id="existingCustomerMode">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Customer</label>
                        <div class="customer-select-wrapper">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ isset($invoice) ? $invoice->customer_id : '' }}">
                            <div class="customer-select-header" onclick="toggleCustomerSelect(this)">
                                <span class="customer-select-value placeholder">Select a customer...</span>
                                <div class="customer-select-icons">
                                    <button type="button" class="customer-select-clear" onclick="clearCustomerSelect(event)">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                    <span class="customer-select-arrow">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="customer-select-dropdown">
                                <div class="customer-select-search">
                                    <input type="text" placeholder="Search customers..." class="customer-search-input" onkeyup="filterCustomers(this)">
                                </div>
                                <div class="customer-select-options">
                                    @foreach($customers as $customer)
                                        <button type="button" class="customer-select-option" data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->name }}" data-customer-phone="{{ $customer->phone }}" onclick="selectCustomerOption(event, this)">
                                            <span class="customer-select-option-name">{{ $customer->name }}</span>
                                            @if($customer->phone)
                                                <span class="customer-select-option-phone">{{ $customer->phone }}</span>
                                            @endif
                                            <span class="customer-select-checkmark">✓</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('customer_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Customer -->
                    <div id="newCustomerMode" class="hidden">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="customer_name" class="block font-medium text-sm text-gray-700 mb-2">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('customer_name') }}">
                                @error('customer_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="customer_phone" class="block font-medium text-sm text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('customer_phone') }}">
                                @error('customer_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="customer_address" class="block font-medium text-sm text-gray-700 mb-2">Address <span class="text-red-600">*</span></label>
                                <textarea name="customer_address" id="customer_address" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="customer_gstin" class="block font-medium text-sm text-gray-700 mb-2">GSTIN</label>
                                <input type="text" name="customer_gstin" id="customer_gstin" placeholder="e.g., 27AABCT1234H1Z0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('customer_gstin') }}">
                                @error('customer_gstin')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="customer_state_code" class="block font-medium text-sm text-gray-700 mb-2">State Code <span class="text-red-600">*</span></label>
                                <input type="text" name="customer_state_code" id="customer_state_code" placeholder="e.g., 27" maxlength="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('customer_state_code') }}">
                                @error('customer_state_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-red-800 font-semibold">Please fix the following errors:</p>
                            <ul class="text-red-700 text-sm mt-2">
                                @foreach ($errors->all() as $error)
                                    @if (strpos($error, 'customer') !== false)
                                        <li>• {{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Invoice Date -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <label for="invoice_date" class="block font-medium text-sm text-gray-700 mb-2">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" value="{{ isset($invoice) ? $invoice->invoice_date : date('Y-m-d') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('invoice_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Apply GST Option -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="apply_gst" 
                            id="apply_gst" 
                            value="1"
                            {{ isset($invoice) && !$invoice->apply_gst ? '' : 'checked' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 transition"
                            onchange="toggleGstTaxColumn()"
                        >
                        <span class="text-sm font-medium text-gray-900">Apply GST Tax</span>
                    </label>
                    <p class="text-xs text-gray-600 mt-2">Enable this to automatically calculate GST tax. Uncheck for non-taxable invoices.</p>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Line Items</h2>
                        <button type="button" onclick="invoiceForm.addProductRow()" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            + Add Item
                        </button>
                    </div>

                    <div class="border border-gray-200 rounded-lg" style="position: relative; z-index: 0;">
                        <table class="w-full" id="productsTable">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="min-width: 240px;">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">Tax (%)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                @if(isset($invoice))
                                    @foreach($invoice->items as $item)
                                        <tr class="border-b border-gray-200 product-row">
                                            <td class="px-4 py-3">
                                                <div class="flex gap-2 items-start">
                                                    <div class="product-select-wrapper flex-1">
                                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" class="product-select-input" value="{{ $item->product_id }}" onchange="updateTotal(this)">
                                                        <div class="product-select-header" data-index="{{ $loop->index }}" onclick="toggleProductSelect(this)">
                                                            <span class="product-select-value placeholder">Select product...</span>
                                                            <div class="product-select-icons">
                                                                <button type="button" class="product-select-clear" onclick="clearProductSelect(event, this)">
                                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                    </svg>
                                                                </button>
                                                                <span class="product-select-arrow">
                                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="product-select-dropdown">
                                                            <div class="product-select-search">
                                                                <input type="text" placeholder="Search products..." class="product-search-input" onkeyup="filterProducts(this)">
                                                            </div>
                                                            <div class="product-select-options">
                                                                @foreach($products as $product)
                                                                    <button type="button" class="product-select-option" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-hsn="{{ $product->hsn_code }}" data-product-price="{{ $product->price }}" data-product-gst="{{ $product->gst_percentage }}" onclick="selectProduct(event, this)">
                                                                        <div class="product-select-option-main">
                                                                            <span class="product-select-option-name">{{ $product->name }}</span>
                                                                            @if($product->hsn_code)
                                                                                <span class="product-select-option-hsn">{{ $product->hsn_code }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <span class="product-select-checkmark">✓</span>
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" onclick="openProductModal(this)" class="px-3 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap font-medium text-sm" title="Add new product">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 w-16">
                                                <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->qty }}" step="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input" onchange="updateTotal(this)">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="items[{{ $loop->index }}][unit_price]" value="{{ $item->price }}" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input" onchange="updateTotal(this)">
                                            </td>
                                            <td class="px-4 py-3 w-28">
                                                <input type="number" name="items[{{ $loop->index }}][tax_rate]" value="{{ $item->gst_amount / $item->total * 100 ?? 0 }}" step="0.01" min="0" max="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 tax-input" onchange="updateTotal(this)">
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-900 row-total">₹{{ number_format($item->total, 2) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" onclick="removeProductRow(this)" class="text-red-600 hover:text-red-900 text-sm">Remove</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b border-gray-200 product-row">
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2 items-start">
                                                <div class="product-select-wrapper flex-1">
                                                    <input type="hidden" name="items[0][product_id]" class="product-select-input" value="" onchange="updateTotal(this)">
                                                    <div class="product-select-header" data-index="0" onclick="toggleProductSelect(this)">
                                                        <span class="product-select-value placeholder">Select product...</span>
                                                        <div class="product-select-icons">
                                                            <button type="button" class="product-select-clear" onclick="clearProductSelect(event, this)">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                </svg>
                                                            </button>
                                                            <span class="product-select-arrow">
                                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="product-select-dropdown">
                                                        <div class="product-select-search">
                                                            <input type="text" placeholder="Search products..." class="product-search-input" onkeyup="filterProducts(this)">
                                                        </div>
                                                    <div class="product-select-options">
                                                        @foreach($products as $product)
                                                            <button type="button" class="product-select-option" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-hsn="{{ $product->hsn_code }}" data-product-price="{{ $product->price }}" data-product-gst="{{ $product->gst_percentage }}" onclick="selectProduct(event, this)">
                                                                <div class="product-select-option-main">
                                                                    <span class="product-select-option-name">{{ $product->name }}</span>
                                                                    @if($product->hsn_code)
                                                                        <span class="product-select-option-hsn">{{ $product->hsn_code }}</span>
                                                                    @endif
                                                                </div>
                                                                <span class="product-select-checkmark">✓</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" onclick="openProductModal(this)" class="px-3 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap font-medium text-sm" title="Add new product">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                        </td>
                                        <td class="px-4 py-3 w-16">
                                            <input type="number" name="items[0][quantity]" value="1" step="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input" onchange="updateTotal(this)">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[0][unit_price]" value="0" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input" onchange="updateTotal(this)">
                                        </td>
                                        <td class="px-4 py-3 w-28">
                                            <input type="number" name="items[0][tax_rate]" value="0" step="0.01" min="0" max="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 tax-input" onchange="updateTotal(this)">
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-900 row-total">₹0.00</td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" onclick="removeProductRow(this)" class="text-red-600 hover:text-red-900 text-sm">Remove</button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="flex justify-end mb-6">
                    <div class="w-64 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="font-semibold" id="subtotal">₹0.00</span>
                        </div>
                        <div id="discount-display" class="flex justify-between text-sm hidden">
                            <span class="text-gray-700">Discount:</span>
                            <span class="font-semibold text-red-600" id="discountAmount">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Total Tax:</span>
                            <span class="font-semibold" id="totalTax">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-lg border-t pt-3 border-gray-200">
                            <span class="font-bold">Grand Total:</span>
                            <span class="font-bold text-indigo-600" id="grandTotal">₹0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700">
                        {{ isset($invoice) ? 'Update' : 'Create' }} Invoice
                    </button>
                </div>
            </div>

        <!-- Shop Details Sidebar -->
        <div class="col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shop Details</h3>
                
                @if(Auth::user()->shop)
                    @php $shop = Auth::user()->shop; @endphp
                    <div class="space-y-3 text-sm">
                        <div class="pb-3 border-b border-gray-200">
                            <p class="text-gray-600">Shop Name</p>
                            <p class="font-semibold text-gray-900">{{ $shop->name ?? 'Not Set' }}</p>
                        </div>
                        <div class="pb-3 border-b border-gray-200">
                            <p class="text-gray-600">GST Number</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $shop->gstin ?? 'Not Set' }}</p>
                        </div>
                        <div class="pb-3 border-b border-gray-200">
                            <p class="text-gray-600">State Code</p>
                            <p class="font-semibold text-gray-900">{{ $shop->state_code ?? 'Not Set' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Address</p>
                            <p class="text-gray-900">{{ $shop->address ?? 'Not Set' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('settings') }}" class="mt-4 inline-block px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded transition">
                        Edit Shop Details
                    </a>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <p class="text-yellow-800 text-sm mb-3">No shop details configured</p>
                        <a href="{{ route('settings') }}" class="inline-block px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Configure Shop
                        </a>
                    </div>
                @endif

                <!-- Divider -->
                <div class="my-6 border-t border-gray-200"></div>

                <!-- Discount Section -->
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Discount</h3>
                    </div>

                    <!-- Discount Type Selection -->
                    <div class="space-y-2 mb-4">
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="discount_option" 
                                value="none" 
                                checked
                                class="w-4 h-4 text-blue-600 border-gray-300 cursor-pointer"
                                onchange="toggleDiscountType()"
                            >
                            <span class="ml-2 text-sm text-gray-700">No Discount</span>
                        </label>
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="discount_option" 
                                value="coupon" 
                                class="w-4 h-4 text-blue-600 border-gray-300 cursor-pointer"
                                onchange="toggleDiscountType()"
                            >
                            <span class="ml-2 text-sm text-gray-700">Promo Code</span>
                        </label>
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                name="discount_option" 
                                value="flat" 
                                class="w-4 h-4 text-blue-600 border-gray-300 cursor-pointer"
                                onchange="toggleDiscountType()"
                            >
                            <span class="ml-2 text-sm text-gray-700">Flat Discount</span>
                        </label>
                    </div>

                    <!-- Coupon Custom Select -->
                    <div id="coupon-section" class="hidden mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Promo Code</label>
                        <div class="coupon-select-wrapper">
                            <input type="hidden" name="coupon_id" id="coupon_id" class="coupon-select-input" data-discount-type="" data-discount-value="0" data-min-amount="0" data-max-discount="0" onchange="handleCouponChange()">
                            <div class="coupon-select-header" onclick="toggleCouponSelect(this)" style="padding: 8px 10px; min-height: 36px;">
                                <span class="coupon-select-value placeholder text-gray-500 text-sm">Select code...</span>
                                <div class="coupon-select-icons">
                                    <button type="button" class="coupon-select-clear" onclick="clearCouponSelect(event, this)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                    <span class="coupon-select-arrow">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="coupon-select-dropdown">
                                <div class="coupon-select-search">
                                    <input type="text" placeholder="Search..." class="coupon-search-input" onkeyup="filterCoupons(this)">
                                </div>
                                <div class="coupon-select-options">
                                    @foreach($coupons as $coupon)
                                        <button 
                                            type="button" 
                                            class="coupon-select-option" 
                                            data-coupon-id="{{ $coupon->id }}" 
                                            data-coupon-code="{{ $coupon->code }}"
                                            data-discount-type="{{ $coupon->discount_type }}"
                                            data-discount-value="{{ $coupon->discount_value }}"
                                            data-min-amount="{{ $coupon->min_amount }}"
                                            data-max-discount="{{ $coupon->max_discount }}"
                                            onclick="selectCoupon(event, this)"
                                        >
                                            <div class="coupon-select-option-main">
                                                <span class="coupon-select-option-code font-semibold text-sm">{{ $coupon->code }}</span>
                                                <span class="coupon-select-option-detail text-xs">{{ ucfirst($coupon->discount_type) }} {{ $coupon->discount_value }}{{ $coupon->discount_type === 'percentage' ? '%' : '₹' }}</span>
                                            </div>
                                            <span class="coupon-select-checkmark">✓</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flat Discount Input -->
                    <div id="flat-discount-section" class="hidden">
                        <label for="flat_discount" class="block text-xs font-semibold text-gray-700 mb-2">Amount (₹)</label>
                        <input 
                            type="number" 
                            name="flat_discount" 
                            id="flat_discount" 
                            step="0.01" 
                            min="0" 
                            value="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            onchange="handleFlatDiscountChange()"
                            oninput="handleFlatDiscountChange()"
                        >
                    </div>

                    <!-- Hidden Discount Amount Input for Form Submission -->
                    <input type="hidden" name="discount_amount" value="0">
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- Product Creation Modal -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Add New Product</h3>
            <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="productModalForm" onsubmit="submitProductModal(event)" class="space-y-4">
            <div>
                <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-600">*</span></label>
                <input 
                    type="text" 
                    id="productName" 
                    name="name"
                    placeholder="Enter product name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                    required
                >
            </div>

            <div>
                <label for="productPrice" class="block text-sm font-medium text-gray-700 mb-1">Price (₹) <span class="text-red-600">*</span></label>
                <input 
                    type="number" 
                    id="productPrice" 
                    name="price"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                    required
                >
            </div>

            <div class="flex gap-2 pt-4">
                <button 
                    type="button" 
                    onclick="closeProductModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition"
                >
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Pass data to invoice-form.js
    window.invoiceRowCount = {{ isset($invoice) ? count($invoice->items) : 1 }};
    window.invoiceProducts = @json($products);

    // Global wrapper function for updateTotal (called from inline onchange handlers)
    function updateTotal(element) {
        if (typeof invoiceForm !== 'undefined' && invoiceForm.updateTotal) {
            invoiceForm.updateTotal(element);
        }
    }

    function toggleCustomerMode() {
        const isNew = document.getElementById('customerTypeNew').checked;
        document.getElementById('existingCustomerMode').classList.toggle('hidden', isNew);
        document.getElementById('newCustomerMode').classList.toggle('hidden', !isNew);
        
        // Clear validation on fields not in use
        if (isNew) {
            // Clear custom customer select
            const wrapper = document.querySelector('.customer-select-wrapper');
            if (wrapper) {
                wrapper.querySelector('#customer_id').value = '';
                const valueSpan = wrapper.querySelector('.customer-select-value');
                valueSpan.textContent = 'Select a customer...';
                valueSpan.classList.add('placeholder');
                wrapper.querySelector('.customer-select-clear').classList.remove('visible');
                wrapper.querySelectorAll('.customer-select-option').forEach(opt => opt.classList.remove('selected'));
            }
        } else {
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_address').value = '';
            document.getElementById('customer_gstin').value = '';
            document.getElementById('customer_state_code').value = '';
        }
    }

    // Custom Customer Select Functions
    function toggleCustomerSelect(headerElement) {
        const wrapper = headerElement.closest('.customer-select-wrapper');
        const dropdown = wrapper.querySelector('.customer-select-dropdown');
        const isOpen = dropdown.classList.contains('open');

        // Close all other dropdowns
        document.querySelectorAll('.customer-select-dropdown.open').forEach(el => {
            el.classList.remove('open');
            el.closest('.customer-select-wrapper').querySelector('.customer-select-header').classList.remove('focused');
        });
        document.querySelectorAll('.product-select-dropdown.open').forEach(el => {
            el.classList.remove('open');
            el.closest('.product-select-wrapper').querySelector('.product-select-header').classList.remove('focused');
        });

        if (!isOpen) {
            dropdown.classList.add('open');
            headerElement.classList.add('focused');
            const searchInput = dropdown.querySelector('.customer-search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 50);
            }
        }
    }

    function selectCustomerOption(event, optionElement) {
        event.preventDefault();
        event.stopPropagation();

        const wrapper = optionElement.closest('.customer-select-wrapper');
        const hiddenInput = wrapper.querySelector('#customer_id');
        const header = wrapper.querySelector('.customer-select-header');
        const valueSpan = header.querySelector('.customer-select-value');
        const dropdown = wrapper.querySelector('.customer-select-dropdown');
        const clearBtn = header.querySelector('.customer-select-clear');

        hiddenInput.value = optionElement.dataset.customerId;
        valueSpan.textContent = optionElement.dataset.customerName;
        valueSpan.classList.remove('placeholder');
        clearBtn.classList.add('visible');

        wrapper.querySelectorAll('.customer-select-option').forEach(opt => opt.classList.remove('selected'));
        optionElement.classList.add('selected');

        dropdown.classList.remove('open');
        header.classList.remove('focused');
    }

    function clearCustomerSelect(event) {
        event.preventDefault();
        event.stopPropagation();

        const wrapper = event.target.closest('.customer-select-wrapper');
        const hiddenInput = wrapper.querySelector('#customer_id');
        const header = wrapper.querySelector('.customer-select-header');
        const valueSpan = header.querySelector('.customer-select-value');
        const clearBtn = header.querySelector('.customer-select-clear');

        hiddenInput.value = '';
        valueSpan.textContent = 'Select a customer...';
        valueSpan.classList.add('placeholder');
        clearBtn.classList.remove('visible');

        wrapper.querySelectorAll('.customer-select-option').forEach(opt => opt.classList.remove('selected'));
    }

    function filterCustomers(searchInput) {
        const wrapper = searchInput.closest('.customer-select-wrapper');
        const options = wrapper.querySelectorAll('.customer-select-option');
        const searchTerm = searchInput.value.toLowerCase();

        options.forEach(option => {
            const name = (option.dataset.customerName || '').toLowerCase();
            const phone = (option.dataset.customerPhone || '').toLowerCase();
            option.style.display = (name.includes(searchTerm) || phone.includes(searchTerm)) ? '' : 'none';
        });
    }

    // Set initial customer selection on page load
    (function() {
        const customerId = document.getElementById('customer_id').value;
        if (customerId) {
            const wrapper = document.querySelector('.customer-select-wrapper');
            const option = wrapper.querySelector('.customer-select-option[data-customer-id="' + customerId + '"]');
            if (option) {
                const header = wrapper.querySelector('.customer-select-header');
                const valueSpan = header.querySelector('.customer-select-value');
                const clearBtn = header.querySelector('.customer-select-clear');
                valueSpan.textContent = option.dataset.customerName;
                valueSpan.classList.remove('placeholder');
                clearBtn.classList.add('visible');
                option.classList.add('selected');
            }
        }
    })();

    // Close customer dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.customer-select-wrapper')) {
            document.querySelectorAll('.customer-select-dropdown.open').forEach(dropdown => {
                dropdown.classList.remove('open');
                dropdown.closest('.customer-select-wrapper').querySelector('.customer-select-header').classList.remove('focused');
            });
        }
    });

    // Custom Product Select Functions
    function toggleProductSelect(headerElement) {
        const wrapper = headerElement.closest('.product-select-wrapper');
        const dropdown = wrapper.querySelector('.product-select-dropdown');
        const isOpen = dropdown.classList.contains('open');
        
        // Close all other dropdowns
        document.querySelectorAll('.product-select-dropdown.open').forEach(el => {
            if (el !== dropdown) {
                el.classList.remove('open');
                el.closest('.product-select-wrapper').querySelector('.product-select-header').classList.remove('focused');
            }
        });
        
        if (isOpen) {
            dropdown.classList.remove('open');
            headerElement.classList.remove('focused');
        } else {
            dropdown.classList.add('open');
            headerElement.classList.add('focused');
            
            // Focus search input
            const searchInput = dropdown.querySelector('.product-search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 0);
            }
        }
    }

    function selectProduct(event, optionElement) {
        event.preventDefault();
        event.stopPropagation();
        
        const wrapper = optionElement.closest('.product-select-wrapper');
        const hiddenInput = wrapper.querySelector('.product-select-input');
        const header = wrapper.querySelector('.product-select-header');
        const valueSpan = header.querySelector('.product-select-value');
        const dropdown = wrapper.querySelector('.product-select-dropdown');
        const clearBtn = header.querySelector('.product-select-clear');
        
        const productId = optionElement.dataset.productId;
        const productName = optionElement.dataset.productName;
        const productHsn = optionElement.dataset.productHsn;
        
        // Update hidden input value
        hiddenInput.value = productId;
        
        // Update display
        valueSpan.textContent = productName;
        valueSpan.classList.remove('placeholder');
        
        // Show clear button
        clearBtn.classList.add('visible');
        
        // Update selected state in options
        wrapper.querySelectorAll('.product-select-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        optionElement.classList.add('selected');
        
        // Close dropdown
        dropdown.classList.remove('open');
        header.classList.remove('focused');
        
        // Trigger change event to update totals
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function clearProductSelect(event, clearButton) {
        event.preventDefault();
        event.stopPropagation();
        
        const wrapper = clearButton.closest('.product-select-wrapper');
        const hiddenInput = wrapper.querySelector('.product-select-input');
        const header = wrapper.querySelector('.product-select-header');
        const valueSpan = header.querySelector('.product-select-value');
        
        // Reset values
        hiddenInput.value = '';
        valueSpan.textContent = 'Select product...';
        valueSpan.classList.add('placeholder');
        clearButton.classList.remove('visible');
        
        // Unselect all options
        wrapper.querySelectorAll('.product-select-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Trigger change event
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function filterProducts(searchInput) {
        const wrapper = searchInput.closest('.product-select-wrapper');
        const options = wrapper.querySelectorAll('.product-select-option');
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        options.forEach(option => {
            const name = option.dataset.productName.toLowerCase();
            const hsn = option.dataset.productHsn.toLowerCase();
            
            const matches = !searchTerm || name.includes(searchTerm) || hsn.includes(searchTerm);
            
            if (matches) {
                option.style.display = '';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Show empty message if no results
        const optionsContainer = wrapper.querySelector('.product-select-options');
        let emptyMsg = optionsContainer.querySelector('.product-select-empty');
        
        if (visibleCount === 0) {
            if (!emptyMsg) {
                emptyMsg = document.createElement('div');
                emptyMsg.className = 'product-select-empty';
                emptyMsg.textContent = 'No products found';
                optionsContainer.appendChild(emptyMsg);
            }
            emptyMsg.style.display = '';
        } else {
            if (emptyMsg) {
                emptyMsg.style.display = 'none';
            }
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.product-select-wrapper')) {
            document.querySelectorAll('.product-select-dropdown.open').forEach(dropdown => {
                dropdown.classList.remove('open');
                dropdown.closest('.product-select-wrapper').querySelector('.product-select-header').classList.remove('focused');
            });
        }
    });

    // Initialize selected products on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.product-select-wrapper').forEach(wrapper => {
            const hiddenInput = wrapper.querySelector('.product-select-input');
            const productId = hiddenInput.value;
            
            if (productId) {
                const option = wrapper.querySelector(`[data-product-id="${productId}"]`);
                if (option) {
                    const header = wrapper.querySelector('.product-select-header');
                    const valueSpan = header.querySelector('.product-select-value');
                    const clearBtn = header.querySelector('.product-select-clear');
                    
                    valueSpan.textContent = option.dataset.productName;
                    valueSpan.classList.remove('placeholder');
                    clearBtn.classList.add('visible');
                    option.classList.add('selected');
                }
            }
        });
    });

    // Toggle GST Tax Column Visibility
    function toggleGstTaxColumn() {
        const applyGst = document.getElementById('apply_gst').checked;
        const taxCells = document.querySelectorAll('th:nth-child(4), td:nth-child(4)');
        
        if (applyGst) {
            // Show tax column
            taxCells.forEach(cell => {
                cell.style.display = '';
            });
        } else {
            // Hide tax column
            taxCells.forEach(cell => {
                cell.style.display = 'none';
            });
        }
        
        // Recalculate totals
        if (typeof invoiceForm !== 'undefined') {
            invoiceForm.updateGrandTotal();
        }
    }

    // Initialize tax column visibility on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleGstTaxColumn();
    });

    // Toggle Discount Type
    function toggleDiscountType() {
        const discountOption = document.querySelector('input[name="discount_option"]:checked').value;
        const couponSection = document.getElementById('coupon-section');
        const flatDiscountSection = document.getElementById('flat-discount-section');
        const discountDisplay = document.getElementById('discount-display');

        if (discountOption === 'coupon') {
            couponSection.classList.remove('hidden');
            flatDiscountSection.classList.add('hidden');
            discountDisplay.classList.remove('hidden');
        } else if (discountOption === 'flat') {
            couponSection.classList.add('hidden');
            flatDiscountSection.classList.remove('hidden');
            discountDisplay.classList.remove('hidden');
        } else {
            couponSection.classList.add('hidden');
            flatDiscountSection.classList.add('hidden');
            discountDisplay.classList.add('hidden');
            clearCouponSelectionInternal();
            document.getElementById('flat_discount').value = '0';
        }

        updateDiscountCalculation();
    }

    // Toggle Coupon Select Dropdown
    function toggleCouponSelect(header) {
        header.classList.toggle('open');
        const dropdown = header.nextElementSibling;
        dropdown.classList.toggle('open');
        header.classList.toggle('focused');
    }

    // Filter Coupons
    function filterCoupons(input) {
        const searchText = input.value.toLowerCase();
        const options = document.querySelectorAll('.coupon-select-option');
        
        options.forEach(option => {
            const code = option.getAttribute('data-coupon-code').toLowerCase();
            const detail = option.querySelector('.coupon-select-option-detail').textContent.toLowerCase();
            if (code.includes(searchText) || detail.includes(searchText)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    }

    // Select Coupon
    // Store selected coupon data
    let selectedCouponData = {
        id: null,
        discountType: null,
        discountValue: 0,
        minAmount: 0,
        maxDiscount: 0
    };

    function selectCoupon(event, button) {
        event.preventDefault();
        
        const couponId = button.getAttribute('data-coupon-id');
        const couponCode = button.getAttribute('data-coupon-code');
        const discountType = button.getAttribute('data-discount-type');
        const discountValue = parseFloat(button.getAttribute('data-discount-value')) || 0;
        const minAmount = parseFloat(button.getAttribute('data-min-amount')) || 0;
        const maxDiscount = parseFloat(button.getAttribute('data-max-discount')) || 0;
        
        // Store coupon data in variable
        selectedCouponData = {
            id: couponId,
            discountType: discountType,
            discountValue: discountValue,
            minAmount: minAmount,
            maxDiscount: maxDiscount
        };
        
        // Also set on input for form submission
        const input = document.querySelector('.coupon-select-input');
        input.value = couponId;
        
        const header = input.parentElement.querySelector('.coupon-select-header');
        const valueSpan = header.querySelector('.coupon-select-value');
        valueSpan.textContent = couponCode;
        valueSpan.classList.remove('placeholder');
        
        // Update selected state
        document.querySelectorAll('.coupon-select-option').forEach(opt => opt.classList.remove('selected'));
        button.classList.add('selected');
        
        // Show clear button
        const clearBtn = header.querySelector('.coupon-select-clear');
        if (clearBtn) {
            clearBtn.classList.add('visible');
        }
        
        // Close dropdown
        const dropdown = header.nextElementSibling;
        dropdown.classList.remove('open');
        header.classList.remove('open', 'focused');
        
        // Clear search
        const searchInput = dropdown.querySelector('.coupon-search-input');
        if (searchInput) {
            searchInput.value = '';
            filterCoupons(searchInput);
        }
        
        // Trigger calculation
        handleCouponChange();
    }

    // Clear Coupon Selection
    function clearCouponSelect(event, button) {
        event.preventDefault();
        event.stopPropagation();
        clearCouponSelectionInternal();
        updateDiscountCalculation();
    }

    // Internal clear function
    function clearCouponSelectionInternal() {
        // Clear stored coupon data
        selectedCouponData = {
            id: null,
            discountType: null,
            discountValue: 0,
            minAmount: 0,
            maxDiscount: 0
        };
        
        const input = document.querySelector('.coupon-select-input');
        input.value = '';
        
        const header = input.parentElement.querySelector('.coupon-select-header');
        const valueSpan = header.querySelector('.coupon-select-value');
        valueSpan.textContent = 'Select a promo code...';
        valueSpan.classList.add('placeholder');
        
        document.querySelectorAll('.coupon-select-option').forEach(opt => opt.classList.remove('selected'));
        
        const clearBtn = header.querySelector('.coupon-select-clear');
        if (clearBtn) {
            clearBtn.classList.remove('visible');
        }
        
        const dropdown = header.nextElementSibling;
        dropdown.classList.remove('open');
        header.classList.remove('open', 'focused');
    }

    // Handle Coupon Change
    function handleCouponChange() {
        updateDiscountCalculation();
    }

    // Handle Flat Discount Change
    function handleFlatDiscountChange() {
        updateDiscountCalculation();
    }

    // Update Discount Calculation
    function updateDiscountCalculation() {
        const discountOption = document.querySelector('input[name="discount_option"]:checked').value;
        const discountDisplay = document.getElementById('discount-display');
        const discountAmountDisplay = document.getElementById('discountAmount');
        
        let discountAmount = 0;

        if (discountOption === 'coupon') {
            const couponInput = document.querySelector('.coupon-select-input');
            const couponId = couponInput.value;
            
            if (couponId && selectedCouponData.id) {
                const discountType = selectedCouponData.discountType;
                const discountValue = selectedCouponData.discountValue;
                const minAmount = selectedCouponData.minAmount;
                const maxDiscount = selectedCouponData.maxDiscount;
                
                // Get subtotal before discount
                const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('₹', '').trim()) || 0;
                
                // Check minimum amount requirement
                if (minAmount > 0 && subtotal < minAmount) {
                    discountAmountDisplay.textContent = '₹0.00';
                    discountDisplay.classList.add('hidden');
                    if (typeof invoiceForm !== 'undefined') {
                        invoiceForm.updateGrandTotal();
                    }
                    return;
                }
                
                if (discountType === 'percentage') {
                    discountAmount = (subtotal * discountValue) / 100;
                } else {
                    discountAmount = discountValue;
                }
                
                if (maxDiscount > 0 && discountAmount > maxDiscount) {
                    discountAmount = maxDiscount;
                }
            }
        } else if (discountOption === 'flat') {
            const flatDiscount = parseFloat(document.getElementById('flat_discount').value) || 0;
            discountAmount = flatDiscount > 0 ? flatDiscount : 0;
        }

        // Update display and hidden input
        if (discountAmount > 0) {
            discountAmountDisplay.textContent = '₹' + discountAmount.toFixed(2);
            discountDisplay.classList.remove('hidden');
        } else {
            discountAmountDisplay.textContent = '₹0.00';
            discountDisplay.classList.add('hidden');
        }

        // Update hidden input for submission
        document.querySelectorAll('input[name="discount_amount"]').forEach(input => {
            input.value = discountAmount.toFixed(2);
        });

        // If invoiceForm exists, recalculate grand total
        if (typeof invoiceForm !== 'undefined') {
            invoiceForm.updateGrandTotal();
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const cSelect = event.target.closest('.coupon-select-wrapper');
        if (!cSelect) {
            document.querySelectorAll('.coupon-select-dropdown').forEach(drop => {
                drop.classList.remove('open');
                drop.parentElement.querySelector('.coupon-select-header').classList.remove('open', 'focused');
            });
        }
    });

    // Product Creation Modal Functions
    window.productModalOpen = null; // Store which row the modal was opened from
    
    function openProductModal(button) {
        document.getElementById('productModal').classList.remove('hidden');
        // Find the wrapper in the same parent (flex container)
        const parent = button.parentElement;
        window.productModalOpen = parent.querySelector('.product-select-wrapper');
        document.getElementById('productName').focus();
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.getElementById('productModalForm').reset();
        window.productModalOpen = null;
    }

    function submitProductModal(event) {
        event.preventDefault();
        
        const name = document.getElementById('productName').value.trim();
        const price = document.getElementById('productPrice').value;

        if (!name || !price) {
            showToast('Please fill in all fields', 'warning');
            return;
        }

        // Show loading state
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        // Submit via AJAX
        fetch('{{ route("products.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                name: name,
                price: parseFloat(price),
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Add product to all dropdowns
                addProductToAllDropdowns(data.product);
                
                // Select in the current row
                if (window.productModalOpen) {
                    selectProductInTarget(window.productModalOpen, data.product);
                }

                closeProductModal();
                showToast('Product created successfully!', 'success');
            } else {
                const errorMsg = data.errors 
                    ? Object.values(data.errors).flat().join(', ')
                    : data.message || 'Failed to create product';
                showToast('Error: ' + errorMsg, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error creating product: ' + error.message, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }

    function addProductToAllDropdowns(product) {
        // Add to all product select dropdowns
        document.querySelectorAll('.product-select-options').forEach(optionsContainer => {
            const optionButton = document.createElement('button');
            optionButton.type = 'button';
            optionButton.className = 'product-select-option';
            optionButton.setAttribute('data-product-id', product.id);
            optionButton.setAttribute('data-product-name', product.name);
            optionButton.setAttribute('data-product-hsn', product.hsn_code);
            optionButton.setAttribute('data-product-price', product.price);
            optionButton.setAttribute('data-product-gst', product.gst_percentage);
            optionButton.onclick = function(e) { selectProduct(e, this); };
            
            optionButton.innerHTML = `
                <div class="product-select-option-main">
                    <span class="product-select-option-name">${product.name}</span>
                    <span class="product-select-option-hsn">${product.hsn_code}</span>
                </div>
                <span class="product-select-checkmark">✓</span>
            `;
            
            optionsContainer.appendChild(optionButton);
        });
    }

    function selectProductInTarget(targetWrapper, product) {
        const selectInput = targetWrapper.querySelector('.product-select-input');
        const selectHeader = targetWrapper.querySelector('.product-select-header');
        const selectValue = selectHeader.querySelector('.product-select-value');
        const clearBtn = selectHeader.querySelector('.product-select-clear');
        
        selectInput.value = product.id;
        selectValue.textContent = product.name;
        selectValue.classList.remove('placeholder');
        if (clearBtn) clearBtn.classList.add('visible');

        // Add the new product to invoiceForm.products so handleProductChange can find it
        if (typeof invoiceForm !== 'undefined') {
            const exists = invoiceForm.products.find(p => p.id == product.id);
            if (!exists) {
                invoiceForm.products.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    hsn_code: product.hsn_code,
                    gst_percentage: product.gst_percentage
                });
            }
            // Now trigger price/tax fill
            invoiceForm.handleProductChange(selectInput);
        }
        
        // Close dropdown
        const dropdown = targetWrapper.querySelector('.product-select-dropdown');
        if (dropdown) {
            dropdown.classList.remove('open');
            selectHeader.classList.remove('open', 'focused');
        }
    }

    // Close modal when clicking outside
    document.getElementById('productModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });

    // Close with Esc key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });

</script>

@vite(['resources/js/invoice-form.js'])
@endsection
