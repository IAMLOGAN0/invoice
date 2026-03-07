@extends('layouts.app')

@section('page_title', 'Edit Coupon')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('coupons.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Coupons
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Coupon</h1>

            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-900 mb-2">Coupon Code</label>
                    <input 
                        type="text" 
                        name="code" 
                        id="code" 
                        placeholder="Enter coupon code (e.g., SAVE20)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('code') border-red-500 @enderror"
                        value="{{ $coupon->code }}"
                    >
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-900 mb-2">Discount Type</label>
                        <select 
                            name="discount_type" 
                            id="discount_type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('discount_type') border-red-500 @enderror"
                        >
                            <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                        @error('discount_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-900 mb-2">Discount Value</label>
                        <input 
                            type="number" 
                            name="discount_value" 
                            id="discount_value" 
                            placeholder="0.00"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('discount_value') border-red-500 @enderror"
                            value="{{ $coupon->discount_value }}"
                        >
                        @error('discount_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="min_amount" class="block text-sm font-medium text-gray-900 mb-2">Minimum Amount</label>
                        <input 
                            type="number" 
                            name="min_amount" 
                            id="min_amount" 
                            placeholder="0.00"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('min_amount') border-red-500 @enderror"
                            value="{{ $coupon->min_amount }}"
                        >
                        @error('min_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_discount" class="block text-sm font-medium text-gray-900 mb-2">Max Discount (Optional)</label>
                        <input 
                            type="number" 
                            name="max_discount" 
                            id="max_discount" 
                            placeholder="0.00"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('max_discount') border-red-500 @enderror"
                            value="{{ $coupon->max_discount }}"
                        >
                        @error('max_discount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="valid_from" class="block text-sm font-medium text-gray-900 mb-2">Valid From (Optional)</label>
                        <input 
                            type="date" 
                            name="valid_from" 
                            id="valid_from" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('valid_from') border-red-500 @enderror"
                            value="{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '' }}"
                        >
                        @error('valid_from')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="valid_to" class="block text-sm font-medium text-gray-900 mb-2">Valid To (Optional)</label>
                        <input 
                            type="date" 
                            name="valid_to" 
                            id="valid_to" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('valid_to') border-red-500 @enderror"
                            value="{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '' }}"
                        >
                        @error('valid_to')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            value="1"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 transition"
                            {{ $coupon->is_active ? 'checked' : '' }}
                        >
                        <span class="text-sm font-medium text-gray-900">Active</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition"
                    >
                        Update Coupon
                    </button>
                    <a 
                        href="{{ route('coupons.index') }}" 
                        class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-medium transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
