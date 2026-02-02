<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PetHouse') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-gradient-custom {
            background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
        }
    </style>
</head>

<body class="bg-gradient-custom min-h-screen flex items-center justify-center p-4">

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
               Registrasi {{ config('app.name', 'LARAPetHouse') }}
            </h1>

            <p class="text-gray-600 mt-2">
                Sistem Manajemen Klinik & Boarding Hewan
            </p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any() && !session('success'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if (!session('success'))
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-teal-600"></i> Username
                </label>
                <input type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autocomplete="off"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg
                          focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-teal-600"></i> Email
                </label>
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="off"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg
                          focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-teal-600"></i> Password
                </label>
                <input type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg
                          focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-teal-600"></i> Konfirmasi Password
                </label>
                <input type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg
                          focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Register Button -->
            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-500 text-teal-700 font-bold py-3 rounded-lg transition shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> Daftar
            </button>
        </form>
        @endif

        <!-- Login Link -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">
                    Login di sini
                </a>
            </p>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ config('app.name', 'PetHouse') }} - All Rights Reserved
        </div>

    </div>

</body>

</html>