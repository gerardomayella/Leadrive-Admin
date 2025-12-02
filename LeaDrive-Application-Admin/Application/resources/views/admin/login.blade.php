<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LeadDrive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#ff7f00',
                        dark: '#0f1419',
                        card: '#1a2332',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-card rounded-2xl shadow-2xl p-8 border border-gray-800">
            <div class="text-center mb-8">
                <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold tracking-wide mb-4 border border-primary/20">
                    ADMIN PANEL
                </span>
                <h1 class="text-3xl font-bold text-white mb-2">LeadDrive</h1>
                <p class="text-gray-400 text-sm">Sign in to manage your driving school</p>
            </div>

            @if(session('success'))
                <div class="bg-green-900/30 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-900/30 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-dark border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-white placeholder-gray-600 transition-all"
                        placeholder="admin@example.com"
                        required 
                        autofocus
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 bg-dark border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-white placeholder-gray-600 transition-all"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-700 bg-dark text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-lg transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-gray-600">
                &copy; {{ date('Y') }} LeadDrive. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
