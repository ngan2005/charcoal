<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Pink Charcoal - Cửa Hàng Thú Cưng</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo-pink-charcoal.png') }}">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#F4C2C3",
                    "primary-dark": "#e0a9aa",
                    "background-light": "#f8f7f6",
                    "background-dark": "#221910",
                },
                fontFamily: {
                    "display": ["Be Vietnam Pro", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
            },
        },
    }
</script>
<style>
    body { font-family: 'Be Vietnam Pro', sans-serif; }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        line-height: 1;
        text-transform: none;
        letter-spacing: normal;
        word-wrap: normal;
        white-space: nowrap;
        direction: ltr;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }

    /* Override Bootstrap Blue with Pink Charcoal */
    :root {
        --bs-primary: #F4C2C3;
        --bs-primary-rgb: 244, 194, 195;
        --bs-link-color: #F4C2C3;
        --bs-link-hover-color: #e0a9aa;
    }
    .btn-primary {
        --bs-btn-bg: #F4C2C3;
        --bs-btn-border-color: #F4C2C3;
        --bs-btn-hover-bg: #e0a9aa;
        --bs-btn-hover-border-color: #e0a9aa;
        --bs-btn-active-bg: #e0a9aa;
        --bs-btn-active-border-color: #e0a9aa;
        color: #1e293b !important; /* slate-900 */
    }
    .text-primary { color: #F4C2C3 !important; }
    .bg-primary { background-color: #F4C2C3 !important; }

    /* Fix Dropdown visibility conflict */
    .nav-item-holder {
        position: relative !important;
        /* Giữ vùng hover liền mạch từ nút xuống menu (tránh menu tắt khi rê chuột qua khe hở) */
        padding-bottom: 0.5rem;
        margin-bottom: -0.5rem;
    }
    .nav-item-dropdown {
        display: none !important;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        margin-top: 0 !important;
        padding-top: 0.25rem !important;
        z-index: 9999 !important;
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.15s ease, visibility 0.15s ease !important;
    }
    /* Cầu nối vô hình: vùng này vẫn thuộc menu → :hover không bị mất khi di chuột xuống */
    .nav-item-dropdown::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: -10px;
        height: 10px;
    }
    .dark .nav-item-dropdown {
        background: rgb(15 23 42) !important;
        border-color: rgb(51 65 85) !important;
    }
    .nav-item-holder:hover > .nav-item-dropdown {
        display: flex !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Cat Paw Chat Box Animations */
    @keyframes paw-bounce {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }
    .animate-paw { animation: paw-bounce 3s ease-in-out infinite; }

    /* Global Site Background */
    body {
        background-image: url('{{ asset('images/site-background.png') }}');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    /* Ensure content visibility over background */
    .bg-background-light { background-color: rgba(255, 255, 255, 0.4) !important; }
    .dark .bg-background-dark { background-color: rgba(15, 23, 42, 0.6) !important; }
</style>
@stack('styles')
</head>
<body x-data="{ showSearch: false }" class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display min-h-screen flex flex-col">

@include('partials.header')

{{-- Page Content --}}
<main class="flex-1 max-w-[1440px] w-full mx-auto px-10 py-8 flex flex-col gap-8">
    @yield('content')
</main>

@include('partials.footer')

@include('partials.about-modal')

{{-- Lightbox Modal --}}
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pt-20 sm:pt-24" onclick="closeLightbox(event)">
    <div class="relative max-w-2xl w-full flex flex-col items-center justify-center transform scale-95 transition-transform duration-300" id="lightboxContent">
        <button type="button" class="absolute -top-16 sm:-top-20 right-0 sm:right-4 text-white hover:text-white bg-primary/80 hover:bg-primary shadow-lg rounded-full p-2 transition-all flex items-center justify-center z-40" onclick="closeLightbox(event, true)">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
        
        <div class="relative">
            {{-- Paw Toes Decor (Ngón chân mèo) --}}
            <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform -rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
            </div>
            
            <img id="lightboxImage" src="" alt="Zoomed Image" class="relative z-10 max-h-[55vh] w-auto object-contain rounded-b-[2rem] rounded-t-[3rem] sm:rounded-t-[4rem] shadow-2xl border-[6px] border-primary bg-white" onclick="event.stopPropagation()">
        </div>
        
        <div class="w-full text-center mt-6">
            <h3 id="lightboxTitle" class="text-white text-2xl font-bold font-display tracking-wide drop-shadow-md"></h3>
            <p id="lightboxPrice" class="text-primary font-bold text-xl mt-1 drop-shadow-md"></p>
        </div>
    </div>
</div>

<script>
    function openLightbox(src, title = '', price = '') {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        const titleEl = document.getElementById('lightboxTitle');
        const priceEl = document.getElementById('lightboxPrice');
        const content = document.getElementById('lightboxContent');
        
        img.src = src;
        titleEl.textContent = title;
        priceEl.textContent = price;
        
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e, force = false) {
        if (!force && e.target.id !== 'lightboxModal') return;
        
        const modal = document.getElementById('lightboxModal');
        const content = document.getElementById('lightboxContent');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('lightboxImage').src = '';
        }, 300);
    }

    window.toggleSupportChat = function() {
        const chatBox = document.getElementById('supportChatBox');
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
</script>

@stack('scripts')

@include('partials.search-overlay')
@include('partials.support-chat')
</body>
</html>
