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
    right: 0;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    z-index: 9999;
    max-height: 320px;
    overflow-y: auto;
    display: none;
    min-width: 100%;
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
                        <label for="customer_id" class="block font-medium text-sm text-gray-700 mb-2">Customer</label>
                        <select name="customer_id" id="customer_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select a customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ isset($invoice) && $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
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
                                                <div class="product-select-wrapper">
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
                                            <div class="product-select-wrapper">
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
            </form>
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
            </div>
        </div>
    </div>
</div>

<script>
    // Pass data to invoice-form.js
    window.invoiceRowCount = {{ isset($invoice) ? count($invoice->items) : 1 }};
    window.invoiceProducts = @json($products);

    function toggleCustomerMode() {
        const isNew = document.getElementById('customerTypeNew').checked;
        document.getElementById('existingCustomerMode').classList.toggle('hidden', isNew);
        document.getElementById('newCustomerMode').classList.toggle('hidden', !isNew);
        
        // Clear validation on fields not in use
        if (isNew) {
            document.getElementById('customer_id').value = '';
        } else {
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_address').value = '';
            document.getElementById('customer_gstin').value = '';
            document.getElementById('customer_state_code').value = '';
        }
    }

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
</script>

@vite(['resources/js/invoice-form.js'])
@endsection
