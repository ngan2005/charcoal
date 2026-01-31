<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Bảng điều khiển Nhân viên - Pet Care Center</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "pastel-pink": "#fff0f3",
                        "soft-blue": "#eef2ff",
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
        }#sidebar-toggle:checked ~ aside {
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
        }.theme-pink { background-color: #fff0f3 !important; }
        .theme-blue { background-color: #eef2ff !important; }#theme-modal-toggle:checked ~ #theme-modal {
            display: flex;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-white transition-colors duration-200">
    <div class="flex h-screen overflow-hidden relative">
        <input class="hidden" id="sidebar-toggle" type="checkbox"/>
        <input class="hidden" id="theme-modal-toggle" type="checkbox"/>
        
        @include('admin.partials.sidebar')

        <main class="flex-1 flex flex-col overflow-y-auto" id="main-content">
            @include('admin.partials.header')
            
            <div class="p-8 max-w-6xl mx-auto w-full">
                @yield('content')
            </div>
        </main>

        <div class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center p-4" id="theme-modal">
            <div class="bg-white dark:bg-[#1a202c] rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-xl font-bold">Chỉnh sửa giao diện</h3>
                    <label class="cursor-pointer text-gray-400 hover:text-gray-600" for="theme-modal-toggle">
                        <span class="material-symbols-outlined">close</span>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chọn tông màu nền phù hợp với không gian làm việc của bạn.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-primary transition-all text-left group" onclick="document.documentElement.classList.remove('dark'); document.getElementById('main-content').style.backgroundColor = '#f6f6f8'">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-gray-400">light_mode</span>
                            </div>
                            <div>
                                <p class="font-bold">Mặc định</p>
                                <p class="text-xs text-gray-500">Nền trắng sạch sẽ, chuyên nghiệp</p>
                            </div>
                        </button>
                        <button class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 dark:border-gray-700 hover:border-primary transition-all text-left group" onclick="document.documentElement.classList.add('dark'); document.getElementById('main-content').style.backgroundColor = ''">
                            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-yellow-400">dark_mode</span>
                            </div>
                            <div>
                                <p class="font-bold">Chế độ tối</p>
                                <p class="text-xs text-gray-500">Tiết kiệm pin, dịu mắt khi làm đêm</p>
                            </div>
                        </button>
                        <button class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-primary transition-all text-left group bg-[#fff0f3]" onclick="document.documentElement.classList.remove('dark'); document.getElementById('main-content').style.backgroundColor = '#fff0f3'">
                            <div class="w-12 h-12 rounded-lg bg-pink-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-pink-600">favorite</span>
                            </div>
                            <div>
                                <p class="font-bold text-pink-700">Hồng Pastel</p>
                                <p class="text-xs text-pink-600">Nhẹ nhàng, ấm cúng cho Pet Shop</p>
                            </div>
                        </button>
                        <button class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-primary transition-all text-left group bg-[#eef2ff]" onclick="document.documentElement.classList.remove('dark'); document.getElementById('main-content').style.backgroundColor = '#eef2ff'">
                            <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-blue-600">water_drop</span>
                            </div>
                            <div>
                                <p class="font-bold text-blue-700">Xanh Dương</p>
                                <p class="text-xs text-blue-600">Hiện đại, tin cậy và mát mẻ</p>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-gray-800/50">
                    <label class="block w-full text-center py-3 bg-primary text-white font-bold rounded-lg cursor-pointer hover:bg-blue-700 transition-colors" for="theme-modal-toggle">Hoàn tất</label>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
