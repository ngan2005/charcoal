<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển Nhân viên') - Pink Charcoal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pink-charcoal.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#2563eb',
                        'primary-hover': '#1d4ed8',
                        'background-light': '#f8fafc',
                        'background-dark': '#0f172a',
                    },
                    fontFamily: {
                        'display': ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'DEFAULT': '0.375rem',
                        'lg': '0.75rem',
                        'xl': '1rem',
                        'full': '9999px',
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .dark .active-nav {
            background-color: #881337;
            color: #fff1f2;
        }
        #sidebar-toggle:checked ~ aside {
            width: 80px;
        }
        #sidebar-toggle:checked ~ aside .nav-text,
        #sidebar-toggle:checked ~ aside .branding-text,
        #sidebar-toggle:checked ~ aside .footer-text {
            display: none;
        }
        #sidebar-toggle:checked ~ aside .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        #sidebar-toggle:checked ~ aside .branding-container {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        #sidebar-toggle:checked ~ aside .toggle-icon {
            transform: rotate(180deg);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#1c1c1c] dark:text-white transition-colors duration-200">
    <div class="flex h-screen overflow-hidden relative">
        <input class="hidden" id="sidebar-toggle" type="checkbox"/>
        
        @include('staff.partials.sidebar')

        <main class="flex-1 flex flex-col overflow-y-auto" id="main-content">
            @include('staff.partials.header')
            
            <div class="p-8 max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </main>

        @stack('modals')
        @stack('scripts')
    </div>
</body>
</html>
