<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PetHouse')</title>
    <meta name="description" content="@yield('meta_description')">

    <!-- Tailwind CDN (SAMA PERSIS PHP NATIVE) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #faf9f6;
            color: #1f2937;
        }

        :root {
            --primary: #0d9488;
            --secondary: #f43f5e;
            --accent: #fbbf24;
        }

        html, body {
            overflow-x: hidden;
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('public.partials.navbar')

    @yield('content')

    @include('public.partials.footer')

    <!-- AOS -->
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
