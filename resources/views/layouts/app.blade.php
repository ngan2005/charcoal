<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PetJoy - Quản lý Lịch hẹn</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#FF9F1C", 
                        "primary-dark": "#e8890b",
                        "secondary": "#FFBF69",
                        "background-light": "#FFFBF7",
                        "background-dark": "#101010",
                        "surface": "#ffffff",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "2xl": "2rem", "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(255, 159, 28, 0.3)',
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: "Plus Jakarta Sans", sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        #sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.collapsed {
            width: 80px;
        }
        #sidebar.collapsed .sidebar-text {
            display: none;
        }
        #sidebar.collapsed .sidebar-logo-text {
            display: none;
        }
        #sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        body.theme-pink { background-color: #fff0f5; }
        body.theme-blue { background-color: #f0f8ff; }
        body.theme-dark { background-color: #101010; color: white; }
        body.theme-white { background-color: #f3f6f8; }
        .bounce-hover:hover {
            animation: bounce-short 0.5s ease-in-out;
        }
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }.modal-enter {
            opacity: 0;
            transform: scale(0.95);
        }
        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.3s ease-out, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-exit {
            opacity: 1;
            transform: scale(1);
        }
        .modal-exit-active {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.2s ease-in, transform 0.2s ease-in;
        }
        .backdrop-enter { opacity: 0; }
        .backdrop-enter-active { opacity: 1; transition: opacity 0.3s ease-out; }
        .backdrop-exit { opacity: 1; }
        .backdrop-exit-active { opacity: 0; transition: opacity 0.2s ease-in; }.carousel-container {
            position: relative;
            overflow: hidden;
        }
        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-slide {
            min-width: 100%;
            flex-shrink: 0;
        }
        .carousel-dot.active {
            background-color: #FF9F1C; 
            width: 2rem;
        }.view-content {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            opacity: 1;
        }
        .view-content.hidden {
            display: none;
            opacity: 0;
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
        function setTheme(theme) {
            const body = document.body;
            const html = document.documentElement;
            // Remove existing theme classes
            body.classList.remove('theme-pink', 'theme-blue', 'theme-dark', 'theme-white');
            // Handle Dark Mode specifics for Tailwind
            if (theme === 'dark') {
                html.classList.add('dark');
                body.classList.add('theme-dark');
            } else {
                html.classList.remove('dark');
                // Add specific theme class
                if (theme === 'pink') body.classList.add('theme-pink');
                else if (theme === 'blue') body.classList.add('theme-blue');
                else body.classList.add('theme-white');
            }
        }
        // Lightbox Logic
        function openModal(type, title, price, imageSrc, desc) {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const modalTitle = document.getElementById('modalTitle');
            const modalPrice = document.getElementById('modalPrice');
            const modalImage = document.getElementById('modalImage');
            const modalDesc = document.getElementById('modalDesc');
            const modalActionBtn = document.getElementById('modalActionBtn');
            modalTitle.innerText = title;
            modalPrice.innerText = price ? price : 'Liên hệ';
            modalImage.style.backgroundImage = `url('${imageSrc}')`;
            // Handle image for products (img tag vs bg image)
            if(type === 'product') {
                 modalImage.style.backgroundImage = 'none';
                 modalImage.innerHTML = `<img src="${imageSrc}" class="w-full h-full object-contain p-4" alt="${title}">`;
                 modalActionBtn.innerHTML = `<span class="material-symbols-outlined mr-2">add_shopping_cart</span> Thêm vào giỏ`;
                 modalDesc.innerText = desc || "Sản phẩm chất lượng cao dành cho thú cưng của bạn. Được làm từ nguyên liệu an toàn, bền đẹp.";
            } else {
                 modalImage.innerHTML = '';
                 modalImage.style.backgroundImage = `url('${imageSrc}')`;
                 modalActionBtn.innerHTML = `<span class="material-symbols-outlined mr-2">calendar_month</span> Đặt lịch ngay`;
                 modalDesc.innerText = desc || "Dịch vụ chăm sóc chuyên nghiệp với đội ngũ nhân viên tận tâm, giàu kinh nghiệm.";
            }
            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            // Add animation classes
            requestAnimationFrame(() => {
                modal.classList.remove('modal-enter');
                modal.classList.add('modal-enter-active');
                backdrop.classList.remove('backdrop-enter');
                backdrop.classList.add('backdrop-enter-active');
            });
        }
        function closeModal() {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            modal.classList.remove('modal-enter-active');
            modal.classList.add('modal-exit-active');
            backdrop.classList.remove('backdrop-enter-active');
            backdrop.classList.add('backdrop-exit-active');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('modal-exit-active');
                backdrop.classList.add('hidden');
                backdrop.classList.remove('backdrop-exit-active');
                // Reset to enter state for next opening
                modal.classList.add('modal-enter');
                backdrop.classList.add('backdrop-enter');
            }, 200);
        }
        // Simple Carousel Logic
        let currentSlide = 0;
        const totalSlides = 3; 
        function moveSlide(direction) {
            currentSlide += direction;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            if (currentSlide >= totalSlides) currentSlide = 0;
            updateCarousel();
        }
        function setSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        function updateCarousel() {
            const track = document.getElementById('carouselTrack');
            const dots = document.querySelectorAll('.carousel-dot');
            if(track) {
                track.style.transform = `translateX(-${currentSlide * 100}%)`;
                dots.forEach((dot, index) => {
                    if (index === currentSlide) {
                        dot.classList.add('active', 'bg-primary', 'w-8');
                        dot.classList.remove('bg-gray-300', 'w-2', 'hover:bg-gray-400');
                    } else {
                        dot.classList.remove('active', 'bg-primary', 'w-8');
                        dot.classList.add('bg-gray-300', 'w-2', 'hover:bg-gray-400');
                    }
                });
            }
        }
        // View Switching Logic
        function switchView(viewId, element) {
            // Hide all views
            const views = ['dashboard', 'services', 'store', 'appointments'];
            views.forEach(id => {
                const el = document.getElementById('view-' + id);
                if (el) el.classList.add('hidden');
            });
            // Show selected view
            const selectedView = document.getElementById('view-' + viewId);
            if (selectedView) {
                selectedView.classList.remove('hidden');
                // Animation reset (simple fade in effect)
                selectedView.style.opacity = '0';
                selectedView.style.transform = 'translateY(10px)';
                requestAnimationFrame(() => {
                    selectedView.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    selectedView.style.opacity = '1';
                    selectedView.style.transform = 'translateY(0)';
                });
            }
            // Update Sidebar Active State
            const navLinks = document.querySelectorAll('#sidebar nav a');
            navLinks.forEach(link => {
                // Remove active classes
                link.classList.remove('bg-primary/10', 'text-primary');
                // Add inactive classes
                link.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:bg-gray-50', 'dark:hover:bg-white/5', 'hover:text-primary');
            });
            // Add active classes to clicked element
            if (element) {
                element.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:bg-gray-50', 'dark:hover:bg-white/5', 'hover:text-primary');
                element.classList.add('bg-primary/10', 'text-primary');
            }
        }
    </script>
</head>
<body class="bg-[#f3f6f8] dark:bg-background-dark text-[#111813] dark:text-white h-screen overflow-hidden flex selection:bg-primary selection:text-white theme-white transition-colors duration-300">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden backdrop-enter" id="modalBackdrop" onclick="closeModal()"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden modal-enter pointer-events-none" id="detailModal">
        <div class="bg-white dark:bg-[#1e1e1e] w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row pointer-events-auto relative max-h-[90vh]">
            <button class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/50 dark:bg-black/50 backdrop-blur rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-black transition-all" onclick="closeModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="w-full md:w-1/2 h-64 md:h-auto bg-gray-100 dark:bg-white/5 relative" id="modalImageContainer">
                <div class="w-full h-full bg-cover bg-center" id="modalImage"></div>
            </div>
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="material-symbols-outlined text-sm">verified</span> Verified Choice
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2 leading-tight" id="modalTitle">Title</h2>
                    <p class="text-2xl font-bold text-primary mb-6" id="modalPrice">Price</p>
                    <div class="space-y-4 mb-8">
                        <h4 class="font-bold text-gray-900 dark:text-gray-200">Mô tả chi tiết</h4>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed" id="modalDesc">Description goes here...</p>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <span class="text-sm font-medium">Chính hãng 100%</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-blue-500">local_shipping</span>
                                <span class="text-sm font-medium">Giao nhanh 2h</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button class="flex-1 py-3.5 bg-primary text-white rounded-xl font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:translate-y-[-2px] active:translate-y-0 transition-all duration-300 flex items-center justify-center" id="modalActionBtn">
                        Action
                    </button>
                    <button class="w-14 h-14 border border-gray-200 dark:border-gray-700 rounded-xl flex items-center justify-center hover:bg-gray-50 dark:hover:bg-white/5 transition-colors text-red-500">
                        <span class="material-symbols-outlined">favorite</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.sidebar')

    <main class="flex-1 flex flex-col h-full overflow-hidden relative transition-colors duration-300">
        @include('partials.header')
        
        <div class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth">
            @yield('content')

            <footer class="mt-12 border-t border-gray-200 dark:border-gray-800 pt-8 pb-4 text-center text-gray-400 text-sm">
                <p>© 2024 PetJoy Vietnam. Modern Dashboard Layout.</p>
            </footer>
        </div>
    </main>
</body>
</html>
