<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PetHouse - Petugas')</title>
    
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
            background-color: #faf9f6; /* beige lembut konsisten */
            color: #1f2937;
        }
        
        :root {
            --primary: #0d9488;   /* teal utama */
            --secondary: #f43f5e; /* pink cute */
            --accent: #fbbf24;    /* amber ceria */
        }

        .navbar-petugas {
            background: linear-gradient(135deg, var(--primary), #2dd4bf);
        }

        .card-hewan {
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: white;
            border: 2px solid transparent;
        }
        .card-hewan:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: var(--primary);
        }

        .btn-wa {
            background: linear-gradient(135deg, #25d366, #128C7E);
            transition: all 0.3s ease;
        }
        .btn-wa:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 30px rgba(37,211,102,0.4);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--accent), #f59e0b);
            transition: all 0.3s ease;
        }
        .btn-update:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 30px rgba(251,191,36,0.4);
        }

        .badge-notif {
            position: absolute;
            top: -8px;
            right: -8px;
            min-width: 20px;
            height: 20px;
            background: var(--secondary);
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
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
            box-shadow: 0 8px 25px rgba(37,211,102,0.5);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover {
            transform: scale(1.15);
        }

        @media (max-width: 640px) {
            .whatsapp-float { width: 55px; height: 55px; bottom: 15px; right: 15px; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('petugas.layouts.navbar')
    
    <main>
        @yield('content')
    </main>
    
    <!-- AOS JS -->
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