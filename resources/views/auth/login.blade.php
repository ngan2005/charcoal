<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng nhập - Pink Charcoal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#F4C2C3",
                        "background-light": "#FFF9F9",
                        "background-dark": "#221910",
                        "soft-pink": "#FCE4E5",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "1.5rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4">
    <div class="max-w-[1100px] w-full bg-white dark:bg-slate-900 rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px]">
        <!-- Left Side: Illustration & Branding -->
        <div class="w-full md:w-1/2 bg-soft-pink dark:bg-slate-800 p-12 flex flex-col justify-center items-center text-center relative overflow-hidden">
            <div class="absolute top-8 left-8 flex items-center gap-2">
                <a href="{{ route('shop') }}" class="flex items-center gap-2">
                    <div class="bg-primary p-2 rounded-full">
                        <span class="material-symbols-outlined text-white">pets</span>
                    </div>
                    <span class="text-slate-900 dark:text-slate-100 font-bold text-2xl tracking-tight">Pink Charcoal</span>
                </a>
            </div>
            <div class="z-10 mt-10">
                <div class="w-64 h-64 mx-auto mb-8 rounded-full overflow-hidden border-8 border-white dark:border-slate-700 shadow-lg bg-white">
                    <img class="w-full h-full object-contain p-4" alt="Logo Pink Charcoal" src="{{ asset('images/logo-pink-charcoal.png') }}"/>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 mb-4">Chào mừng bạn!</h1>
                <p class="text-slate-600 dark:text-slate-400 max-w-xs mx-auto">Nơi dành cho những người yêu thương và chăm sóc thú cưng tận tâm nhất.</p>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-2xl"></div>
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/20 rounded-full blur-2xl"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white dark:bg-slate-900">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Đăng nhập</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2">Đăng nhập để tiếp tục chăm sóc các bé yêu</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined">error</span>
                        <span class="font-medium">Đăng nhập thất bại</span>
                    </div>
                    <ul class="mt-2 text-sm text-red-500 dark:text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800">
                    <div class="flex items-center gap-2 text-yellow-600 dark:text-yellow-400">
                        <span class="material-symbols-outlined">warning</span>
                        <span class="font-medium">{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                        <input name="Email" value="{{ old('Email') }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-full focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-all @error('Email') ring-2 ring-red-500 @enderror" placeholder="example@gmail.com" type="email" required/>
                    </div>
                </div>
                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Mật khẩu</label>
                        <a class="text-sm font-medium text-primary hover:underline" href="{{ route('forgot-password') }}">Quên mật khẩu?</a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                        <input id="password" name="Password" class="w-full pl-12 pr-12 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-full focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-all @error('Password') ring-2 ring-red-500 @enderror" placeholder="Nhập mật khẩu" type="password" required/>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary" type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2 ml-1">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                    <label for="remember" class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer">Ghi nhớ tôi</label>
                </div>

                <!-- Login Button -->
                <button class="w-full bg-primary hover:bg-[#efb1b2] text-white font-bold py-4 rounded-full shadow-lg shadow-primary/20 transition-all transform hover:scale-[1.02] active:scale-95 mt-4 flex items-center justify-center gap-2" type="submit">
                    <span class="material-symbols-outlined">login</span>
                    Đăng nhập
                </button>

                <!-- Divider -->
                <div class="relative flex items-center gap-4 my-8">
                    <div class="flex-grow border-t border-slate-100 dark:border-slate-800"></div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-widest">Hoặc đăng nhập bằng</span>
                    <div class="flex-grow border-t border-slate-100 dark:border-slate-800"></div>
                </div>

                <!-- Social Logins -->
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-3 py-3 px-4 rounded-full border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all" type="button">
                        <img alt="Google Logo" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA4E8fzAlLCivQvZSlc_NE2wYvL50KJWKNTVz28xMliDS2wf1Au9LyhOiZiOx6HRvEkosydiNne0saon6JMmWBctwElLv3Aa587t1iDjznneN-pm1aztHj_1L_MSM07sda5zUksEL9BTZhIYGZ0yEa4wRI76vSCWQpIUb-sfBAGglXVFTekwcZmhbRWt6CGJxHor_Jksmr0tRVTViOjBt5-KD7CBvXSV6opBwwO8AM7edc5sn_EcLSJGfqAbjptHQCqxjus9oPqVw"/>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Google</span>
                    </button>
                    <button class="flex items-center justify-center gap-3 py-3 px-4 rounded-full border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all" type="button">
                        <img alt="Facebook Logo" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD_HIqvze6iy3r30b_LFw5fWHa1uco1Bb7GjPU79f12gZKThP7YjIkcQ841D0mptol8gtbCGXpB768ivApCnh1HjzIbufLOLiOmqZ-JQf3SdGzbEa_hGWpx-SnVDcXRlbW2I1oZN7YvYQBnzqoi2NDEKaPir0HhP7r0NHt0u4NmQ4dnh4FDbLw9r4zaW1e-X3EgyyMJ6OVqZVgg9R5x4oZd01XGWznRrcNLQHwympTBJgaoIM27fj5rt-7r_npVb_DfMiNAfmxXsA"/>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Facebook</span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center space-y-3 mt-8">
                    <p class="text-slate-600 dark:text-slate-400 text-sm">
                        Chưa có tài khoản? 
                        <a class="text-primary font-bold hover:underline ml-1" href="{{ route('register-customer') }}">Đăng ký ngay</a>
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Hoặc là nhân viên mới? 
                        <a href="{{ route('register-staff') }}" class="text-primary font-medium hover:underline ml-1">Gửi hồ sơ tuyển dụng</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>
