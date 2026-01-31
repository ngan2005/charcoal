@extends('layouts.admin')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Tổng quan nhanh về hoạt động hệ thống</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng khách hàng</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng nhân viên</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStaff) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng sản phẩm</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng dịch vụ</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalServices) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng doanh thu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 0, ',', '.') }} đ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Doanh thu 7 ngày gần nhất</h3>
                <span class="text-xs text-gray-400">Đơn vị: VND</span>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">5 sản phẩm mới thêm gần đây</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Sản phẩm</th>
                        <th class="px-6 py-3 text-left">Danh mục</th>
                        <th class="px-6 py-3 text-left">Giá</th>
                        <th class="px-6 py-3 text-left">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentProducts as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-900">{{ $product->ProductName }}</div>
                                <div class="text-xs text-gray-500">Mã: {{ $product->ProductCode ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $product->category?->CategoryName ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ number_format((float) ($product->Price ?? 0), 0, ',', '.') }} đ</td>
                            <td class="px-6 py-3 text-gray-600">{{ optional($product->CreatedAt)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                Chưa có sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($chartValues),
                    borderColor: '#135bec',
                    backgroundColor: 'rgba(19, 91, 236, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: value => value.toLocaleString('vi-VN')
                        }
                    }
                }
            }
        });
    </script>
@endpush
