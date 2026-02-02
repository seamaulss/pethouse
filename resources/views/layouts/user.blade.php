<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PetHouse')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS Animation -->
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

        .navbar-dashboard {
            background: linear-gradient(135deg, var(--primary), #2dd4bf);
        }

        .card-dashboard {
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: white;
            border: 2px solid transparent;
        }

        .card-dashboard:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: var(--primary);
        }

        .btn-logout {
            background: var(--secondary);
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #e11d48;
            transform: scale(1.05);
        }

        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25d366, #128C7E);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.15);
        }

        @media (max-width: 640px) {
            .whatsapp-float {
                width: 55px;
                height: 55px;
                bottom: 15px;
                right: 15px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('partials.user-navbar')

    <!-- Konten -->
    @yield('content')

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