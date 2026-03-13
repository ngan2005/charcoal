<header class="sticky top-0 z-20 bg-white/80 dark:bg-[#111827]/80 backdrop-blur-md border-b border-[#f3e8ea] dark:border-gray-800 px-8 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
        <h2 class="text-xl font-bold tracking-tight text-gray-800 dark:text-white">@yield('header_title', 'Bảng điều khiển')</h2>
    </div>
    
    <div class="flex items-center gap-4">
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
