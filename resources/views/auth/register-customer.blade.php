<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng ký - Pink Charcoal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#F4C2C3",
                        "soft-pink": "#F4C2C3",
                        "background-light": "#f8f7f6",
                        "background-dark": "#221910",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body {
            font-family: "Be Vietnam Pro", sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">

    <main class="flex-grow flex items-center justify-center p-4 lg:p-8">
        <div class="max-w-6xl w-full bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-2xl flex flex-col lg:flex-row">
            <!-- Left Side: Illustration -->
            <div class="lg:w-1/2 relative hidden lg:block overflow-hidden">
                <div class="absolute inset-0 bg-primary/10" style="background-color: rgba(244, 194, 195, 0.1);"></div>
                <img alt="Cute pets" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfzOVA5LZhWO-QlgU5Cw1wTqgBmtfh15v-jalyqpdnnvmHcXGFCB0YNFnCT4AYByyyvE9gJ3V0uwS76Np5n2tN5v6H3ZFp2wUeG36hFqYzAiodJW5QUYaulaZbfK5v0Q-hwP8sLgbVLbBP0dGjiMPEbfzBjGhFzFsyVHrMIxsFrEdpQdc1LfY29GbjjuSQwjdYY4XAvZ0qVMvQUIOAXyU9xPjGPOQ4wOZPy49sgl6YyiusEmPjUgrVqiG5rZ8byme0cYT33Zt4HQ"/>
                <div class="absolute bottom-12 left-12 right-12 p-8 bg-white/80 backdrop-blur-md rounded-xl">
                    <p class="text-slate-800 text-lg font-medium italic">"Nơi yêu thương bắt đầu từ những người bạn bốn chân của bạn."</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center">
                <div class="mb-10">
                    <h1 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-white mb-2">Đăng ký tài khoản</h1>
                    <p class="text-slate-600 dark:text-slate-400">Điền thông tin để trở thành thành viên của gia đình Pink Charcoal</p>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                        <ul class="text-sm text-red-500 dark:text-red-400 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('register.customer') }}">
                    @csrf
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Họ và tên</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                            <input name="FullName" value="{{ old('FullName') }}" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all @error('FullName') border-red-500 @enderror" placeholder="Nhập họ và tên của bạn" type="text" required/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Email</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                                <input name="Email" value="{{ old('Email') }}" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all @error('Email') border-red-500 @enderror" placeholder="example@gmail.com" type="email" required/>
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Số điện thoại</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">call</span>
                                <input name="Phone" value="{{ old('Phone') }}" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all" placeholder="Nhập số điện thoại" type="tel"/>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Mật khẩu</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                            <input name="Password" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all @error('Password') border-red-500 @enderror" placeholder="••••••••" type="password" required/>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Xác nhận mật khẩu</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">verified_user</span>
                            <input name="Password_confirmation" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all" placeholder="••••••••" type="password" required/>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button class="w-full bg-primary hover:opacity-90 text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 mt-4" style="box-shadow: 0 10px 15px -3px rgba(244, 194, 195, 0.4);" type="submit">
                        Tạo tài khoản
                    </button>
                </form>

                <div class="mt-8">
                    <div class="relative flex items-center justify-center mb-6">
                        <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                        <span class="flex-shrink mx-4 text-sm text-slate-500 bg-white dark:bg-slate-900 px-2">Hoặc đăng ký bằng</span>
                        <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                            <img alt="Google" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuATK4IKrjKIseWCSUZkCD5T-zJYOgjpctz_qWByHLQ9Pvyu6Kh2oO6k0SZ77fuotus40aqT_I5JPkBQ90ketllJkDP9xsCqWC5QO6xFb3tKIZXtJA7v0ldI9XzH28RvKi70fdS_CzP7NnBjULN_-8GPhcPsOAf3tMjbrKszGmv9go9LckykwLY8DHszZBNhEQjgWrXnO3gi0JcuqM-Jpqp2iE0xS_S9B13na7wp9t0jqGTaTaDBPBwfFX-0JX1tK2ASwVNVrfQO7w"/>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Google</span>
                        </button>
                        <button class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                            <img alt="Facebook" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDV3tDZLTGPhBCj8V7GvOq8L6wBJ3UTLynR-gWi2zE6BR4l8D4cI3aO71Z_1sFzqz0KU-vuZjCtJE3wMBNWL_QhZOxvd4THMxlmfgT4kOMVNbBpMDLFf6Sr98DBri6oHhoCo9d2M95JmX8hVm2gghIH46fl_5KGK4mxIW0RR-mrWDZ4LnSdNzj1cCfAOtv2SovArIB2KGsg_FV1armQhj8TokskMMzP_RnAUeqTKu9lzwynM6hT8xZPcIaFm7MP-TTuuUOQMxtCqA"/>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Facebook</span>
                        </button>
                    </div>
                </div>

                <p class="mt-10 text-center text-slate-600 dark:text-slate-400 text-sm">
                    Bạn đã có tài khoản? 
                    <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-slate-500 text-xs text-slate-500">
        © {{ date('Y') }} Pink Charcoal Pet Shop. Bảo lưu mọi quyền.
    </footer>
</body>
</html>
