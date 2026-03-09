@extends('layouts.shop')

@section('title', 'Tài khoản của tôi - Pink Charcoal')

@section('content')
<div class="w-full max-w-[1000px] mx-auto min-h-[60vh] flex flex-col pt-4 pb-12 gap-6 relative">
    {{-- Decorative Background Paw --}}
    <div class="absolute -top-10 -right-10 opacity-[0.03] pointer-events-none rotate-12 hidden md:block">
        <span class="material-symbols-outlined text-[300px]">pets</span>
    </div>

    {{-- Page Header --}}
    <div class="flex items-center gap-5 border-b border-pink-100 dark:border-slate-800 pb-5">
        <div class="w-14 h-14 md:w-16 md:h-16 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0 border-2 border-white dark:border-slate-800 shadow-sm overflow-hidden text-primary">
            @php
                $headerAvatarUrl = null;
                if ($user->Avatar) {
                    $headerAvatarUrl = str_starts_with($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar);
                }
            @endphp
            @if($headerAvatarUrl)
                <img src="{{ $headerAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
            @else
                <span class="material-symbols-outlined text-3xl drop-shadow-sm">face_3</span>
            @endif
        </div>
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white font-display">Xin chào, {{ $user->FullName }}!</h1>
            <p class="text-slate-400 text-xs mt-1">Cập nhật thông tin để chúng mình phục vụ bạn tốt hơn nhé 🐾</p>
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
        <div class="w-full md:w-72 flex flex-col gap-1.5 sticky top-24">
            <button onclick="switchTab('profileBtn', 'profileTab')" id="profileBtn" class="profile-nav-btn active w-full text-left px-5 py-3 rounded-2xl font-bold flex items-center justify-between transition-all bg-primary/20 text-slate-900 border border-primary/10">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">person</span> Thông tin Cá Nhân
                </div>
                <span class="material-symbols-outlined text-sm active-paw opacity-100">pets</span>
            </button>
            <button onclick="switchTab('ordersBtn', 'ordersTab')" id="ordersBtn" class="profile-nav-btn w-full text-left px-5 py-3 rounded-2xl font-bold flex items-center justify-between transition-all text-slate-500 dark:text-slate-400 hover:bg-pink-50/50 dark:hover:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span> Lịch sử Đơn hàng
                </div>
                <span class="material-symbols-outlined text-sm active-paw opacity-0">pets</span>
            </button>
            <button onclick="switchTab('passwordBtn', 'passwordTab')" id="passwordBtn" class="profile-nav-btn w-full text-left px-5 py-3 rounded-2xl font-bold flex items-center justify-between transition-all text-slate-500 dark:text-slate-400 hover:bg-pink-50/50 dark:hover:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">lock</span> Đổi mật khẩu
                </div>
                <span class="material-symbols-outlined text-sm active-paw opacity-0">pets</span>
            </button>
            <button onclick="switchTab('supportBtn', 'supportTab')" id="supportBtn" class="profile-nav-btn w-full text-left px-5 py-3 rounded-2xl font-bold flex items-center justify-between transition-all text-slate-500 dark:text-slate-400 hover:bg-pink-50/50 dark:hover:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">support_agent</span> Hỗ trợ
                </div>
                <span class="material-symbols-outlined text-sm active-paw opacity-0">pets</span>
            </button>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-pink-50 dark:border-slate-800 pt-4">
                @csrf
                <button type="submit" class="w-full text-left px-5 py-2.5 rounded-2xl font-medium flex items-center gap-3 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-500 transition-colors text-sm">
                    <span class="material-symbols-outlined text-[20px]">logout</span> Đăng xuất
                </button>
            </form>
        </div>

        {{-- Content Area --}}
        <div class="w-full md:w-3/4">
            
            {{-- Profile Tab --}}
            <div id="profileTab" class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-6 md:p-10 shadow-xl shadow-pink-100/20 dark:shadow-none border border-white dark:border-slate-800 flex flex-col gap-6">
                <div class="flex items-center gap-2 border-b border-pink-50 dark:border-slate-800 pb-5">
                    <span class="material-symbols-outlined text-primary">account_circle</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Hồ Sơ Của Tôi</h2>
                </div>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6 max-w-lg">
                    @csrf
                    @method('PUT')
                    
                    {{-- Avatar Upload Section --}}
                    @php
                        $formAvatarUrl = null;
                        if ($user->Avatar) {
                            $formAvatarUrl = str_starts_with($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar);
                        }
                    @endphp
                    <div class="flex items-center gap-6 p-4 bg-pink-50/30 dark:bg-slate-800/30 rounded-3xl border border-white dark:border-slate-800 shadow-inner">
                        <div class="relative w-24 h-24 rounded-3xl border-4 border-white dark:border-slate-800 shadow-md overflow-hidden bg-white dark:bg-slate-800 shrink-0 group">
                            @if($formAvatarUrl)
                                <img id="avatarPreview" src="{{ $formAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div id="avatarPreviewHolder" class="w-full h-full flex items-center justify-center text-slate-200">
                                    <span class="material-symbols-outlined text-4xl">pets</span>
                                </div>
                                <img id="avatarPreview" src="" alt="Avatar" class="hidden w-full h-full object-cover">
                            @endif
                            <label for="avatarInput" class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] text-slate-900 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                                <span class="material-symbols-outlined font-bold">add_a_photo</span>
                            </label>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Ảnh Đại Diện</h3>
                            <p class="text-[10px] text-slate-400 mb-3 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full w-fit">JPG, PNG, GIF. Tối đa 5MB</p>
                            <label for="avatarInput" class="text-xs font-bold bg-primary text-slate-900 hover:shadow-lg px-4 py-2.5 rounded-2xl cursor-pointer transition-all max-w-fit flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">upload</span> Thêm hình mới
                            </label>
                            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Email / Username</label>
                            <div class="px-4 py-3 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 text-slate-400 font-medium text-sm">
                                {{ $user->Username }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Họ và Tên</label>
                            <input type="text" name="FullName" value="{{ $user->FullName }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none" required>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Số Điện Thoại</label>
                        <input type="text" name="Phone" value="{{ $user->Phone }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Địa Chỉ Giao Hàng</label>
                        <textarea name="Address" rows="2" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none resize-none">{{ $user->Address }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">
                            Cập Nhật Hồ Sơ 🐾
                        </button>
                    </div>
                </form>
            </div>

            {{-- Orders Tab --}}
            <div id="ordersTab" class="hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-6 md:p-10 shadow-xl shadow-pink-100/20 dark:shadow-none border border-white dark:border-slate-800 flex-col gap-6">
                <div class="flex items-center gap-2 border-b border-pink-50 dark:border-slate-800 pb-5">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Lịch Sử Mua Hàng</h2>
                </div>
                
                @if(count($orders) > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($orders as $order)
                            <div class="bg-white/50 dark:bg-slate-800/50 border border-slate-50 dark:border-slate-800 rounded-3xl p-5 flex flex-col gap-3 hover:border-primary/50 transition-all hover:shadow-md group">
                                <div class="flex justify-between items-center border-b border-dashed border-pink-100 dark:border-slate-700 pb-3">
                                    <div class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                                        #{{ str_pad($order->OrderID, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="text-[10px] uppercase font-bold bg-white dark:bg-slate-700 px-3 py-1 rounded-full text-slate-500 dark:text-slate-300 border border-slate-100 dark:border-slate-600">
                                        {{ $order->Status }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($order->CreatedAt)->format('d/m/Y') }}
                                    </div>
                                    <div class="font-bold text-primary text-xl">
                                        {{ number_format($order->TotalAmount, 0, ',', '.') }}<span class="text-xs ml-0.5">đ</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <button class="mt-2 text-center text-primary font-bold hover:text-primary-dark transition-colors py-2 text-sm flex items-center justify-center gap-1 group">
                            Xem tất cả đơn hàng <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-pink-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-3xl text-primary/40">shopping_basket</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Giỏ hàng đang đợi bạn</h3>
                        <p class="text-slate-400 text-sm">Bạn chưa mua món đồ nào cả. Hãy sắm cho các bé cưng nhé!</p>
                        <a href="{{ route('shop') }}" class="mt-6 bg-primary text-slate-900 font-bold py-3 px-8 rounded-2xl shadow-lg shadow-primary/20 transition-all text-sm">Đi shopping thôi 🐾</a>
                    </div>
                @endif
            </div>

            {{-- Password Tab --}}
            <div id="passwordTab" class="hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-6 md:p-10 shadow-xl shadow-pink-100/20 dark:shadow-none border border-white dark:border-slate-800 flex-col gap-6">
                <div class="flex items-center gap-2 border-b border-pink-50 dark:border-slate-800 pb-5">
                    <span class="material-symbols-outlined text-primary">key</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Đổi Mật Khẩu</h2>
                </div>
                
                <form action="{{ route('profile.password') }}" method="POST" class="flex flex-col gap-5 max-w-lg">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none" required>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none" required>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nhập lại mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm outline-none" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-slate-800 dark:bg-white text-white dark:text-slate-900 font-bold py-3.5 px-8 rounded-2xl shadow-lg hover:-translate-y-0.5 transition-all">
                            Cập Nhật Mật Khẩu 🔒
                        </button>
                    </div>
                </form>
            </div>

            {{-- Support Tab --}}
            <div id="supportTab" class="hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-6 md:p-10 shadow-xl shadow-pink-100/20 dark:shadow-none border border-white dark:border-slate-800 flex flex-col gap-6">
                <div class="flex items-center gap-2 border-b border-pink-50 dark:border-slate-800 pb-5">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Hỗ trợ khách hàng</h2>
                </div>
                
                <p class="text-slate-400 text-xs italic">Gửi tin nhắn cho chúng mình nhé. Pink Charcoal luôn lắng nghe bạn!</p>
                
                {{-- Chat Container --}}
                <div id="supportChat" class="flex flex-col gap-3 h-[350px] overflow-y-auto p-5 bg-pink-50/20 dark:bg-slate-800/20 rounded-[1.5rem] border border-pink-50 dark:border-slate-800 shadow-inner scroll-smooth">
                    <div class="text-center text-slate-300 text-sm py-12" id="noMessages">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <span class="material-symbols-outlined text-primary/30">forum</span>
                        </div>
                        <p class="font-bold">Đừng ngần ngại đặt câu hỏi nha!</p>
                    </div>
                </div>

                {{-- Send Message Form --}}
                <form id="supportForm" class="flex gap-2">
                    @csrf
                    <input type="text" id="supportMessage" name="message" placeholder="Nhập lời nhắn yêu thương..." 
                        class="flex-1 px-5 py-3 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all outline-none"
                        required>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold px-5 py-3 rounded-2xl shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all flex items-center justify-center">
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
            btn.classList.remove('bg-primary/20', 'text-slate-900', 'border-primary/10');
            btn.classList.add('text-slate-500', 'dark:text-slate-400', 'hover:bg-pink-50/50', 'dark:hover:bg-slate-800/50');
            btn.querySelector('.active-paw').classList.add('opacity-0');
            btn.querySelector('.active-paw').classList.remove('opacity-100');
        });

        // Activate selected tab & button
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('flex');
        
        const activeBtn = document.getElementById(btnId);
        activeBtn.classList.add('bg-primary/20', 'text-slate-900', 'border-primary/10');
        activeBtn.classList.remove('text-slate-500', 'dark:text-slate-400', 'hover:bg-pink-50/50', 'dark:hover:bg-slate-800/50');
        activeBtn.querySelector('.active-paw').classList.remove('opacity-0');
        activeBtn.querySelector('.active-paw').classList.add('opacity-100');
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
