@extends('layouts.app')

@section('page_title', 'Add Product')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Products
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Product</h1>

            <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        placeholder="Enter product name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hsn_code" class="block text-sm font-medium text-gray-900 mb-2">HSN Code</label>
                    <input 
                        type="text" 
                        name="hsn_code" 
                        id="hsn_code" 
                        placeholder="Enter HSN code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('hsn_code') border-red-500 @enderror"
                        value="{{ old('hsn_code') }}"
                    >
                    @error('hsn_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price</label>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            placeholder="0.00"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('price') border-red-500 @enderror"
                            value="{{ old('price') }}"
                        >
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gst_percentage" class="block text-sm font-medium text-gray-900 mb-2">GST Percentage</label>
                        <input 
                            type="number" 
                            name="gst_percentage" 
                            id="gst_percentage" 
                            placeholder="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('gst_percentage') border-red-500 @enderror"
                            value="{{ old('gst_percentage') }}"
                        >
                        @error('gst_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex space-x-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition"
                    >
                        Save Product
                    </button>
                    <a 
                        href="{{ route('products.index') }}" 
                        class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-medium transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
