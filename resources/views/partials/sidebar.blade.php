<aside class="hidden md:flex flex-col w-[260px] bg-white dark:bg-[#1a1a1a] justify-between border-r border-gray-200 dark:border-gray-800 transition-all duration-300 z-30 flex-shrink-0" id="sidebar">
    <div>
        <div class="h-20 flex items-center justify-start px-6 border-b border-gray-100 dark:border-gray-800 sidebar-header gap-3">
            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="flex items-center gap-3 sidebar-logo-text min-w-0">
                <img src="{{ asset('images/logo-pink-charcoal.png') }}" class="w-9 h-9 rounded-lg object-cover" alt="Pink Charcoal">
                <h1 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">Pink Charcoal</h1>
            </div>
        </div>
        <nav class="p-4 space-y-2 mt-4">
            <a class="flex items-center gap-4 px-4 py-3 bg-primary/10 text-primary rounded-xl transition-all hover:bg-primary hover:text-white group overflow-hidden whitespace-nowrap cursor-pointer" onclick="switchView('dashboard', this); return false;">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">dashboard</span>
                <span class="font-bold sidebar-text">Tổng quan</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary rounded-xl transition-all group overflow-hidden whitespace-nowrap cursor-pointer" onclick="switchView('services', this); return false;">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">spa</span>
                <span class="font-semibold sidebar-text">Dịch vụ</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary rounded-xl transition-all group overflow-hidden whitespace-nowrap cursor-pointer" onclick="switchView('store', this); return false;">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">storefront</span>
                <span class="font-semibold sidebar-text">Cửa hàng</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary rounded-xl transition-all group overflow-hidden whitespace-nowrap cursor-pointer" onclick="switchView('appointments', this); return false;">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">calendar_month</span>
                <span class="font-semibold sidebar-text">Lịch hẹn</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary rounded-xl transition-all group overflow-hidden whitespace-nowrap cursor-pointer" href="{{ route('pets.index') }}">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">pets</span>
                <span class="font-semibold sidebar-text">Thú cưng</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary rounded-xl transition-all group overflow-hidden whitespace-nowrap" href="#">
                <span class="material-symbols-outlined flex-shrink-0 group-hover:scale-110 transition-transform">article</span>
                <span class="font-semibold sidebar-text">Blog</span>
            </a>
        </nav>
    </div>
    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
        <a href="#" class="w-full flex items-center justify-start gap-4 px-4 py-3 text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-all overflow-hidden whitespace-nowrap">
             <span class="material-symbols-outlined flex-shrink-0">settings</span>
            <span class="font-semibold sidebar-text">Cài đặt</span>
        </a>
    </div>
</aside>
