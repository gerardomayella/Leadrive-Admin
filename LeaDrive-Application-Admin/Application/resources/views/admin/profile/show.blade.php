@extends('layouts.admin')

@section('header', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
        <div class="p-6 border-b border-dark-border">
            <h3 class="text-lg font-semibold text-white">Profile Details</h3>
        </div>
        
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-dark-muted mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ $admin->name }}" required
                    class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-dark-muted mb-2">Email Address</label>
                <input type="email" id="email" value="{{ $admin->email }}" disabled
                    class="bg-dark-bg border border-dark-border text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                <p class="mt-1 text-xs text-dark-muted">Email cannot be changed.</p>
            </div>

            <div class="border-t border-dark-border pt-6 mt-6">
                <h4 class="text-md font-medium text-white mb-4">Change Password</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-dark-muted mb-2">New Password</label>
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current"
                            class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-dark-muted mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-dark-border">
                <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
