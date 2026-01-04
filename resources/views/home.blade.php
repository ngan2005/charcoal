<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetParadise | Chăm sóc thú cưng chuẩn 5 sao</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soft-pink': '#FFB6C1',
                        'misty-rose': '#FFE4E1',
                        'accent-peach': '#FFD1DC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        brand: ['Quicksand', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-pink: #FFB6C1;
            --light-pink: #FFE4E1;
        }
        body {
            background-color: #fffafb;
            scroll-behavior: smooth;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 182, 193, 0.2);
        }
        .sidebar-fixed {
            height: calc(100vh - 80px);
            top: 90px;
        }
        .custom-carousel-img {
            height: 600px;
            object-fit: cover;
        }
        .nav-link-custom {
            position: relative;
            transition: color 0.3s;
        }
        .nav-link-custom::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-pink);
            transition: width 0.3s;
        }
        .nav-link-custom:hover::after {
            width: 100%;
        }
        .card-service {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-service:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(255, 182, 193, 0.3);
        }
    </style>
</head>
<body class="font-sans text-gray-800">

    <header class="sticky top-0 z-[1050] glass-effect shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="#" class="font-brand text-2xl font-bold text-soft-pink flex items-center gap-2 no-underline">
                <i class="fa-solid fa-paw text-3xl"></i>
                <span class="tracking-tight text-gray-700">Pet<span class="text-soft-pink">Paradise</span></span>
            </a>

            <div class="hidden md:flex flex-1 max-w-md mx-10">
                <div class="relative w-full">
                    <input type="text" class="w-full bg-misty-rose/30 border-none rounded-full py-2 px-5 focus:ring-2 focus:ring-soft-pink transition-all outline-none text-sm" placeholder="Tìm kiếm dịch vụ, thức ăn cho bé...">
                    <button class="absolute right-3 top-2 text-gray-400 hover:text-soft-pink"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button class="relative text-gray-600 hover:text-soft-pink transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span class="absolute -top-2 -right-2 bg-soft-pink text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </button>
                <a href="#" class="hidden sm:block text-sm font-semibold text-gray-600 hover:text-soft-pink no-underline transition-colors">Đăng nhập</a>
                <a href="#" class="bg-soft-pink text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg shadow-pink-200 hover:bg-rose-300 transition-all no-underline">Gia nhập</a>
                <button class="md:hidden text-gray-700" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"><i class="fa-solid fa-bars-staggered text-2xl"></i></button>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b border-pink-50 hidden md:block">
        <div class="container mx-auto px-4 py-2">
            <ul class="flex justify-center gap-10 list-none m-0 p-0 text-sm font-semibold uppercase tracking-widest text-gray-500">
                <li><a href="#" class="nav-link-custom no-underline py-2 text-soft-pink">Trang chủ</a></li>
                <li><a href="#" class="nav-link-custom no-underline py-2">Cửa hàng</a></li>
                <li><a href="#" class="nav-link-custom no-underline py-2">Spa & Grooming</a></li>
                <li><a href="#" class="nav-link-custom no-underline py-2">Khách sạn</a></li>
                <li><a href="#" class="nav-link-custom no-underline py-2">Cộng đồng</a></li>
                <li><a href="#" class="nav-link-custom no-underline py-2">Liên hệ</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="hidden lg:block w-72 shrink-0">
                <div class="sidebar-fixed sticky glass-effect rounded-3xl p-6 shadow-sm overflow-y-auto">
                    <h5 class="font-brand font-bold text-lg mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-soft-pink"></i> Danh mục
                    </h5>
                    <ul class="space-y-4 list-none p-0">
                        <li><a href="#" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-misty-rose transition-all no-underline text-gray-600 font-medium group">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center group-hover:bg-white"><i class="fa-solid fa-dog text-soft-pink"></i></span>
                            Cún cưng
                        </a></li>
                        <li><a href="#" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-misty-rose transition-all no-underline text-gray-600 font-medium group">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center group-hover:bg-white"><i class="fa-solid fa-cat text-soft-pink"></i></span>
                            Mèo cảnh
                        </a></li>
                        <li><a href="#" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-misty-rose transition-all no-underline text-gray-600 font-medium group">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center group-hover:bg-white"><i class="fa-solid fa-bowl-food text-soft-pink"></i></span>
                            Thức ăn dinh dưỡng
                        </a></li>
                        <li><a href="#" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-misty-rose transition-all no-underline text-gray-600 font-medium group">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center group-hover:bg-white"><i class="fa-solid fa-briefcase-medical text-soft-pink"></i></span>
                            Y tế & Thuốc
                        </a></li>
                        <li><a href="#" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-misty-rose transition-all no-underline text-gray-600 font-medium group">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center group-hover:bg-white"><i class="fa-solid fa-shirt text-soft-pink"></i></span>
                            Thời trang & Phụ kiện
                        </a></li>
                    </ul>

                    <div class="mt-10 p-4 rounded-3xl bg-gradient-to-br from-soft-pink to-rose-300 text-white relative overflow-hidden shadow-lg">
                        <i class="fa-solid fa-gift absolute -right-4 -bottom-4 text-6xl opacity-20 rotate-12"></i>
                        <p class="text-xs uppercase font-bold opacity-80 mb-1">Thành viên mới</p>
                        <h6 class="font-bold text-lg mb-2 leading-tight">Giảm ngay 15% đơn đầu tiên</h6>
                        <button class="bg-white text-soft-pink px-4 py-1.5 rounded-full text-xs font-bold hover:scale-105 transition-transform">Lấy mã ngay</button>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <section class="mb-12">
                    <div id="heroCarousel" class="carousel slide carousel-fade rounded-[2.5rem] overflow-hidden shadow-2xl" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active rounded-full !w-3 !h-3"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="rounded-full !w-3 !h-3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="5000">
                                <img src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?q=80&w=2071&auto=format&fit=crop" class="d-block w-100 custom-carousel-img" alt="Pet Spa">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-10 lg:p-20">
                                    <div class="text-white max-w-xl">
                                        <span class="inline-block bg-soft-pink/90 backdrop-blur px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Mùa hè rực rỡ</span>
                                        <h1 class="text-4xl lg:text-6xl font-brand font-bold mb-6 leading-tight">Spa trọn gói chỉ từ 199k</h1>
                                        <p class="text-lg mb-8 opacity-90 font-light">Dịch vụ chăm sóc lông và móng chuyên nghiệp cho bé yêu của bạn.</p>
                                        <div class="flex gap-4">
                                            <button class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-soft-pink hover:text-white transition-all">Đặt lịch ngay</button>
                                            <button class="border-2 border-white/50 text-white px-8 py-3 rounded-full font-bold hover:bg-white/20 transition-all">Xem bảng giá</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <img src="https://images.unsplash.com/photo-1548191265-cc70d3d45ba1?q=80&w=2070&auto=format&fit=crop" class="d-block w-100 custom-carousel-img" alt="Hotel">
                                <div class="absolute inset-0 bg-gradient-to-r from-soft-pink/40 to-transparent flex items-center p-10 lg:p-20">
                                    <div class="text-white max-w-lg">
                                        <h2 class="text-4xl lg:text-6xl font-brand font-bold mb-6 leading-tight text-gray-800">Pet Hotel <br><span class="text-white drop-shadow-md">Luxury Stay</span></h2>
                                        <p class="text-lg mb-8 text-gray-700">Không gian nghỉ dưỡng cao cấp, camera 24/7 giúp chủ nhân an tâm tuyệt đối.</p>
                                        <button class="bg-soft-pink text-white px-8 py-3 rounded-full font-bold shadow-xl shadow-pink-200">Khám phá phòng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-16">
                    <div class="flex items-end justify-between mb-8">
                        <div>
                            <h2 class="text-3xl font-brand font-bold text-gray-800">Dịch vụ nổi bật</h2>
                            <p class="text-gray-500 mt-2">Được thực hiện bởi các chuyên gia giàu kinh nghiệm</p>
                        </div>
                        <a href="#" class="text-soft-pink font-bold no-underline hover:mr-2 transition-all">Xem tất cả <i class="fa-solid fa-arrow-right-long ml-2"></i></a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        <div class="card-service group relative glass-effect rounded-[2rem] overflow-hidden p-3 shadow-sm">
                            <div class="relative h-64 rounded-[1.5rem] overflow-hidden mb-4">
                                <img src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?q=80&w=2069&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Grooming">
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase text-soft-pink tracking-wider">Phổ biến nhất</div>
                            </div>
                            <div class="px-3 pb-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">Cắt tỉa tạo kiểu</h3>
                                    <span class="text-soft-pink font-bold">250k+</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-2 italic">Kiến tạo diện mạo hoàn hảo cho các bé từ Poodle đến Corgi với các stylist chuyên nghiệp.</p>
                                <button class="w-full border-2 border-misty-rose text-soft-pink group-hover:bg-soft-pink group-hover:text-white group-hover:border-soft-pink py-2 rounded-2xl font-bold transition-all">Đăng ký tư vấn</button>
                            </div>
                        </div>

                        <div class="card-service group relative glass-effect rounded-[2rem] overflow-hidden p-3 shadow-sm">
                            <div class="relative h-64 rounded-[1.5rem] overflow-hidden mb-4">
                                <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?q=80&w=1964&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Health">
                            </div>
                            <div class="px-3 pb-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">Kiểm tra sức khỏe</h3>
                                    <span class="text-soft-pink font-bold">300k+</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-2 italic">Gói khám tổng quát định kỳ, tiêm chủng và tư vấn dinh dưỡng khoa học.</p>
                                <button class="w-full border-2 border-misty-rose text-soft-pink group-hover:bg-soft-pink group-hover:text-white group-hover:border-soft-pink py-2 rounded-2xl font-bold transition-all">Đăng ký tư vấn</button>
                            </div>
                        </div>

                        <div class="card-service group relative glass-effect rounded-[2rem] overflow-hidden p-3 shadow-sm">
                            <div class="relative h-64 rounded-[1.5rem] overflow-hidden mb-4">
                                <img src="https://images.unsplash.com/photo-1623387641168-d9803ddd3f35?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Training">
                            </div>
                            <div class="px-3 pb-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">Huấn luyện cơ bản</h3>
                                    <span class="text-soft-pink font-bold">500k+</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-2 italic">Khóa học 7 ngày giúp bé nghe lời, đi vệ sinh đúng chỗ và các kỹ năng xã hội cơ bản.</p>
                                <button class="w-full border-2 border-misty-rose text-soft-pink group-hover:bg-soft-pink group-hover:text-white group-hover:border-soft-pink py-2 rounded-2xl font-bold transition-all">Đăng ký tư vấn</button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-16 bg-white rounded-[3rem] p-10 shadow-sm border border-pink-50 flex flex-wrap justify-around gap-8 text-center">
                    <div class="flex flex-col items-center">
                        <span class="text-4xl font-brand font-bold text-soft-pink mb-2">15k+</span>
                        <span class="text-gray-400 font-semibold text-xs uppercase tracking-widest">Khách hàng tin tưởng</span>
                    </div>
                    <div class="flex flex-col items-center border-x-0 md:border-x border-pink-100 px-12">
                        <span class="text-4xl font-brand font-bold text-soft-pink mb-2">10+</span>
                        <span class="text-gray-400 font-semibold text-xs uppercase tracking-widest">Năm kinh nghiệm</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-4xl font-brand font-bold text-soft-pink mb-2">24/7</span>
                        <span class="text-gray-400 font-semibold text-xs uppercase tracking-widest">Hỗ trợ khẩn cấp</span>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <footer class="bg-misty-rose/30 mt-20 pt-20 pb-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div>
                    <a href="#" class="font-brand text-3xl font-bold text-soft-pink mb-6 block no-underline">PetParadise</a>
                    <p class="text-gray-500 leading-relaxed mb-6">Nơi hội tụ những giá trị tốt nhất cho cộng đồng yêu thú cưng tại Việt Nam. Chúng tôi cam kết mang lại hạnh phúc cho mỗi "người bạn nhỏ".</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-soft-pink shadow-sm hover:bg-soft-pink hover:text-white transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-soft-pink shadow-sm hover:bg-soft-pink hover:text-white transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-soft-pink shadow-sm hover:bg-soft-pink hover:text-white transition-all"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-800">Liên kết nhanh</h5>
                    <ul class="space-y-4 text-gray-500 p-0 list-none">
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Về chúng tôi</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Hệ thống cửa hàng</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Tuyển dụng Groomer</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-800">Hỗ trợ khách hàng</h5>
                    <ul class="space-y-4 text-gray-500 p-0 list-none">
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Hướng dẫn mua hàng</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Tích điểm đổi quà</a></li>
                        <li><a href="#" class="hover:text-soft-pink transition-colors no-underline">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-800">Bản tin ưu đãi</h5>
                    <p class="text-gray-500 mb-4 text-sm italic">Đừng bỏ lỡ các đợt giảm giá lớn và kiến thức chăm sóc thú cưng hữu ích.</p>
                    <div class="flex">
                        <input type="email" class="bg-white border-none rounded-l-2xl py-3 px-4 w-full focus:ring-0" placeholder="Email của bạn...">
                        <button class="bg-soft-pink text-white px-5 rounded-r-2xl font-bold"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="border-t border-pink-100 pt-8 flex flex-col md:row justify-between items-center text-gray-400 text-sm">
                <p>&copy; 2025 PetParadise Studio. Design with ❤️ for Pets.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <span>Hotline: 1900 6789</span>
                    <span>Email: hello@petparadise.vn</span>
                </div>
            </div>
        </div>
    </footer>

    <div class="offcanvas offcanvas-start rounded-r-[2rem]" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header p-6">
            <h5 class="offcanvas-title font-brand font-bold text-soft-pink text-2xl" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-6">
            <ul class="space-y-6 list-none p-0 text-xl font-brand font-bold">
                <li><a href="#" class="text-gray-800 no-underline hover:text-soft-pink block">Trang chủ</a></li>
                <li><a href="#" class="text-gray-800 no-underline hover:text-soft-pink block">Cửa hàng</a></li>
                <li><a href="#" class="text-gray-800 no-underline hover:text-soft-pink block">Spa & Grooming</a></li>
                <li><a href="#" class="text-gray-800 no-underline hover:text-soft-pink block">Tin tức</a></li>
                <li><a href="#" class="text-gray-800 no-underline hover:text-soft-pink block">Liên hệ</a></li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>