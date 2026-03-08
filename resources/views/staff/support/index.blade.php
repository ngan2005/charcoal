@extends('layouts.staff')

@section('title', 'Hỗ trợ khách hàng - Pink Charcoal')

@push('styles')
<style>
    .chat-message {
        max-width: 70%;
    }
    .chat-customer {
        background: #f1f5f9;
    }
    .chat-staff {
        background: #dcfce7;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <div class="flex h-[calc(100vh-180px)]">
        {{-- Conversation List --}}
        <div class="w-1/3 border-r border-gray-200 dark:border-gray-700 flex flex-col bg-white dark:bg-gray-800 rounded-l-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-rose-500 to-pink-500">
                <h2 class="text-lg font-bold text-white">Tin nhắn hỗ trợ</h2>
            </div>
            <div id="conversationList" class="flex-1 overflow-y-auto">
                <div class="p-4 text-center text-gray-500">Đang tải...</div>
            </div>
        </div>

        {{-- Chat Area --}}
        <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-gray-900 rounded-r-2xl">
            {{-- Chat Header --}}
            <div id="chatHeader" class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hidden">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div>
                        <h3 id="chatUserName" class="font-bold text-gray-900 dark:text-white">--</h3>
                        <p id="chatUserEmail" class="text-sm text-gray-500">--</p>
                    </div>
                </div>
            </div>

            {{-- No Conversation Selected --}}
            <div id="noConversation" class="flex-1 flex items-center justify-center">
                <div class="text-center text-gray-500">
                    <span class="material-symbols-outlined text-6xl mb-2 text-gray-300">chat</span>
                    <p>Chọn một cuộc trò chuyện để xem</p>
                </div>
            </div>

            {{-- Messages Area --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3 hidden">
            </div>

            {{-- Reply Form --}}
            <div id="replyForm" class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hidden">
                <form id="supportReplyForm" class="flex gap-3">
                    @csrf
                    <input type="hidden" id="replyUserId" name="user_id">
                    <input type="text" id="replyMessage" name="message" placeholder="Nhập tin nhắn trả lời..." 
                        class="flex-1 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                    <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">send</span> Gửi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentUserId = null;
let refreshInterval = null;

// Load conversations
function loadConversations() {
    fetch('{{ route("staff.support.conversations") }}')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('conversationList');
            if (data.length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-gray-500">Chưa có tin nhắn nào</div>';
                return;
            }
            
            list.innerHTML = data.map(conv => {
                const unreadBadge = conv.unread_count > 0 
                    ? `<span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">${conv.unread_count}</span>` 
                    : '';
                const name = conv.FullName || 'Khách vãng lai';
                const email = conv.Email || 'Chưa đăng nhập';
                const time = new Date(conv.last_message_time).toLocaleString('vi-VN');
                
                return `
                    <div onclick="selectConversation(${conv.UserID}, '${name.replace(/'/g, "\\'")}', '${email}')" 
                        class="p-4 border-b border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-gray-700 ${currentUserId === conv.UserID ? 'bg-rose-100 dark:bg-gray-700' : ''}"
                        id="conv-${conv.UserID}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-gray-900 dark:text-white">${name}</span>
                            ${unreadBadge}
                        </div>
                        <p class="text-sm text-gray-500 truncate">${email}</p>
                        <p class="text-xs text-gray-400 mt-1">${time}</p>
                    </div>
                `;
            }).join('');
        });
}

// Select conversation
function selectConversation(userId, name, email) {
    currentUserId = userId;
    
    // Update UI
    document.getElementById('noConversation').classList.add('hidden');
    document.getElementById('chatHeader').classList.remove('hidden');
    document.getElementById('chatMessages').classList.remove('hidden');
    document.getElementById('replyForm').classList.remove('hidden');
    document.getElementById('chatUserName').textContent = name;
    document.getElementById('chatUserEmail').textContent = email;
    document.getElementById('replyUserId').value = userId;
    
    // Highlight selected
    document.querySelectorAll('[id^="conv-"]').forEach(el => el.classList.remove('bg-rose-100', 'dark:bg-gray-700'));
    document.getElementById('conv-' + userId)?.classList.add('bg-rose-100', 'dark:bg-gray-700');
    
    // Load messages
    loadMessages(userId);
    
    // Auto refresh
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(() => loadMessages(userId), 5000);
}

// Load messages for user
function loadMessages(userId) {
    fetch(`/staff/support/user/${userId}/messages`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('chatMessages');
            container.innerHTML = data.map(msg => {
                const isFromAdmin = msg.IsFromAdmin;
                const align = isFromAdmin ? 'justify-end' : 'justify-start';
                const bgClass = isFromAdmin ? 'chat-staff' : 'chat-customer';
                const time = new Date(msg.created_at).toLocaleString('vi-VN');
                
                return `
                    <div class="flex ${align}">
                        <div class="chat-message px-4 py-2 rounded-2xl ${bgClass}">
                            <p class="text-sm text-gray-900 dark:text-gray-800">${msg.Message}</p>
                            <p class="text-xs text-gray-500 mt-1">${time}</p>
                        </div>
                    </div>
                `;
            }).join('');
            container.scrollTop = container.scrollHeight;
        });
}

// Send reply
document.getElementById('supportReplyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('replyUserId').value;
    const message = document.getElementById('replyMessage').value.trim();
    
    if (!message || !userId) return;
    
    fetch('{{ route("staff.support.reply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'user_id=' + userId + '&message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('replyMessage').value = '';
            loadMessages(userId);
            loadConversations(); // Refresh unread count
        }
    });
});

// Initial load
loadConversations();
</script>
@endpush
@endsection
