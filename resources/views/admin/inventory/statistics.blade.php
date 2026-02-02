@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Thống kê kho</h1>
                    <p class="text-sm text-gray-500">Tổng quan tồn kho theo danh mục</p>
                </div>
            </div>
        </div>

        <!-- Stock by Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary">pie_chart</span>
                    Tồn kho theo danh mục
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Danh mục</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Số sản phẩm</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Tổng tồn kho</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($byCategory as $category)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">category</span>
                                    <span class="fw-medium">{{ $category->CategoryName }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-primary">{{ $category->products_count }} sản phẩm</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <span class="fw-bold {{ $category->products_sum_stock <= 10 ? 'text-danger' : ($category->products_sum_stock <= 50 ? 'text-warning' : 'text-success') }}">
                                    {{ number_format($category->products_sum_stock ?? 0) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(($category->products_sum_stock ?? 0) == 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @elseif(($category->products_sum_stock ?? 0) <= 10)
                                    <span class="badge bg-warning">Sắp hết</span>
                                @else
                                    <span class="badge bg-success">Còn hàng</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Chưa có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-warning">warning</span>
                    Sản phẩm sắp hết hàng (1-10)
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Mã SP</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Danh mục</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Tồn kho</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lowStockProducts as $product)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <span class="fw-medium text-primary">{{ $product->ProductCode }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $product->ProductName }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-dark">{{ $product->category?->CategoryName ?? '--' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-warning">{{ $product->Stock }}</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('admin.inventory.show', $product->ProductID) }}" class="btn btn-outline-primary btn-sm">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">check_circle</span>
                                    <p class="mb-0">Không có sản phẩm sắp hết hàng</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Out of Stock Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-danger">remove_shopping_cart</span>
                    Sản phẩm hết hàng (0)
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Mã SP</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Danh mục</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Tồn kho</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($outOfStockProducts as $product)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <span class="fw-medium text-primary">{{ $product->ProductCode }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $product->ProductName }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-dark">{{ $product->category?->CategoryName ?? '--' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-danger">0</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('admin.inventory.show', $product->ProductID) }}" class="btn btn-outline-primary btn-sm">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">check_circle</span>
                                    <p class="mb-0">Không có sản phẩm hết hàng</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection





