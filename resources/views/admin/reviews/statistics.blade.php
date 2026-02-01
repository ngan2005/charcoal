@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .rating-stars { color: #ffc107; }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Thống kê đánh giá</h1>
                    <p class="text-sm text-gray-500">Tổng quan về đánh giá của khách hàng</p>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary">pie_chart</span>
                Phân bố số sao
            </h5>
            <div class="row">
                @php $totalRatings = $ratingDistribution->sum('count'); @endphp
                @forelse($ratingDistribution->sortByDesc('Rating') as $dist)
                    <div class="col-md-2 col-6 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                @for($i = 1; $i <= $dist->Rating; $i++)
                                    <span class="material-symbols-outlined rating-stars" style="font-size: 20px;">star</span>
                                @endfor
                            </div>
                            <div class="fw-bold fs-4">{{ $dist->Rating }}</div>
                            <div class="text-muted small">{{ $dist->count }} đánh giá</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalRatings > 0 ? ($dist->count / $totalRatings * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">Chưa có dữ liệu</div>
                @endforelse
            </div>
        </div>

        <!-- Top Products by Reviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-success">inventory_2</span>
                    Sản phẩm được đánh giá nhiều nhất
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Sản phẩm</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Số đánh giá</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Đánh giá TB</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($productReviews as $review)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                @if($review->ProductID)
                                    @php $product = \App\Models\Product::find($review->ProductID); @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-symbols-outlined text-success">inventory_2</span>
                                        <span>{{ $product?->ProductName ?? 'Sản phẩm #' . $review->ProductID }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-primary">{{ $review->total_reviews }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <span class="material-symbols-outlined rating-stars">star</span>
                                    <span class="fw-bold">{{ number_format($review->avg_rating, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Chưa có đánh giá sản phẩm</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top Services by Reviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-info">content_cut</span>
                    Dịch vụ được đánh giá nhiều nhất
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Dịch vụ</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Số đánh giá</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Đánh giá TB</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($serviceReviews as $review)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                @if($review->ServiceID)
                                    @php $service = \App\Models\Service::find($review->ServiceID); @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-symbols-outlined text-info">content_cut</span>
                                        <span>{{ $service?->ServiceName ?? 'Dịch vụ #' . $review->ServiceID }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-primary">{{ $review->total_reviews }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <span class="material-symbols-outlined rating-stars">star</span>
                                    <span class="fw-bold">{{ number_format($review->avg_rating, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Chưa có đánh giá dịch vụ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top Staff by Reviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-warning">badge</span>
                    Nhân viên được đánh giá cao nhất
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Nhân viên</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Số đánh giá</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Đánh giá TB</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($staffReviews as $review)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                @if($review->StaffID)
                                    @php $staff = \App\Models\User::find($review->StaffID); @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-symbols-outlined text-warning">badge</span>
                                        <span>{{ $staff?->FullName ?? 'Nhân viên #' . $review->StaffID }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-primary">{{ $review->total_reviews }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <span class="material-symbols-outlined rating-stars">star</span>
                                    <span class="fw-bold">{{ number_format($review->avg_rating, 1) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Chưa có đánh giá nhân viên</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

