@extends('layouts.app')

@section('page_title', 'Products')

@section('content')
    @if ($message = Session::get('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ $message }}', 'success');
            });
        </script>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Products</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Product</span>
        </a>
    </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col" style="max-height: 70vh; min-height: 300px;">
          <div class="overflow-x-auto overflow-y-auto flex-1">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Product Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">HSN Code</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">{{ $product->hsn_code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">₹{{ number_format($product->price, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    <div class="relative group">
                                        <button class="inline-flex items-center justify-center w-8 h-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.5 1.5H9.5V3.5H10.5V1.5Z"></path>
                                                <path d="M10.5 8.5H9.5V10.5H10.5V8.5Z"></path>
                                                <path d="M10.5 15.5H9.5V17.5H10.5V15.5Z"></path>
                                            </svg>
                                        </button>
                                        
                                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                            <a href="{{ route('products.edit', $product->id) }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 first:rounded-t-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="border-t border-gray-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-red-700 hover:bg-red-50 last:rounded-b-lg transition" onclick="return confirm('Are you sure?')">
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
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">No products found</p>
                                    <a href="{{ route('products.create') }}" class="mt-4 text-blue-600 hover:text-blue-700 font-medium">Create your first product</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
          </div>
        </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
