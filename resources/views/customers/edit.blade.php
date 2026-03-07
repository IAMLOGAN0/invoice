@extends('layouts.app')

@section('page_title', 'Edit Customer')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Customers
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Customer</h1>

            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Customer Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        placeholder="Enter customer name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                        value="{{ $customer->name }}"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-900 mb-2">Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            placeholder="Enter phone number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                            value="{{ $customer->phone }}"
                        >
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gstin" class="block text-sm font-medium text-gray-900 mb-2">GSTIN (Optional)</label>
                        <input 
                            type="text" 
                            name="gstin" 
                            id="gstin" 
                            placeholder="Enter GSTIN"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('gstin') border-red-500 @enderror"
                            value="{{ $customer->gstin }}"
                        >
                        @error('gstin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-900 mb-2">Address</label>
                    <textarea 
                        name="address" 
                        id="address" 
                        rows="3"
                        placeholder="Enter customer address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('address') border-red-500 @enderror"
                    >{{ $customer->address }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state_code" class="block text-sm font-medium text-gray-900 mb-2">State Code (Optional)</label>
                    <input 
                        type="text" 
                        name="state_code" 
                        id="state_code" 
                        placeholder="e.g., MH, DL"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('state_code') border-red-500 @enderror"
                        value="{{ $customer->state_code }}"
                    >
                    @error('state_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition"
                    >
                        Update Customer
                    </button>
                    <a 
                        href="{{ route('customers.index') }}" 
                        class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-medium transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
