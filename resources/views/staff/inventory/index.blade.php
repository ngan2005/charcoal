@extends('layouts.staff')

@section('title', 'Quản lý Kho - Nhân viên')

@section('header_title', 'Quản lý Kho')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Danh sách sản phẩm kho</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin tồn kho sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.inventory') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">inventory_2</span>
                Tất cả
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_products'] }}</p>
                    <p class="text-xs text-gray-500">Tổng sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_stock']) }}</p>
                    <p class="text-xs text-gray-500">Tổng tồn kho</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['low_stock'] }}</p>
                    <p class="text-xs text-gray-500">Sắp hết hàng</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">remove_shopping_cart</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['out_of_stock'] }}</p>
                    <p class="text-xs text-gray-500">Hết hàng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white dark:bg-[#111827] rounded-xl border border-gray-100 dark:border-gray-800 p-4">
        <form action="{{ route('staff.inventory') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo mã hoặc tên sản phẩm..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent dark:text-white">
            </div>
            <div class="flex gap-3">
                <select name="category_id" class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-primary dark:text-white">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryID }}" {{ request('category_id') == $category->CategoryID ? 'selected' : '' }}>
                            {{ $category->CategoryName }}
                        </option>
                    @endforeach
                </select>
                <select name="stock_status" class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-primary dark:text-white">
                    <option value="">Tình trạng kho</option>
                    <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Sắp hết</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Hết hàng</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-[#111827] rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">STT</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Sản phẩm</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Danh mục</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Giá bán</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Tồn kho</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($products as $index => $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $index + 1 + ($products->currentPage() - 1) * $products->perPage() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                        @php
                                            $firstImage = $product->images->where('IsMain', 1)->first() ?: $product->images->first();
                                        @endphp
                                        @if($firstImage && $firstImage->ImageUrl)
                                            <img src="{{ $firstImage->ImageUrl }}" alt="{{ $product->ProductName }}" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-gray-400 text-3xl\'>image</span>'">
                                        @else
                                            <span class="material-symbols-outlined text-gray-400 text-3xl">image</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">{{ $product->ProductName }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->ProductCode }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    {{ $product->category->CategoryName ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($product->Price, 0, ',', '.') }} đ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $product->Stock <= 10 ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">
                                    {{ $product->Stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->Stock == 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        Hết hàng
                                    </span>
                                @elseif($product->Stock <= 10)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span>
                                        Sắp hết
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                        Còn hàng
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                                    <p>Không tìm thấy sản phẩm nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-4 border border-blue-100 dark:border-blue-900/30">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-blue-600 mt-0.5">info</span>
            <div>
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Lưu ý</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                    Đây là trang xem thông tin tồn kho. Để cập nhật số lượng sản phẩm, vui lòng liên hệ Admin.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection


