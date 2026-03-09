{{-- Support Chat Box (Cat Paw Style) --}}
<div id="supportChatBox" class="fixed bottom-6 right-6 z-[100] hidden flex flex-col w-80 sm:w-96 max-h-[85vh] bg-white dark:bg-slate-900 rounded-b-[3rem] rounded-t-[4rem] shadow-2xl border-[6px] border-primary dark:border-primary/50 transform transition-all duration-300 translate-y-4 opacity-0 overflow-visible">
    {{-- Cat Paw Toes (Ngón chân mèo) --}}
    <div class="absolute -top-12 sm:-top-16 left-0 right-0 flex items-end justify-center gap-2 sm:gap-4 pointer-events-none px-4">
        <div class="toe w-12 h-14 sm:w-16 sm:h-20 bg-primary/90 dark:bg-primary/70 rounded-[50%] transform -rotate-[25deg] translate-y-4 shadow-lg border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-14 h-18 sm:w-20 sm:h-24 bg-primary dark:bg-primary/80 rounded-[50%] transform -rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-14 h-18 sm:w-20 sm:h-24 bg-primary dark:bg-primary/80 rounded-[50%] transform rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-12 h-14 sm:w-16 sm:h-20 bg-primary/90 dark:bg-primary/70 rounded-[50%] transform rotate-[25deg] translate-y-4 shadow-lg border-4 border-white dark:border-slate-900"></div>
    </div>

    {{-- Chat Header --}}
    <div class="bg-primary px-5 py-6 sm:py-8 flex items-center justify-between shadow-sm rounded-t-[3.5rem] relative overflow-hidden shrink-0">
        {{-- Decorative Paw Print --}}
        <div class="absolute -right-4 -top-4 opacity-10 transform rotate-12">
            <span class="material-symbols-outlined text-8xl text-slate-900">pets</span>
        </div>
        
        <div class="flex items-center gap-3 relative z-10">
            <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-md animate-paw">
                <span class="material-symbols-outlined text-primary text-3xl">support_agent</span>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-base leading-tight">Hỗ trợ khách hàng</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <p class="text-slate-800 text-[10px] uppercase tracking-wider font-bold">TRỰC TUYẾN</p>
                </div>
            </div>
        </div>
        <button type="button" onclick="typeof toggleSupportChat === 'function' && toggleSupportChat();" class="text-slate-800 hover:text-black transition-colors relative z-10 bg-white/20 p-2 rounded-full backdrop-blur-sm">
            <span class="material-symbols-outlined text-[20px] font-bold">close</span>
        </button>
    </div>

    {{-- Chat Messages --}}
    <div id="chatMessageContainer" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50 dark:bg-slate-800/30 no-scrollbar relative min-h-[200px]">
        <div class="flex flex-col gap-1 max-w-[85%]">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl rounded-tl-none shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">Chào bạn! Pink Charcoal có thể giúp gì cho bạn và thú cưng của mình không ạ? 🐾✨</p>
            </div>
            <span class="text-[10px] text-slate-400 ml-2 font-medium">Vừa xong</span>
        </div>
    </div>

    {{-- Chat Input --}}
    <div class="p-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-b-[2.5rem] shrink-0">
        <form id="chatForm" class="flex items-center gap-3 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-full ring-1 ring-slate-200 dark:ring-slate-700 focus-within:ring-primary transition-all">
            <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." class="flex-1 bg-transparent border-none px-4 py-2 text-sm focus:ring-0 placeholder:text-slate-400 dark:text-white">
            <button type="submit" class="h-10 w-10 bg-primary hover:bg-primary-dark text-slate-900 rounded-full flex items-center justify-center shadow-md transition-all active:scale-95 group">
                <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    window.toggleSupportChat = function() {
        var chatBox = document.getElementById('supportChatBox');
        if (!chatBox) return;
        if (chatBox.classList.contains('hidden')) {
            chatBox.classList.remove('hidden');
            void chatBox.offsetWidth;
            chatBox.classList.remove('translate-y-4', 'opacity-0');
            chatBox.classList.add('translate-y-0', 'opacity-100');
            var container = document.getElementById('chatMessageContainer');
            if (container) container.scrollTop = container.scrollHeight;
            if (typeof loadMessages === 'function') loadMessages();
        } else {
            chatBox.classList.add('translate-y-4', 'opacity-0');
            chatBox.classList.remove('translate-y-0', 'opacity-100');
            setTimeout(function() { chatBox.classList.add('hidden'); }, 300);
        }
    };

    async function loadMessages() {
        try {
            const response = await fetch('{{ route('support.messages') }}');
            if (!response.ok) return;
            const messages = await response.json();
            const container = document.getElementById('chatMessageContainer');
            if (!container) return;
            
            // Clear existing except first welcome
            const welcome = container.firstElementChild.outerHTML;
            container.innerHTML = welcome;

            messages.forEach(msg => {
                appendMessage(msg.Message, msg.IsFromAdmin, new Date(msg.created_at));
            });
            
            container.scrollTop = container.scrollHeight;
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Handle chat form submission
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chatForm');
        if (chatForm) {
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const input = document.getElementById('chatInput');
                const message = input.value.trim();
                if (message) {
                    appendMessage(message, false);
                    input.value = '';
                    
                    try {
                        const response = await fetch('{{ route('support.send') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ message: message })
                        });
                        
                        if (!response.ok) throw new Error('Failed to send');
                    } catch (error) {
                        console.error('Error sending message:', error);
                    }
                }
            });
        }
    });

    function appendMessage(text, isFromAdmin, date = new Date()) {
        const container = document.getElementById('chatMessageContainer');
        if (!container) return;
        const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const messageHtml = `
            <div class="flex flex-col gap-1 ${isFromAdmin ? 'max-w-[80%]' : 'max-w-[80%] items-end ml-auto'}">
                <div class="${isFromAdmin ? 'bg-white dark:bg-slate-800 rounded-tl-none border-slate-100 dark:border-slate-700' : 'bg-primary text-slate-900 rounded-tr-none border-transparent'} p-3 rounded-2xl shadow-sm border">
                    <p class="text-sm leading-relaxed">${text}</p>
                </div>
                <span class="text-[10px] text-slate-400 ${isFromAdmin ? 'ml-1' : 'mr-1'}">${time}</span>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', messageHtml);
        container.scrollTop = container.scrollHeight;
    }
</script>
