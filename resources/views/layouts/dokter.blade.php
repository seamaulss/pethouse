<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dokter PetHouse</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
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
        
        .navbar-dokter {
            background: linear-gradient(135deg, var(--primary), #2dd4bf);
        }
        
        .card-form {
            border-radius: 2rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            background: white;
        }
        
        .card-konsultasi {
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: white;
            border: 2px solid transparent;
        }
        
        .card-konsultasi:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: var(--primary);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #2dd4bf);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 20px 40px rgba(13,148,136,0.4);
        }
        
        .btn-terima {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-terima:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 30px rgba(16,185,129,0.4);
        }
        
        .btn-selesai {
            background: linear-gradient(135deg, var(--accent), #f59e0b);
            transition: all 0.3s ease;
        }
        
        .btn-selesai:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 30px rgba(251,191,36,0.4);
        }
        
        .btn-wa {
            background: linear-gradient(135deg, #25d366, #128C7E);
            transition: all 0.3s ease;
        }
        
        .btn-wa:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 15px 30px rgba(37,211,102,0.4);
        }
        
        .status-badge {
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
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
            .whatsapp-float {
                width: 55px;
                height: 55px;
                bottom: 15px;
                right: 15px;
            }
        }
        
        .chat-bubble-doctor {
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 1rem 1rem 1rem 0.25rem;
        }
        
        .chat-bubble-client {
            background-color: #f3f4f6;
            color: #374151;
            border-radius: 1rem 1rem 0.25rem 1rem;
        }
    </style>
</head>
<body>

    @include('dokter.partials.navbar')

    <!-- Konten Utama -->
    <main>
        @yield('content')
    </main>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/6285942173668?text=Halo%20admin,%20saya%20dokter%20dan%20butuh%20bantuan"
       class="whatsapp-float text-4xl sm:text-5xl" target="_blank" rel="noopener" aria-label="Hubungi Admin via WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

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