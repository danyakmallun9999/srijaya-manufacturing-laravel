<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="px-8 pt-8 pb-6 text-center">
            <div class="w-16 h-16 bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4 overflow-hidden">
                <img src="{{ asset('images/idefu.png') }}" alt="Idefu Logo" class="w-12 h-12 object-contain">
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Welcome Back</h1>
            <p class="text-slate-500 text-sm">Masuk ke sistem manajemen produksi & order</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="px-8 pb-8">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email Address
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('email') border-red-300 focus:ring-red-500 @enderror"
                    placeholder="Enter your email">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('password') border-red-300 focus:ring-red-500 @enderror"
                    placeholder="Enter your password">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-6">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900 focus:ring-2">
                <label for="remember_me" class="ml-2 text-sm text-slate-600">
                    Remember me
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-slate-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-all duration-200 transform hover:translate-y-[-1px] active:translate-y-0">
                Sign In
            </button>

        </form>
    </div>

    <!-- Optional: Add some subtle animation -->
    <script>
        // Simple fade-in animation
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('body > div');
            loginForm.style.opacity = '0';
            loginForm.style.transform = 'translateY(20px)';

            setTimeout(() => {
                loginForm.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                loginForm.style.opacity = '1';
                loginForm.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

</body>

</html>
