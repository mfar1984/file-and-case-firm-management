@extends('layouts.app')

@section('breadcrumb')
    Profile > Edit Profile
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">person</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Edit Profile</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Update your account's profile information and settings.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Success Message -->
            @if (session('status') === 'profile-updated')
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-green-400 text-sm">check_circle</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-green-800">Profile updated successfully!</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-red-400 text-sm">error</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xs font-medium text-red-800">There were some errors with your submission:</h3>
                            <ul class="mt-2 text-xs text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')
                <!-- Profile Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Profile Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-300 @enderror" placeholder="Enter your full name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-300 @enderror" placeholder="Enter your email address" value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-300 @enderror" placeholder="Enter your phone number" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-300 @enderror" placeholder="Enter your department" value="{{ old('department', $user->department) }}" autocomplete="organization-title">
                            @error('department')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-300 @enderror" placeholder="Any additional notes...">{{ old('notes', $user->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Update Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Update Password</h3>
                    <p class="text-xs text-gray-600 mb-4">Leave blank if you don't want to change your password.</p>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-300 @enderror" placeholder="Enter current password" autocomplete="current-password">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-300 @enderror" placeholder="Enter new password" autocomplete="new-password">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm new password" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs bg-gray-100" value="{{ $user->username ?? 'Not set' }}" readonly>
                            <p class="mt-1 text-xs text-gray-500">Username cannot be changed</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Firm</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs bg-gray-100" value="{{ $user->firm->name ?? 'Not assigned' }}" readonly>
                            <p class="mt-1 text-xs text-gray-500">Firm assignment managed by administrator</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Role</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs bg-gray-100" value="{{ $user->roles->pluck('name')->join(', ') ?: 'No role assigned' }}" readonly>
                            <p class="mt-1 text-xs text-gray-500">Role managed by administrator</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Last Login</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs bg-gray-100" value="{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Never' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('overview') }}" class="w-full md:w-auto px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
