<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal': {
                            DEFAULT: '#0d9488',
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                        'pink': {
                            DEFAULT: '#f43f5e',
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        },
                        'amber': {
                            DEFAULT: '#fbbf24',
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        'beige': '#faf9f6',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    borderRadius: {
                        '3xl': '1.75rem',
                        '4xl': '2rem'
                    }
                }
            }
        }
    </script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #faf9f6 0%, #f5f3ef 100%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(13, 148, 136, 0.3);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: translate(50%, -50%);
        }

        .quick-action {
            position: relative;
            overflow: hidden;
        }

        .quick-action::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .quick-action:hover::after {
            opacity: 1;
        }

        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .7; transform: scale(1.1); }
        }

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: rgba(13, 148, 136, 0.05);
            transform: translateX(8px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen flex">
    <!-- Include Sidebar -->
    @include('admin.layouts.navbar')

    <!-- Overlay Gelap untuk Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-60 z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <!-- Main Content Area -->
    <div class="flex-1 lg:ml-64 transition-all duration-300">
        <!-- Include Navbar -->
        @include('admin.layouts.navbar')
        
        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-10 max-w-7xl mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (mobileMenuBtn && sidebar && overlay) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });

            // Close sidebar when clicking menu links on mobile
            document.querySelectorAll('#adminSidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>