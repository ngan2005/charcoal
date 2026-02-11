@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .rating-stars { color: #ffc107; }
        .rating-stars-gray { color: #e9ecef; }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý đánh giá</h1>
                <p class="text-sm text-gray-500">Theo dõi và xử lý đánh giá của khách hàng</p>
            </div>
            <a href="{{ route('admin.reviews.statistics') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">analytics</span>
                Thống kê
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-primary" style="font-size: 24px;">rate_review</span>
                        </div>
                        <div>
                            <div class="text-muted small">Tổng đánh giá</div>
                            <div class="fw-bold fs-4">{{ number_format($stats['total']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-warning" style="font-size: 24px;">star</span>
                        </div>
                        <div>
                            <div class="text-muted small">Đánh giá TB</div>
                            <div class="fw-bold fs-4">{{ number_format($stats['avg_rating'] ?? 0, 1) }} <small class="text-muted">/5</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-success" style="font-size: 24px;">stars</span>
                        </div>
                        <div>
                            <div class="text-muted small">5 sao</div>
                            <div class="fw-bold fs-4 text-success">{{ number_format($stats['five_star']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-danger" style="font-size: 24px;">star_half</span>
                        </div>
                        <div>
                            <div class="text-muted small">1 sao</div>
                            <div class="fw-bold fs-4 text-danger">{{ number_format($stats['one_star']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên khách, nội dung..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Loại đánh giá</label>
                    <select name="type" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="product" @selected(request('type') === 'product')>Sản phẩm</option>
                        <option value="service" @selected(request('type') === 'service')>Dịch vụ</option>
                        <option value="staff" @selected(request('type') === 'staff')>Nhân viên</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Số sao</label>
                    <select name="rating" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="5" @selected(request('rating') == 5)>5 sao</option>
                        <option value="4" @selected(request('rating') == 4)>4 sao</option>
                        <option value="3" @selected(request('rating') == 3)>3 sao</option>
                        <option value="2" @selected(request('rating') == 2)>2 sao</option>
                        <option value="1" @selected(request('rating') == 1)>1 sao</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <span class="material-symbols-outlined me-1 align-middle" style="font-size: 18px;">search</span>
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">filter_alt_off</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Khách hàng</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Loại</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Đánh giá</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Nội dung</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Ngày</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">person</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $review->customer?->FullName ?? 'Khách' }}</div>
                                        <small class="text-muted">{{ $review->customer?->Email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($review->ProductID)
                                    <span class="badge bg-success d-flex align-items-center gap-1" style="width: fit-content;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">inventory_2</span>
                                        Sản phẩm
                                    </span>
                                @elseif($review->ServiceID)
                                    <span class="badge bg-info d-flex align-items-center gap-1" style="width: fit-content;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">content_cut</span>
                                        Dịch vụ
                                    </span>
                                @elseif($review->StaffID)
                                    <span class="badge bg-warning d-flex align-items-center gap-1" style="width: fit-content;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">badge</span>
                                        Nhân viên
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined {{ $i <= $review->Rating ? 'rating-stars' : 'rating-stars-gray' }}" style="font-size: 20px;">
                                            {{ $i <= $review->Rating ? 'star' : 'star' }}
                                        </span>
                                    @endfor
                                    <span class="ms-1 text-muted">({{ $review->Rating }})</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div style="max-width: 300px;">
                                    <p class="mb-0 text-truncate" title="{{ $review->Comment }}">{{ $review->Comment }}</p>
                                    @if($review->product)
                                        <small class="text-success">{{ $review->product->ProductName }}</small>
                                    @elseif($review->service)
                                        <small class="text-info">{{ $review->service->ServiceName }}</small>
                                    @elseif($review->staff)
                                        <small class="text-warning">{{ $review->staff->FullName }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary" style="font-size: 18px;">calendar_today</span>
                                    <span>{{ $review->CreatedAt->format('d/m/Y') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.reviews.show', $review->ReviewID) }}" class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.reviews.hide', $review->ReviewID) }}" class="btn btn-outline-secondary btn-sm" title="Ẩn đánh giá" onclick="return confirm('Ẩn đánh giá này?')">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">visibility_off</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">rate_review</span>
                                    <p class="mb-0">Chưa có đánh giá nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = @json(session('success'));
            if (successMessage) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: successMessage,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        });
    </script>
@endpush












