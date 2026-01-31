<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt đơn xin việc - Charcoal</title>
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
            <h2 class="text-3xl font-bold text-gray-900">Danh sách đơn xin việc</h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Quay lại Dashboard
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

        @if($requests->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-600 text-lg">Không có đơn xin việc chưa được duyệt</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Họ tên</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Vị trí</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Ngày nộp</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $request->FullName }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $request->Email }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $request->Position }}</td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.staff-request.show', $request->RequestID) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-semibold">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            @endif
        @endif
    </div>
</body>
</html>
