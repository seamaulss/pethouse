<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Login') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    .bg-login {
        background-image: url('{{ asset('images/login.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Optional: overlay biar teks lebih kebaca */
    .bg-login::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: -1;
    }
</style>


</head>

<body class="bg-login min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl max-w-md p-8">

        <!-- Logo -->
        <div class="text-center mb-8">
            <img
                src="{{ asset('storage/logos/logos.png') }}"
                alt="LARAPetHouse Logo"
                class="mx-auto mb-4"
                width="80"
                height="80">

            <h1 class="text-3xl font-bold text-gray-800">
                Login {{ config('app.name', 'PetHouse') }}
            </h1>

            <p class="text-gray-600 mt-2">
                Sistem Manajemen Klinik & Boarding Hewan
            </p>
        </div>


        <!-- Session Status -->
        @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email/Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-teal-600"></i> {{ __('Username atau Email') }}
                </label>
                <input type="text"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="off"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-teal-600"></i> {{ __('Password') }}
                </label>
                <input type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="off"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <div class="mt-2 text-sm text-gray-600">
                    <input type="checkbox" onclick="togglePassword()" id="showPassword">
                    <label for="showPassword">Tampilkan password</label>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-500 text-teal-600 font-bold py-3 rounded-lg transition shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i> {{ __('Masuk') }}
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Belum punya akun?
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-teal-600 font-semibold hover:underline">
                    Daftar di sini
                </a>
                @else
                <span class="text-teal-600 font-semibold">Hubungi administrator</span>
                @endif
            </p>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ config('app.name', 'PetHouse') }} - All Rights Reserved
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        // Initialize Alpine.js if using
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Add any additional initialization here
        });
    </script>
</body>

</html>