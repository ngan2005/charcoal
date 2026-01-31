<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Charcoal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Charcoal</h1>
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
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Chào mừng, {{ auth()->user()->FullName }}!</h2>
            
            @if(auth()->user()->RoleID == 1)
                <!-- Admin -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Admin Dashboard</h3>
                        <p class="text-blue-700 mb-4">Quản lý hệ thống</p>
                        <a href="{{ route('admin.staff-requests') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            → Duyệt đơn xin việc
                        </a>
                    </div>
                </div>
            @elseif(auth()->user()->RoleID == 2)
                <!-- Staff -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-green-900 mb-2">Staff Dashboard</h3>
                        <p class="text-green-700">Quản lý công việc</p>
                    </div>
                </div>
            @else
                <!-- Customer -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-purple-900 mb-2">My Orders</h3>
                        <p class="text-purple-700">Xem đơn hàng của bạn</p>
                    </div>
                </div>
            @endif

            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="font-bold text-gray-900 mb-4">Thông tin tài khoản</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><strong>Email:</strong> {{ auth()->user()->Email }}</li>
                    <li><strong>Số điện thoại:</strong> {{ auth()->user()->Phone ?? 'Chưa cập nhật' }}</li>
                    <li><strong>Địa chỉ:</strong> {{ auth()->user()->Address ?? 'Chưa cập nhật' }}</li>
                    <li><strong>Vai trò:</strong> 
                        @if(auth()->user()->RoleID == 1)
                            Admin
                        @elseif(auth()->user()->RoleID == 2)
                            Nhân viên
                        @else
                            Khách hàng
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
