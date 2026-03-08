@extends('layouts.shop')

@section('title', 'Tài khoản của tôi - Pink Charcoal')

@section('content')
<div class="w-full max-w-[1200px] mx-auto min-h-[60vh] flex flex-col pt-4 pb-12 gap-8">
    
    {{-- Page Header --}}
    <div class="flex items-center gap-4 border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-primary/20 rounded-full flex items-center justify-center shrink-0 border-4 border-white dark:border-slate-800 shadow-sm overflow-hidden text-primary">
            @php
                $headerAvatarUrl = null;
                if ($user->Avatar) {
                    $headerAvatarUrl = str_starts_with($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar);
                }
            @endphp
            @if($headerAvatarUrl)
                <img src="{{ $headerAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
            @else
                <span class="material-symbols-outlined text-4xl drop-shadow-sm">face_3</span>
            @endif
        </div>
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white font-display">Xin chào, {{ $user->FullName }}!</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý thông tin cá nhân và lịch sử dịch vụ, đơn hàng của bạn.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-xl border border-red-100 dark:border-red-800 flex flex-col gap-2">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">error</span> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        {{-- Sidebar Menu --}}
        <div class="w-full md:w-1/4 flex flex-col gap-2 sticky top-24">
            <button onclick="switchTab('profileBtn', 'profileTab')" id="profileBtn" class="w-full text-left px-5 py-4 rounded-xl font-bold flex items-center gap-3 transition-colors bg-primary text-slate-900 shadow-sm border border-transparent">
                <span class="material-symbols-outlined">person</span> Thông tin Cá Nhân
            </button>
            <button onclick="switchTab('ordersBtn', 'ordersTab')" id="ordersBtn" class="w-full text-left px-5 py-4 rounded-xl font-bold flex items-center gap-3 transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                <span class="material-symbols-outlined">receipt_long</span> Lịch sử Đơn hàng
            </button>
            <button onclick="switchTab('passwordBtn', 'passwordTab')" id="passwordBtn" class="w-full text-left px-5 py-4 rounded-xl font-bold flex items-center gap-3 transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                <span class="material-symbols-outlined">lock</span> Đổi mật khẩu
            </button>
            <button onclick="switchTab('supportBtn', 'supportTab')" id="supportBtn" class="w-full text-left px-5 py-4 rounded-xl font-bold flex items-center gap-3 transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                <span class="material-symbols-outlined">support_agent</span> Hỗ trợ
            </button>
            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-slate-200 dark:border-slate-800 pt-4">
                @csrf
                <button type="submit" class="w-full text-left px-5 py-3 rounded-xl font-medium flex items-center gap-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition-colors">
                    <span class="material-symbols-outlined">logout</span> Đăng xuất
                </button>
            </form>
        </div>

        {{-- Content Area --}}
        <div class="w-full md:w-3/4">
            
            {{-- Profile Tab --}}
            <div id="profileTab" class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col gap-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-4">Hồ Sơ Của Tôi</h2>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 max-w-lg">
                    @csrf
                    @method('PUT')
                    
                    {{-- Avatar Upload Section --}}
                    @php
                        $formAvatarUrl = null;
                        if ($user->Avatar) {
                            $formAvatarUrl = str_starts_with($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar);
                        }
                    @endphp
                    <div class="flex items-center gap-6 mb-2">
                        <div class="relative w-24 h-24 rounded-full border-4 border-white dark:border-slate-800 shadow-sm overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0 group">
                            @if($formAvatarUrl)
                                <img id="avatarPreview" src="{{ $formAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div id="avatarPreviewHolder" class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                    <span class="material-symbols-outlined text-4xl">person</span>
                                </div>
                                <img id="avatarPreview" src="" alt="Avatar" class="hidden w-full h-full object-cover">
                            @endif
                            <label for="avatarInput" class="absolute inset-0 bg-slate-900/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <span class="material-symbols-outlined">photo_camera</span>
                            </label>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-bold text-slate-900 dark:text-white">Ảnh Đại Diện</h3>
                            <p class="text-xs text-slate-500 mb-2">Định dạng JPG, PNG, GIF. Tối đa 5MB</p>
                            <label for="avatarInput" class="text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 px-4 py-2 rounded-full cursor-pointer transition-colors max-w-fit flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">upload</span> Chọn ảnh mới
                            </label>
                            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tên Đăng Nhập / Email</label>
                        <input type="text" value="{{ $user->Username }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-500 focus:outline-none" disabled>
                        <p class="text-xs text-slate-400 mt-1">Tên đăng nhập không thể thay đổi.</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Họ và Tên</label>
                        <input type="text" name="FullName" value="{{ $user->FullName }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Số Điện Thoại</label>
                        <input type="text" name="Phone" value="{{ $user->Phone }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Địa Chỉ Giao Hàng</label>
                        <textarea name="Address" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none">{{ $user->Address }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3 px-8 rounded-xl shadow-md hover:-translate-y-0.5 transition-all">
                            Lưu Thông Tin
                        </button>
                    </div>
                </form>
            </div>

            {{-- Orders Tab --}}
            <div id="ordersTab" class="hidden bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 dark:border-slate-800 flex-col gap-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-4">Lịch Sử Mua Hàng</h2>
                
                @if(count($orders) > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($orders as $order)
                            <div class="border border-slate-100 dark:border-slate-800 rounded-2xl p-5 flex flex-col gap-4 hover:border-primary/50 transition-colors">
                                <div class="flex justify-between items-center border-b border-dashed border-slate-200 dark:border-slate-700 pb-3">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        Mã Đơn: #{{ str_pad($order->OrderID, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="text-xs bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full text-slate-600 dark:text-slate-300 font-medium tracking-wide">
                                        {{ $order->Status }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-slate-500">
                                        {{ \Carbon\Carbon::parse($order->CreatedAt)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="font-extrabold text-primary text-lg">
                                        {{ number_format($order->TotalAmount, 0, ',', '.') }}đ
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <button class="mt-2 text-center text-primary font-bold hover:text-primary-dark transition-colors py-2 text-sm">Xem tất cả đơn hàng</button>
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-slate-700 mb-4">receipt_long</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Chưa có đơn hàng nào</h3>
                        <p class="text-slate-500">Bạn chưa thực hiện giao dịch nào. Hãy khám phá thẻ Cửa hàng nhé!</p>
                        <a href="{{ route('shop') }}" class="mt-6 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold py-2.5 px-6 rounded-full transition-colors text-sm">Đi đến Cửa Hàng</a>
                    </div>
                @endif
            </div>

            {{-- Password Tab --}}
            <div id="passwordTab" class="hidden bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 dark:border-slate-800 flex-col gap-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-4">Đổi Mật Khẩu</h2>
                
                <form action="{{ route('profile.password') }}" method="POST" class="flex flex-col gap-5 max-w-lg">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nhập lại mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 font-bold py-3 px-8 rounded-xl shadow-md transition-all">
                            Cập Nhật Mật Khẩu
                        </button>
                    </div>
                </form>
            </div>

            {{-- Support Tab --}}
            <div id="supportTab" class="hidden bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col gap-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">support_agent</span> Hỗ trợ khách hàng
                </h2>
                
                <p class="text-slate-500 text-sm">Gửi tin nhắn cho nhân viên cửa hàng. Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
                
                {{-- Chat Container --}}
                <div id="supportChat" class="flex flex-col gap-3 h-[400px] overflow-y-auto p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="text-center text-slate-400 text-sm py-8" id="noMessages">
                        <span class="material-symbols-outlined text-4xl mb-2">chat</span>
                        <p>Chưa có tin nhắn nào. Hãy gửi tin nhắn đầu tiên!</p>
                    </div>
                </div>

                {{-- Send Message Form --}}
                <form id="supportForm" class="flex gap-3">
                    @csrf
                    <input type="text" id="supportMessage" name="message" placeholder="Nhập tin nhắn..." 
                        class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        required>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold px-6 py-3 rounded-xl shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(btnId, tabId) {
        // Reset all tabs
        document.getElementById('profileTab').classList.add('hidden');
        document.getElementById('profileTab').classList.remove('flex');
        
        document.getElementById('ordersTab').classList.add('hidden');
        document.getElementById('ordersTab').classList.remove('flex');
        
        document.getElementById('passwordTab').classList.add('hidden');
        document.getElementById('passwordTab').classList.remove('flex');

        document.getElementById('supportTab').classList.add('hidden');
        document.getElementById('supportTab').classList.remove('flex');

        // Reset all buttons style
        const buttons = ['profileBtn', 'ordersBtn', 'passwordBtn', 'supportBtn'];
        buttons.forEach(id => {
            const btn = document.getElementById(id);
            btn.classList.remove('bg-primary', 'text-slate-900', 'shadow-sm');
            btn.classList.add('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-50', 'dark:hover:bg-slate-800/50', 'hover:border-slate-200', 'dark:hover:border-slate-700');
        });

        // Activate selected tab & button
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('flex');
        
        const activeBtn = document.getElementById(btnId);
        activeBtn.classList.add('bg-primary', 'text-slate-900', 'shadow-sm');
        activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-50', 'dark:hover:bg-slate-800/50', 'hover:border-slate-200', 'dark:hover:border-slate-700');
    }

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('avatarPreview');
            var holder = document.getElementById('avatarPreviewHolder');
            output.src = reader.result;
            output.classList.remove('hidden');
            if (holder) holder.classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Support Chat Functions
    function loadSupportMessages() {
        fetch('{{ route("support.messages") }}')
            .then(response => response.json())
            .then(data => {
                const chatContainer = document.getElementById('supportChat');
                const noMessages = document.getElementById('noMessages');
                
                if (data.length > 0) {
                    noMessages.classList.add('hidden');
                    chatContainer.innerHTML = data.map(msg => `
                        <div class="flex ${msg.IsFromAdmin ? 'justify-start' : 'justify-end'}">
                            <div class="max-w-[80%] px-4 py-2 rounded-2xl ${msg.IsFromAdmin 
                                ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white' 
                                : 'bg-primary text-slate-900'}">
                                <p class="text-sm">${msg.Message}</p>
                                <p class="text-xs ${msg.IsFromAdmin ? 'text-slate-400' : 'text-slate-600'} mt-1">
                                    ${new Date(msg.created_at).toLocaleString('vi-VN')}
                                </p>
                            </div>
                        </div>
                    `).join('');
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                } else {
                    chatContainer.innerHTML = '';
                    chatContainer.appendChild(noMessages);
                    noMessages.classList.remove('hidden');
                }
            });
    }

    // Send message
    document.getElementById('supportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const messageInput = document.getElementById('supportMessage');
        const message = messageInput.value.trim();
        
        if (!message) return;

        fetch('{{ route("support.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: 'message=' + encodeURIComponent(message)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadSupportMessages();
            }
        });
    });

    // Load messages when support tab is opened
    const originalSwitchTab = switchTab;
    window.switchTab = function(btnId, tabId) {
        originalSwitchTab(btnId, tabId);
        if (tabId === 'supportTab') {
            loadSupportMessages();
            // Auto refresh every 10 seconds
            if (!window.supportInterval) {
                window.supportInterval = setInterval(loadSupportMessages, 10000);
            }
        }
    };
</script>
@endpush
@endsection
