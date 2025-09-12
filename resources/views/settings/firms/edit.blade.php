@extends('layouts.app')

@section('breadcrumb')
    Settings > Firm Management > Edit Firm
@endsection

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            @if($firm->logo)
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ Storage::url($firm->logo) }}"
                                     alt="{{ $firm->name }}">
                            @else
                                <span class="material-icons text-lg text-blue-600">business</span>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Firm</h1>
                            <p class="text-xs text-gray-500 mt-1">{{ $firm->name }} ({{ $firm->email ?: 'No email' }})</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <form action="{{ route('settings.firms.update', $firm) }}" method="POST" enctype="multipart/form-data" class="space-y-0">
                @csrf
                @method('PUT')


                <!-- Firm Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Firm Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                            <input type="text" name="name" required
                                   value="{{ old('name', $firm->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., ABC Law Firm, XYZ Legal Associates">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Registration Number</label>
                            <input type="text" name="registration_number"
                                   value="{{ old('registration_number', $firm->registration_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., LLP123456, SSM789012">
                            @error('registration_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $firm->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., info@firm.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone"
                                   value="{{ old('phone', $firm->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., +60 3-1234 5678">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Complete firm address...">{{ old('address', $firm->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Branding Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Branding & Logo</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Firm Logo</label>

                            @if($firm->logo)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-600 mb-2">Current Logo:</p>
                                    <img src="{{ Storage::url($firm->logo) }}"
                                         alt="{{ $firm->name }}"
                                         class="h-16 w-16 object-cover rounded border border-gray-200">
                                </div>
                            @endif

                            <input type="file" name="logo" accept="image/*"
                                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Upload JPEG, PNG, JPG, GIF - Max: 2MB</p>
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Firm Settings Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Firm Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status', $firm->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $firm->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Total Users</label>
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-600">
                                {{ $firm->users()->count() }} users assigned
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Firm Information -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-700 mb-4">Current Firm Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div>
                            <span class="text-blue-600">Created:</span>
                            <div class="text-blue-800">{{ $firm->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Last Updated:</span>
                            <div class="text-blue-800">{{ $firm->updated_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Total Users:</span>
                            <div class="text-blue-800">{{ $firm->users()->count() }} users</div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('settings.firms.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Firm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
