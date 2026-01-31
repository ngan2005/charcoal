<header class="sticky top-0 z-10 bg-white/80 dark:bg-[#1a202c]/80 backdrop-blur-md border-b border-[#f0f2f4] dark:border-gray-700 px-8 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
        <h2 class="text-xl font-bold tracking-tight">Bảng điều khiển</h2>
        <div class="hidden lg:flex items-center bg-[#f0f2f4] dark:bg-gray-800 rounded-lg px-3 py-1.5 w-72">
            <span class="material-symbols-outlined text-[#616f89] text-xl">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-[#616f89] dark:placeholder:text-gray-500" placeholder="Tìm kiếm khách hàng, đơn hàng..." type="text"/>
        </div>
    </div>
    <div class="flex items-center gap-4">
        @php
            $currentUser = auth()->user();
            $displayName = $currentUser?->FullName ?? 'Người dùng';
            $displayRole = $currentUser?->role?->RoleName ?? 'Tài khoản';
            $avatarUrl = $currentUser?->Avatar
                ?: 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=E2E8F0&color=1F2937';
            $notificationCount = session('notification_count', $notificationCount ?? 0);
        @endphp
        <label class="p-2 rounded-lg bg-[#f0f2f4] dark:bg-gray-800 text-[#111318] dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center gap-2 group" for="theme-modal-toggle">
            <span class="material-symbols-outlined">palette</span>
            <span class="text-xs font-semibold hidden sm:inline">Chỉnh sửa giao diện</span>
        </label>
        <button class="p-2 rounded-lg bg-[#f0f2f4] dark:bg-gray-800 text-[#111318] dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors relative" aria-label="Thông báo">
            <span class="material-symbols-outlined">notifications</span>
            @if ($notificationCount > 0)
                <span class="absolute -top-1 -right-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                </span>
            @endif
        </button>
        <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-700 mx-2"></div>
        <a class="flex items-center gap-3 hover:opacity-80 transition-opacity" href="{{ route('admin.profile.edit') }}">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold leading-none">{{ $displayName }}</p>
                <p class="text-[11px] text-[#616f89] dark:text-gray-400 mt-1">{{ $displayRole }}</p>
            </div>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary/20" style='background-image: url("{{ $avatarUrl ?? ('https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=E2E8F0&color=1F2937') }}");'></div>
        </a>
    </div>
</header>
