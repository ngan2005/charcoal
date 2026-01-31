<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Nhân Viên - Charcoal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo/Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Charcoal</h1>
                <p class="mt-2 text-gray-600">Gửi yêu cầu tuyển dụng nhân viên</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Register Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('register.staff') }}">
                @csrf
                <div class="rounded-lg shadow-md p-6 bg-white space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700">
                            Họ và Tên
                        </label>
                        <input id="fullname" name="FullName" type="text" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('FullName') border-red-500 @enderror"
                            value="{{ old('FullName') }}">
                        @error('FullName')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input id="email" name="Email" type="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('Email') border-red-500 @enderror"
                            value="{{ old('Email') }}">
                        @error('Email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Số Điện Thoại
                        </label>
                        <input id="phone" name="Phone" type="tel"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('Phone') }}">
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Địa Chỉ
                        </label>
                        <textarea id="address" name="Address" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('Address') }}</textarea>
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700">
                            Vị Trí Tuyển Dụng
                        </label>
                        <input id="position" name="Position" type="text" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('Position') border-red-500 @enderror"
                            value="{{ old('Position') }}"
                            placeholder="VD: Nhân viên bán hàng, Nhân viên kho">
                        @error('Position')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason for Application -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">
                            Lý Do Ứng Tuyển
                        </label>
                        <textarea id="reason" name="ReasonForApplication" rows="3" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('ReasonForApplication') border-red-500 @enderror">{{ old('ReasonForApplication') }}</textarea>
                        @error('ReasonForApplication')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Gửi Yêu Cầu
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center text-sm">
                <p class="text-gray-600">
                    Bạn đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Đăng nhập ngay
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
