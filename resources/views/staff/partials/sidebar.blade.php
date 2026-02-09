<aside class="w-64 bg-white dark:bg-[#111827] border-r border-[#f3e8ea] dark:border-gray-800 flex flex-col justify-between p-4 shrink-0 transition-all duration-300 relative z-30 shadow-sm h-screen fixed left-0 top-0">
    <div class="flex flex-col gap-6">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-2 branding-container">
            <img src="{{ asset('images/logo-pink-charcoal.png') }}" class="w-10 h-10 rounded-xl shadow-lg shadow-primary/20 object-cover" alt="Pink Charcoal Logo">
            <div class="flex flex-col branding-text overflow-hidden">
                <h1 class="text-[#111318] dark:text-white text-base font-bold leading-tight whitespace-nowrap">Pink Charcoal</h1>
                <p class="text-[#616f89] dark:text-gray-400 text-xs font-normal whitespace-nowrap">Staff Dashboard</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-1.5">
            <p class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Menu chính</p>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.dashboard')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.dashboard') }}">
                <span class="material-symbols-outlined shrink-0">dashboard</span>
                <p class="text-sm font-semibold nav-text whitespace-nowrap">Tổng quan</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.shifts') || Route::is('staff.shifts.*')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.shifts') }}">
                <span class="material-symbols-outlined shrink-0">event_repeat</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Ca trực</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.pets.*')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.pets') }}">
                <span class="material-symbols-outlined shrink-0">potted_plant</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Quản lý Thú cưng</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.journal.*')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.journal') }}">
                <span class="material-symbols-outlined shrink-0">menu_book</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Nhật ký chăm sóc</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.timekeeping.*')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.timekeeping') }}">
                <span class="material-symbols-outlined shrink-0">fingerprint</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Chấm công</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.leaves.*')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.leaves') }}">
                <span class="material-symbols-outlined shrink-0">edit_document</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Đơn từ / Nghỉ phép</p>
            </a>

            <p class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider mt-2">Cá nhân</p>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl nav-item transition-all @if(Route::is('staff.profile')) active-nav @else hover:bg-rose-50 dark:hover:bg-rose-900/20 text-[#64748b] dark:text-gray-400 @endif" href="{{ route('staff.profile') }}">
                <span class="material-symbols-outlined shrink-0">account_circle</span>
                <p class="text-sm font-medium nav-text whitespace-nowrap">Hồ sơ cá nhân</p>
            </a>
        </nav>
    </div>

    <!-- Sidebar Footer -->
    <div class="flex flex-col gap-4">
        <!-- Collapse Toggle -->
        <label class="flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer absolute -right-4 top-10 shadow-lg hover:bg-gray-50 dark:hover:bg-gray-800 z-50 transition-colors" for="sidebar-toggle">
            <span class="material-symbols-outlined text-sm toggle-icon transition-transform duration-300">chevron_left</span>
        </label>

        <!-- Create Appointment Button -->
        <a href="{{ route('staff.appointments.create') }}" class="flex w-full items-center justify-center rounded-xl h-11 bg-primary text-white text-sm font-bold tracking-wide hover:bg-primary-hover transition-all px-2 shadow-md shadow-primary/20">
            <span class="material-symbols-outlined text-xl shrink-0">add_circle</span>
            <span class="truncate ml-2 footer-text">Tạo lịch hẹn</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center rounded-xl h-11 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-bold tracking-wide hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                <span class="material-symbols-outlined text-xl shrink-0">logout</span>
                <span class="truncate ml-2 footer-text">Đăng xuất</span>
            </button>
        </form>
    </div>
</aside>
