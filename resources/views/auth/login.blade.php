<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Charcoal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo/Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Charcoal</h1>
                <p class="mt-2 text-gray-600">Đăng nhập vào tài khoản của bạn</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded relative">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Login Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="rounded-lg shadow-md p-6 bg-white space-y-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input id="email" name="Email" type="email" autocomplete="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('Email') border-red-500 @enderror"
                            value="{{ old('Email') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mật Khẩu
                        </label>
                        <input id="password" name="Password" type="password" autocomplete="current-password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('Password') border-red-500 @enderror">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ghi nhớ tôi
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Đăng Nhập
                    </button>
                </div>
            </form>

            <!-- Links -->
            <div class="flex items-center justify-between text-sm">
                <a href="{{ route('forgot-password') }}" class="text-blue-600 hover:text-blue-700">
                    Quên mật khẩu?
                </a>
            </div>

            <!-- Register Links -->
            <div class="text-center space-y-2 text-sm">
                <p class="text-gray-600">
                    Bạn chưa có tài khoản?
                    <a href="{{ route('register-customer') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Đăng ký khách hàng
                    </a>
                </p>
                <p class="text-gray-600">
                    Bạn là nhân viên?
                    <a href="{{ route('register-staff') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Gửi yêu cầu tuyển dụng
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
