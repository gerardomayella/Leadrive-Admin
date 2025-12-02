@extends('layouts.admin')

@section('header', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
        <div class="p-6 border-b border-dark-border">
            <h3 class="text-lg font-semibold text-white">New User Details</h3>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-dark-muted mb-2">Full Name</label>
                <input type="text" id="name" name="name" required
                    class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-dark-muted mb-2">Email Address</label>
                <input type="email" id="email" name="email" required
                    class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-muted mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-muted mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="role" class="block text-sm font-medium text-dark-muted mb-2">Role</label>
                    <select id="role" name="role" required
                        class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="pemilik_kursus">Pemilik Kursus</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label for="nomor_hp" class="block text-sm font-medium text-dark-muted mb-2">Phone Number</label>
                    <input type="text" id="nomor_hp" name="nomor_hp"
                        class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 placeholder-gray-600">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-dark-border">
                <a href="{{ route('admin.users.index') }}" class="text-dark-muted hover:text-white mr-4 text-sm font-medium transition-colors">Cancel</a>
                <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
