<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PetHouse Admin')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-teal-600 text-white rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
    
    <!-- Include Sidebar -->
    @include('admin.partials.sidebar')
    
    <!-- Main Content Area -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Mobile Header (Optional) -->
        <div class="lg:hidden bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">@yield('title', 'PetHouse Admin')</h1>
                    <p class="text-sm text-gray-600">Administrator Panel</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">{{ auth()->user()->username }}</span>
                    <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-4 lg:p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true
        });
    </script>
    
    <!-- Sidebar Mobile Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuBtn && sidebar && overlay) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
                
                // Close sidebar when clicking on links in mobile
                const menuLinks = sidebar.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024) {
                            sidebar.classList.add('-translate-x-full');
                            overlay.classList.add('hidden');
                        }
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>