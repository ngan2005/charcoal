<header class="sticky top-0 z-20 bg-white/80 dark:bg-[#111827]/80 backdrop-blur-md border-b border-[#f3e8ea] dark:border-gray-800 px-8 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
        <h2 class="text-xl font-bold tracking-tight text-gray-800 dark:text-white">@yield('header_title', 'Bảng điều khiển')</h2>
        <div class="hidden lg:flex items-center bg-[#f8fafc] dark:bg-gray-800 rounded-xl px-4 py-2 w-72 border border-gray-100 dark:border-gray-700">
            <span class="material-symbols-outlined text-[#94a3b8] text-xl">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-[#94a3b8] dark:placeholder:text-gray-500 text-gray-800 dark:text-white" placeholder="Tìm kiếm dịch vụ, hồ sơ..." type="text"/>
        </div>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Theme Toggle -->
        <button class="p-2.5 rounded-xl bg-[#f8fafc] dark:bg-gray-800 text-[#475569] dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2 group" onclick="toggleTheme()">
            <span class="material-symbols-outlined">palette</span>
            <span class="text-xs font-semibold hidden sm:inline">Giao diện</span>
        </button>

        <!-- Notifications -->
        <button class="p-2.5 rounded-xl bg-[#f8fafc] dark:bg-gray-800 text-[#475569] dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors relative">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-primary ring-2 ring-white dark:ring-[#111827]"></span>
        </button>

        <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-700 mx-1"></div>

        <!-- User Profile -->
        <!-- User Profile -->
        <a href="{{ route('staff.profile') }}" class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl p-1 transition-colors">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold leading-none text-gray-800 dark:text-white">{{ auth()->user()->FullName ?? 'Nhân viên' }}</p>
                <p class="text-[11px] text-primary font-medium mt-1 uppercase tracking-wider">Nhân viên</p>
            </div>
            @php
                $user = auth()->user();
                $avatarPath = $user->Avatar ? (Str::startsWith($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->FullName ?? 'NV') . '&background=fce7f3&color=ec4899&size=128';
            @endphp
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-xl size-10 border-2 border-primary/10 shadow-sm" style='background-image: url("{{ $avatarPath }}");'></div>

        </a>
    </div>
</header>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        
        if (isDark) {
            html.classList.remove('dark');
            document.getElementById('main-content').style.backgroundColor = '#fdf8f9';
        } else {
            html.classList.add('dark');
            document.getElementById('main-content').style.backgroundColor = '';
        }
        
        // Save preference
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    }

    // Check saved theme on load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    });
</script>
