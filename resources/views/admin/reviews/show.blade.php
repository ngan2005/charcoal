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
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Chi tiết đánh giá</h1>
                    <p class="text-sm text-gray-500">Ngày đánh giá: {{ $review->CreatedAt->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reviews.hide', $review->ReviewID) }}" class="btn btn-outline-secondary" onclick="return confirm('Ẩn đánh giá này?')">
                    <span class="material-symbols-outlined me-1 align-middle">visibility_off</span>
                    Ẩn đánh giá
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Review Info -->
            <div class="col-lg-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">rate_review</span>
                        Thông tin đánh giá
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Số sao</span>
                            <div class="d-flex align-items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined {{ $i <= $review->Rating ? 'rating-stars' : 'rating-stars-gray' }}" style="font-size: 24px;">
                                        {{ $i <= $review->Rating ? 'star' : 'star' }}
                                    </span>
                                @endfor
                                <span class="ms-2 fw-bold">({{ $review->Rating }}/5)</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Loại đánh giá</span>
                            @if($review->ProductID)
                                <span class="badge bg-success">Sản phẩm</span>
                            @elseif($review->ServiceID)
                                <span class="badge bg-info">Dịch vụ</span>
                            @elseif($review->StaffID)
                                <span class="badge bg-warning">Nhân viên</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Đối tượng</span>
                            <span>
                                @if($review->product)
                                    {{ $review->product->ProductName }}
                                @elseif($review->service)
                                    {{ $review->service->ServiceName }}
                                @elseif($review->staff)
                                    {{ $review->staff->FullName }}
                                @else
                                    --
                                @endif
                            </span>
                        </div>
                        <hr>
                        <div>
                            <span class="text-muted d-block mb-2">Nội dung đánh giá</span>
                            <p class="mb-0 p-3 bg-light rounded">{{ $review->Comment }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="col-lg-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Thông tin khách hàng
                    </h5>
                    @if($review->customer)
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <span class="material-symbols-outlined text-primary" style="font-size: 28px;">person</span>
                                </div>
                                <div>
                                    <div class="fw-bold fs-5">{{ $review->customer->FullName }}</div>
                                    <small class="text-muted">{{ $review->customer->Email }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Điện thoại</span>
                                <span>{{ $review->customer->Phone ?? '--' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Địa chỉ</span>
                                <span class="text-end" style="max-width: 200px;">{{ $review->customer->Address ?? '--' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Không có thông tin khách hàng</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Replies -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-info">reply</span>
                    Phản hồi ({{ $review->replies->count() }})
                </h5>
            </div>
            <div class="p-4">
                @forelse($review->replies as $reply)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                            <span class="material-symbols-outlined text-info" style="font-size: 20px;">support_agent</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">{{ $reply->customer?->FullName ?? 'Admin' }}</span>
                                <small class="text-muted">{{ $reply->CreatedAt->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-0 text-muted">{{ $reply->Comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-4">Chưa có phản hồi nào</p>
                @endforelse
            </div>

            <!-- Reply Form -->
            <div class="p-4 bg-light border-top">
                <form action="{{ route('admin.reviews.reply', $review->ReviewID) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Phản hồi đánh giá</label>
                        <textarea name="Comment" class="form-control" rows="3" placeholder="Nhập phản hồi..." maxlength="500" required></textarea>
                        <small class="text-muted">Tối đa 500 ký tự</small>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <span class="material-symbols-outlined me-1 align-middle">send</span>
                            Gửi phản hồi
                        </button>
                    </div>
                </form>
            </div>
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












