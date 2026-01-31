<aside class="w-64 bg-white dark:bg-[#1a202c] border-r border-[#dbdfe6] dark:border-gray-700 flex flex-col justify-between p-4 shrink-0 transition-all duration-300 relative">
    <div class="flex flex-col gap-8">
        <a class="flex items-center gap-3 px-2 branding-container" href="{{ route('shop') }}">
            <div class="rounded-lg overflow-hidden shrink-0 w-11 h-11 border border-gray-200 bg-white">
                <img
                    src="{{ asset('images/pink-charcoal.jpg') }}"
                    alt="Pink Charcoal"
                    class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col branding-text overflow-hidden">
                <h1 class="text-[#111318] dark:text-white text-base font-bold leading-tight whitespace-nowrap">Pink Charcoal</h1>
                <p class="text-[#616f89] dark:text-gray-400 text-xs font-normal whitespace-nowrap">Nhân viên</p>
            </div>
        </a>
        <nav class="flex flex-col gap-1">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg nav-item {{ request()->routeIs('admin.dashboard') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined shrink-0">dashboard</span>
                <p class="text-sm font-semibold nav-text whitespace-nowrap">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300 nav-item" href="#">
                <span class="material-symbols-outlined shrink-0">shopping_bag</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý đơn hàng</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg nav-item {{ request()->routeIs('admin.products.*') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.products.index') }}">
                <span class="material-symbols-outlined shrink-0">inventory_2</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý sản phẩm</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg nav-item {{ request()->routeIs('admin.services.*') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.services.index') }}">
                <span class="material-symbols-outlined shrink-0">content_cut</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý dịch vụ</p>
            </a>
            @php($isUserMenuOpen = request()->routeIs('admin.users.*'))
            <details class="group" {{ $isUserMenuOpen ? 'open' : '' }}>
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg nav-item cursor-pointer list-none {{ $isUserMenuOpen ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}">
                    <span class="material-symbols-outlined shrink-0">manage_accounts</span>
                    <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý người dùng</p>
                    <span class="material-symbols-outlined text-base ml-auto transition-transform duration-200 nav-text group-open:rotate-180">expand_more</span>
                </summary>
                <div class="mt-1 flex flex-col gap-1 pl-9">
                    <a class="flex items-center gap-2 px-3 py-2 rounded-lg nav-item {{ request()->routeIs('admin.users.customers') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.users.customers') }}">
                        <span class="material-symbols-outlined text-base shrink-0">group</span>
                        <p class="text-sm font-medium nav-text whitespace-nowrap">Khách hàng</p>
                    </a>
                    <a class="flex items-center gap-2 px-3 py-2 rounded-lg nav-item {{ request()->routeIs('admin.users.staff') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.users.staff') }}">
                        <span class="material-symbols-outlined text-base shrink-0">badge</span>
                        <p class="text-sm font-medium nav-text whitespace-nowrap">Nhân viên</p>
                    </a>
                </div>
            </details>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg nav-item {{ request()->routeIs('admin.categories.*') ? 'active-nav text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300' }}" href="{{ route('admin.categories.index') }}">
                <span class="material-symbols-outlined shrink-0">category</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý danh mục</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-[#616f89] dark:text-gray-300 relative nav-item" href="#">
                <span class="material-symbols-outlined shrink-0">chat_bubble</span>
                <p class="text-sm font-medium flex-1 nav-text whitespace-nowrap">Chat</p>
                <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full absolute top-1 right-2 nav-text">3</span>
            </a>
        </nav>
    </div>
    <div class="flex flex-col gap-4">
        <label class="flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer absolute -right-4 top-10 shadow-md hover:bg-gray-50 dark:hover:bg-gray-700 z-50" for="sidebar-toggle">
            <span class="material-symbols-outlined text-sm toggle-icon transition-transform duration-300">chevron_left</span>
        </label>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex w-full items-center justify-center rounded-lg h-11 bg-red-500 text-white text-sm font-bold tracking-[0.015em] hover:bg-red-600 transition-colors px-2" type="submit">
                <span class="material-symbols-outlined text-xl shrink-0">logout</span>
                <span class="truncate ml-2 footer-text">Đăng xuất</span>
            </button>
        </form>
    </div>
</aside>
