@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-gray-100">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        <!-- Image Section -->
        <div class="hidden md:block w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
            <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 flex flex-col justify-center p-12 text-white">
                <h2 class="text-4xl font-bold mb-4">LeadDrive</h2>
                <p class="text-lg opacity-90">Start your driving journey with us. Professional instructors, flexible schedules, and guaranteed success.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
                <p class="text-gray-500">Please enter your details to sign in</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email_or_phone" class="block text-sm font-medium text-gray-700 mb-2">Email or Phone</label>
                    <input 
                        type="text" 
                        id="email_or_phone" 
                        name="email_or_phone" 
                        value="{{ old('email_or_phone') }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="Enter your email or phone"
                        required 
                        autofocus
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-700">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary-600 text-white font-bold py-3 px-4 rounded-lg transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register.step1') }}" class="font-medium text-primary hover:text-primary-700">Sign up for free</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection