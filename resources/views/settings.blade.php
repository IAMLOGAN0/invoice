@extends('layouts.app')

@section('page_title', 'Settings')

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    <div class="max-w-4xl mx-auto">

        {{-- Settings Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm text-white p-8 mb-8">
            <h2 class="text-3xl font-bold mb-2">Settings</h2>
            <p class="text-blue-100">Manage your account and application preferences</p>
        </div>

        {{-- Settings Tabs --}}
        <div class="flex space-x-4 mb-6 border-b border-gray-200">
            <button class="settings-tab px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-semibold" data-tab="account">
                Account Settings
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-semibold" data-tab="shop">
                Shop Settings
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-semibold" data-tab="invoice">
                Invoice Settings
            </button>
        </div>

        {{-- Account Settings Tab --}}
        <div id="account-tab" class="settings-content space-y-6">
            {{-- Profile Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
                
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                
                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('current_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('password_confirmation')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-4">Danger Zone</h3>
                <p class="text-gray-600 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                
                <form method="POST" action="{{ route('profile.destroy') }}" class="flex justify-end">
                    @csrf
                    @method('DELETE')
                    
                    <button type="submit" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>

        {{-- Shop Settings Tab --}}
        <div id="shop-tab" class="settings-content space-y-6 hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shop Information</h3>
                
                @php
                    $shop = Auth::user()->shop;
                @endphp

                <form method="POST" action="{{ route('shop.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-2">Shop Name</label>
                        <input type="text" id="shop_name" name="name" value="{{ $shop?->name ?? '' }}" placeholder="Your Shop Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="gstin" class="block text-sm font-medium text-gray-700 mb-2">GST Number</label>
                            <input type="text" id="gstin" name="gstin" value="{{ $shop?->gstin ?? '' }}" placeholder="22AAAAA0000A1Z5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('gstin')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="state_code" class="block text-sm font-medium text-gray-700 mb-2">State Code</label>
                            <input type="text" id="state_code" name="state_code" value="{{ $shop?->state_code ?? '' }}" placeholder="22" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            @error('state_code')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-2">Shop Address</label>
                        <textarea id="shop_address" name="address" rows="3" placeholder="Enter your complete shop address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $shop?->address ?? '' }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Save Shop Details
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Invoice Settings Tab --}}
        <div id="invoice-tab" class="settings-content space-y-6 hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Preferences</h3>
                
                <form class="space-y-4">
                    <div>
                        <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-2">Invoice Prefix</label>
                        <input type="text" id="invoice_prefix" name="invoice_prefix" placeholder="INV" value="INV" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        <p class="text-sm text-gray-500 mt-1">Example: INV-001, INV-002, etc.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="invoice_start_number" class="block text-sm font-medium text-gray-700 mb-2">Starting Invoice Number</label>
                            <input type="number" id="invoice_start_number" name="invoice_start_number" value="1" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        
                        <div>
                            <label for="invoice_notes" class="block text-sm font-medium text-gray-700 mb-2">Default Invoice Notes</label>
                            <input type="text" id="invoice_notes" name="invoice_notes" placeholder="Thank you for your business" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>
                    </div>
                    
                    <div>
                        <label for="invoice_terms" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                        <textarea id="invoice_terms" name="invoice_terms" rows="3" placeholder="Enter your payment terms and conditions" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Invoice Display Options</h4>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="show_gst" name="show_gst" checked class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                            <label for="show_gst" class="ml-3 text-gray-700">Show GST on invoices</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="show_discount" name="show_discount" checked class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                            <label for="show_discount" class="ml-3 text-gray-700">Show discount column</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="show_quantity" name="show_quantity" checked class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                            <label for="show_quantity" class="ml-3 text-gray-700">Show quantity column</label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Save Invoice Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Hide all content
                document.querySelectorAll('.settings-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Remove active state from all tabs
                document.querySelectorAll('.settings-tab').forEach(t => {
                    t.classList.remove('border-blue-600', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-600');
                });
                
                // Show selected content
                document.getElementById(tabName + '-tab').classList.remove('hidden');
                
                // Add active state to clicked tab
                this.classList.remove('border-transparent', 'text-gray-600');
                this.classList.add('border-blue-600', 'text-blue-600');
            });
        });
    </script>
@endsection
