<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn xin việc - Charcoal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Charcoal Admin</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-700">{{ auth()->user()->FullName }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Đăng Xuất
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-12">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">Chi tiết đơn xin việc</h2>
            <a href="{{ route('admin.staff-requests') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Quay lại danh sách
            </a>
        </div>

        @if($message = session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
                {{ $message }}
            </div>
        @endif

        @if($message = session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded">
                {{ $message }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-8 mb-6">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Họ tên</label>
                    <p class="text-lg text-gray-900">{{ $request->FullName }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <p class="text-lg text-gray-900">{{ $request->Email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
                    <p class="text-lg text-gray-900">{{ $request->Phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vị trí ứng tuyển</label>
                    <p class="text-lg text-gray-900">{{ $request->Position }}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                    <p class="text-lg text-gray-900">{{ $request->Address }}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lý do ứng tuyển</label>
                    <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $request->ReasonForApplication }}</p>
                </div>
            </div>

            @if($request->Status === 'pending')
                <hr class="my-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Xử lý đơn</h3>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <!-- Duyệt đơn -->
                    <form method="POST" action="{{ route('admin.staff-request.approve', $request->RequestID) }}">
                        @csrf
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <h4 class="text-lg font-semibold text-green-900 mb-4">Duyệt đơn</h4>
                            
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu tạm (để gửi cho nhân viên)</label>
                                <input type="password" name="password" id="password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                                @error('password_confirmation')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                                ✓ Duyệt và tạo tài khoản
                            </button>
                        </div>
                    </form>

                    <!-- Từ chối đơn -->
                    <form method="POST" action="{{ route('admin.staff-request.reject', $request->RequestID) }}" onsubmit="return confirm('Bạn chắc chắn muốn từ chối đơn này?');">
                        @csrf
                        <div class="bg-red-50 p-6 rounded-lg border border-red-200 flex flex-col justify-center h-full">
                            <h4 class="text-lg font-semibold text-red-900 mb-4">Từ chối đơn</h4>
                            <p class="text-gray-700 text-sm mb-4">Nhấn nút dưới để từ chối đơn xin việc này.</p>
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                                ✗ Từ chối đơn
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-8 p-4 bg-gray-100 rounded text-center">
                    <p class="text-gray-700 font-semibold">
                        Trạng thái: <span class="text-{{ $request->Status === 'approved' ? 'green' : 'red' }}-600">
                            {{ $request->Status === 'approved' ? 'Đã duyệt' : 'Đã từ chối' }}
                        </span>
                    </p>
                    @if($request->ApprovedAt)
                        <p class="text-gray-600 text-sm mt-2">
                            Xử lý lúc: {{ $request->ApprovedAt->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
