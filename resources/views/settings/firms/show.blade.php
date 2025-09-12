@extends('layouts.app')

@section('breadcrumb')
    Settings > Firm Management > Firm Details
@endsection

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded shadow-md border border-gray-300">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            @if($firm->logo)
                                <img class="h-12 w-12 rounded-full object-cover"
                                     src="{{ Storage::url($firm->logo) }}"
                                     alt="{{ $firm->name }}">
                            @else
                                <span class="material-icons text-xl text-blue-600">business</span>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-lg md:text-xl font-bold text-gray-800">{{ $firm->name }}</h1>
                            <p class="text-sm text-gray-600">{{ $firm->registration_number ?: 'No registration number' }}</p>
                            <p class="text-xs text-gray-500">{{ $firm->email ?: 'No email address' }}</p>
                            <p class="text-xs text-gray-400">Firm ID: {{ $firm->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('settings.firms.edit', $firm->id) }}"
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Firm
                    </a>
                    <a href="{{ route('settings.firms.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to Firms
                    </a>
                </div>
            </div>
        </div>


        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Firm Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-blue-500 mr-2 text-sm">business</span>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name</label>
                                <p class="text-xs text-gray-900 font-medium">{{ $firm->name }}</p>
                            </div>
                            @if($firm->registration_number)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Registration Number</label>
                                <p class="text-xs text-gray-900">{{ $firm->registration_number }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                                <div class="mt-1">
                                    <span class="inline-block {{ $firm->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs font-medium">
                                        {{ ucfirst($firm->status) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Created</label>
                                <p class="text-xs text-gray-900">{{ $firm->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-green-500 mr-2 text-sm">contact_mail</span>
                            Contact Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($firm->email)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Email Address</label>
                                <p class="text-xs text-gray-900">{{ $firm->email }}</p>
                            </div>
                            @endif
                            @if($firm->phone)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                                <p class="text-xs text-gray-900">{{ $firm->phone }}</p>
                            </div>
                            @endif
                            @if($firm->address)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                                <p class="text-xs text-gray-900">{{ $firm->address }}</p>
                            </div>
                            @endif
                            @if($firm->settings && isset($firm->settings['website']))
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Website</label>
                                <p class="text-xs text-gray-900">{{ $firm->settings['website'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Users Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-purple-500 mr-2 text-sm">people</span>
                            Users ({{ $firm->users()->count() }})
                        </h3>
                        @if($firm->users()->count() > 0)
                            <div class="space-y-3">
                                @foreach($firm->users()->take(5)->get() as $user)
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                <span class="text-xs font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-900">{{ $user->name }}</h4>
                                                <p class="text-xs text-gray-600 mt-1">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($user->roles->count() > 0)
                                                <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">
                                                    {{ $user->roles->count() }} {{ $user->roles->count() === 1 ? 'role' : 'roles' }}
                                                </span>
                                            @else
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">No roles</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($user->roles->count() > 0)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-500 mb-2">Roles:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->roles->take(3) as $role)
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">{{ $role->name }}</span>
                                            @endforeach
                                            @if($user->roles->count() > 3)
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">+{{ $user->roles->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                                @if($firm->users()->count() > 5)
                                    <div class="text-center pt-3">
                                        <p class="text-xs text-gray-500">And {{ $firm->users()->count() - 5 }} more users...</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <span class="material-icons text-gray-400 text-2xl mb-2">people_outline</span>
                                <p class="text-gray-500 text-xs">No users assigned to this firm yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Activity Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-orange-500 mr-2 text-sm">schedule</span>
                            Activity Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Created</label>
                                <p class="text-xs text-gray-900 font-medium">{{ $firm->created_at->format('M d, Y H:i') }}</p>
                                <div class="text-xs text-gray-500 mt-1">{{ $firm->created_at->diffForHumans() }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Updated</label>
                                <p class="text-xs text-gray-900 font-medium">{{ $firm->updated_at->format('M d, Y H:i') }}</p>
                                <div class="text-xs text-gray-500 mt-1">{{ $firm->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('settings.firms.edit', $firm->id) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                                <span class="material-icons text-xs mr-1">edit</span>
                                Edit Firm
                            </a>
                            @if(auth()->user()->hasRole('Super Administrator'))
                            <form action="{{ route('firm.switch') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="firm_id" value="{{ $firm->id }}">
                                <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                                    <span class="material-icons text-xs mr-1">swap_horiz</span>
                                    Switch to Firm
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Firm Statistics -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Firm Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Users:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $firm->users()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Clients:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $firm->clients()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Cases:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $firm->cases()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Partners:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $firm->partners()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Firm Age:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $firm->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    @if($firm->users()->count() === 0)
                    <!-- Danger Zone -->
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <h3 class="text-sm font-semibold text-red-700 mb-4">Danger Zone</h3>
                        <p class="text-xs text-red-600 mb-3">These actions cannot be undone.</p>
                        <button onclick="deleteFirm({{ $firm->id }})"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">delete_forever</span>
                            Delete Firm
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteFirm(firmId) {
    if (confirm('Are you sure you want to delete this firm? This action cannot be undone and will remove all associated data.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/firms/${firmId}`;

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';

        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
