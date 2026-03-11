<header class="sticky top-0 z-10 bg-white/80 dark:bg-[#1a202c]/80 backdrop-blur-md border-b border-[#f0f2f4] dark:border-gray-700 px-8 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
        <h2 class="text-xl font-bold tracking-tight">Bảng điều khiển</h2>
    </div>
    <div class="flex items-center gap-4">
        @php
            $currentUser = auth()->user();
            $displayName = $currentUser?->FullName ?? 'Người dùng';
            $displayRole = $currentUser?->role?->RoleName ?? 'Tài khoản';
            
            // Xử lý avatar URL - chuyển đổi URL về dạng chuẩn
            $avatarPath = $currentUser?->Avatar;
            if ($avatarPath) {
                // Nếu là URL đầy đủ (http/https)
                if (preg_match('/^https?:\/\//', $avatarPath)) {
                    // Chuyển đổi URL về dạng asset() nếu cần thiết
                    $parsedUrl = parse_url($avatarPath);
                    $path = $parsedUrl['path'] ?? '';
                    
                    // Nếu path bắt đầu bằng /storage/ hoặc storage/
                    if (str_contains($path, '/storage/')) {
                        $relativePath = ltrim(str_replace('/storage/', 'storage/', $path), '/');
                        $avatarUrl = asset($relativePath);
                    } else {
                        // Sử dụng URL gốc nếu không phải storage path
                        $avatarUrl = $avatarPath;
                    }
                } 
                // Nếu bắt đầu bằng /storage hoặc storage/
                elseif (str_starts_with($avatarPath, '/storage') || str_starts_with($avatarPath, 'storage/')) {
                    $avatarUrl = asset(ltrim($avatarPath, '/'));
                }
                // Các trường hợp khác
                else {
                    $avatarUrl = asset('storage/' . ltrim($avatarPath, '/'));
                }
            } else {
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=E2E8F0&color=1F2937&size=128';
            }
            
            @endphp
        <a class="flex items-center gap-3 hover:opacity-80 transition-opacity" href="{{ route('admin.profile.edit') }}">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold leading-none">{{ $displayName }}</p>
                <p class="text-[11px] text-[#616f89] dark:text-gray-400 mt-1">{{ $displayRole }}</p>
            </div>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary/20" style='background-image: url("{{ $avatarUrl }}");'></div>
        </a>
    </div>
</header>
