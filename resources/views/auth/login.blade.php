<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Pink Charcoal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF9F1C',
                        primary-dark: '#e8890b',
                        secondary: '#FFBF69',
                    },
                    fontFamily: {
                        display: ['Plus Jakarta Sans'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary rounded-2xl shadow-lg shadow-primary/30 mb-4">
                    <span class="material-symbols-outlined text-white text-4xl">pets</span>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pink Charcoal</h1>
                <p class="mt-2 text-gray-500">Đăng nhập để tiếp tục</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-2 bg-red-50 border border-red-200">
                            <div class="flex items-center gap-2 text-red-600">
                                <span class="material-symbols-outlined">error</span>
                                <span class="font-medium">Đăng nhập thất bại</span>
                            </div>
                            <ul class="mt-2 text-sm text-red-500 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-2 bg-green-50 border border-green-200">
                            <div class="flex items-center gap-2 text-green-600">
                                <span class="material-symbols-outlined">check_circle</span>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="mb-6 p-4 rounded-2 bg-yellow-50 border border-yellow-200">
                            <div class="flex items-center gap-2 text-yellow-600">
                                <span class="material-symbols-outlined">warning</span>
                                <span class="font-medium">{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                                <input
                                    type="email"
                                    name="Email"
                                    value="{{ old('Email') }}"
                                    placeholder="Nhập email của bạn"
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary focus:bg-white transition-all @error('Email') ring-2 ring-red-500 bg-red-50 @enderror">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                <input
                                    type="password"
                                    name="Password"
                                    id="password"
                                    placeholder="Nhập mật khẩu"
                                    required
                                    class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary focus:bg-white transition-all @error('Password') ring-2 ring-red-500 bg-red-50 @enderror">
                                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                                <span class="text-sm text-gray-600">Ghi nhớ tôi</span>
                            </label>
                            <a href="{{ route('forgot-password') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:translate-y-[-2px] active:translate-y-0 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">login</span>
                            Đăng Nhập
                        </button>
                    </form>
                </div>

                <!-- Footer Links -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    <div class="space-y-3 text-center">
                        <p class="text-gray-600 text-sm">
                            Bạn chưa có tài khoản?
                            <a href="{{ route('register-customer') }}" class="text-primary font-semibold hover:text-primary-dark">
                                Đăng ký ngay
                            </a>
                        </p>
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                            <span>hoặc là nhân viên mới?</span>
                            <a href="{{ route('register-staff') }}" class="text-primary font-medium hover:underline">
                                Gửi hồ sơ tuyển dụng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="text-sm">Quay lại cửa hàng</span>
                </a>
            </div>
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
