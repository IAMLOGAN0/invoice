@extends('layouts.app')

@section('page_title', 'Customers')

@section('content')
    @if ($message = Session::get('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ $message }}', 'success');
            });
        </script>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Customers</h1>
        <a href="{{ route('customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Customer</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" placeholder="Search by customer name or phone..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Customer</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Phone</th>
                        <th class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">GSTIN</th>
                        <th class="hidden lg:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Address</th>
                        <th class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">State</th>
                        <th class="px-2 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-2 sm:px-6 py-3 sm:py-4">
                                <p class="text-xs sm:text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                                <p class="md:hidden text-xs text-gray-500 mt-0.5">{{ $customer->gstin ?? 'No GSTIN' }}</p>
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4">
                                <p class="text-xs sm:text-sm text-gray-700">{{ $customer->phone }}</p>
                            </td>
                            <td class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4">
                                <p class="text-xs sm:text-sm text-gray-700">{{ $customer->gstin ?? '-' }}</p>
                            </td>
                            <td class="hidden lg:table-cell px-2 sm:px-6 py-3 sm:py-4">
                                <p class="text-xs sm:text-sm text-gray-700 truncate max-w-xs">{{ $customer->address ?? '-' }}</p>
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4">
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $customer->state_code ?? '-' }}
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
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="flex items-center space-x-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 first:rounded-t-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="border-t border-gray-100">
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
                            <td colspan="6" class="px-2 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-xs sm:text-sm">No customers found</p>
                                    <a href="{{ route('customers.create') }}" class="mt-2 sm:mt-4 text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Add your first customer</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $customers->links() }}
    </div>
@endsection
