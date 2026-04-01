<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pink Charcoal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pink-charcoal.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav {
            background-color: #f0f2f4;
            color: #111318;
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

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #dbdfe6;
            border-radius: 10px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #616f89;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-white transition-colors duration-200">
    <div class="flex h-screen overflow-hidden relative">
        <input class="hidden" id="sidebar-toggle" type="checkbox"/>

        @include('admin.partials.sidebar')

        <main class="flex-1 flex flex-col overflow-y-auto" id="main-content">
            @include('admin.partials.header')

            <div class="p-8 max-w-6xl mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
