<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PetHouse'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Google Fonts: Poppins (tetap seperti kode native) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Poppins', sans-serif !important; 
            background-color: #faf9f6 !important;
            color: #1f2937 !important;
            padding-top: 64px !important; /* Untuk navbar fixed */
        }
        
        :root {
            --primary: #0d9488;   /* teal utama */
            --secondary: #f43f5e; /* pink cute */
            --accent: #fbbf24;    /* amber/kuning ceria */
        }

        html, body {
            overflow-x: hidden; /* Cegah horizontal scroll */
        }

        @media (max-width: 767px) {
            body {
                padding-top: 64px;
            }
        }

        /* Gaya untuk halaman publik - override Breeze default */
        .min-h-screen {
            min-height: 0 !important;
            background-color: transparent !important;
        }

        .bg-gray-100 {
            background-color: transparent !important;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    @include('public.partials.navbar')
    
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    @include('public.partials.footer')

    <!-- AOS Init -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 80,
            easing: 'ease-out-quart'
        });
    </script>

    @stack('scripts')
</body>
</html>