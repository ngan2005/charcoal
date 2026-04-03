@extends('layouts.staff')

@section('title', 'Nhật ký chăm sóc á - Nhân viên')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        .journal-card {
            border-radius: 1rem;
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .journal-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: #7c3aed;
        }
        .stat-card {
            border-radius: 1rem;
            border: none;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-in-progress { background: #dbeafe; color: #2563eb; }
        .badge-completed { background: #d1fae5; color: #059669; }
        .badge-other { background: #f3f4f6; color: #6b7280; }
    </style>
@endpush

@section('header_title', 'Nhật ký chăm sóc á')

@section('content')
<div class="container-fluid py-2">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">Nhật ký chăm sóc á</h2>
            <p class="text-secondary mb-0">Theo dõi danh sách dịch vụ giặt ủi đã được chăm sóc</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary fw-semibold rounded-3" data-bs-toggle="modal" data-bs-target="#addJournalModal">
                <span class="material-symbols-outlined fs-6 align-middle">add</span>
                Thêm ghi chú mới
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white shadow-sm p-3 text-center">
                <div class="text-secondary small fw-semibold">Tổng số</div>
                <div class="fw-bold text-dark fs-4">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white shadow-sm p-3 text-center">
                <div class="text-secondary small fw-semibold">Chờ xử lý</div>
                <div class="fw-bold text-warning fs-4">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white shadow-sm p-3 text-center">
                <div class="text-secondary small fw-semibold">Đang thực hiện</div>
                <div class="fw-bold text-primary fs-4">{{ $stats['in_progress'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white shadow-sm p-3 text-center">
                <div class="text-secondary small fw-semibold">Hoàn thành</div>
                <div class="fw-bold text-success fs-4">{{ $stats['completed'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('staff.cloth-journal') }}" method="GET" class="d-flex gap-3 flex-wrap">
                <select name="status" class="form-select" style="max-width: 200px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                </select>
                <select name="date_range" class="form-select" style="max-width: 200px;">
                    <option value="7days" {{ request('date_range', '7days') == '7days' ? 'selected' : '' }}>7 ngày gần nhất</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>30 ngày gần nhất</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">Lọc</button>
                <a href="{{ route('staff.cloth-journal') }}" class="btn btn-outline-secondary">Đặt lại</a>
            </form>
        </div>
    </div>

    <!-- Journal List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @forelse($careLogs as $log)
                <div class="journal-card p-4 mb-3 mx-3 mt-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 p-3 bg-purple-subtle text-purple flex-shrink-0" style="background: #f3e8ff; color: #7c3aed;">
                            <span class="material-symbols-outlined fs-4">checkroom</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $log->ItemName }}</h5>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                @php
                                    $badgeClass = match($log->Status) {
                                        'pending' => 'badge-pending',
                                        'in_progress' => 'badge-in-progress',
                                        'completed' => 'badge-completed',
                                        default => 'badge-other'
                                    };
                                    $statusLabel = match($log->Status) {
                                        'pending' => 'Chờ xử lý',
                                        'in_progress' => 'Đang thực hiện',
                                        'completed' => 'Hoàn thành',
                                        default => $log->Status
                                    };
                                @endphp
                                <span class="badge rounded-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </div>

                            {{-- Service Info --}}
                            <div class="d-flex gap-3 mb-2 flex-wrap">
                                @if($log->ServiceName)
                                    <span class="d-flex align-items-center gap-1 text-sm text-secondary">
                                        <span class="material-symbols-outlined fs-6">dry_cleaning</span>
                                        {{ $log->ServiceName }}
                                    </span>
                                @endif
                                @if($log->ItemType)
                                    <span class="d-flex align-items-center gap-1 text-sm text-secondary">
                                        <span class="material-symbols-outlined fs-6">category</span>
                                        {{ $log->ItemType }}
                                    </span>
                                @endif
                                @if($log->Condition)
                                    <span class="d-flex align-items-center gap-1 text-sm text-secondary">
                                        <span class="material-symbols-outlined fs-6">info</span>
                                        Tình trạng: {{ $log->Condition }}
                                    </span>
                                @endif
                            </div>

                            {{-- Notes --}}
                            @if($log->BeforeNotes)
                                <div class="mb-2">
                                    <small class="text-secondary fw-semibold">Ghi chú trước:</small>
                                    <p class="mb-1 text-secondary small">{{ $log->BeforeNotes }}</p>
                                </div>
                            @endif
                            @if($log->AfterNotes)
                                <div class="mb-2">
                                    <small class="text-success fw-semibold">Ghi chú sau:</small>
                                    <p class="mb-1 text-success small">{{ $log->AfterNotes }}</p>
                                </div>
                            @endif
                            @if($log->StaffNotes)
                                <div class="mb-2">
                                    <small class="text-primary fw-semibold">Ghi chú nhân viên:</small>
                                    <p class="mb-0 text-primary small">{{ $log->StaffNotes }}</p>
                                </div>
                            @endif

                            {{-- Order & Staff --}}
                            <div class="d-flex gap-4 text-sm text-muted mt-2">
                                @if($log->order)
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined fs-6">receipt_long</span>
                                        Đơn: {{ $log->order->OrderCode }}
                                    </span>
                                @endif
                                <span class="d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined fs-6">person</span>
                                    {{ $log->staff?->FullName ?? 'Nhân viên' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <span class="material-symbols-outlined fs-1 text-muted">checkroom</span>
                    <h5 class="fw-bold mt-3">Chưa có nhật ký nào</h5>
                    <p class="text-muted">Hãy thêm nhật ký chăm sóc á đầu tiên!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Thêm nhật ký chăm sóc á -->
<div class="modal fade" id="addJournalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm nhật ký chăm sóc á</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.cloth-journal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Món đồ <span class="text-danger">*</span></label>
                        <input type="text" name="ItemName" class="form-control" placeholder="Ví dụ: Áo sơ mi trắng" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Loại món đồ</label>
                        <select name="ItemType" class="form-select">
                            <option value="">-- Chọn loại --</option>
                            <option value="Áo sơ mi">Áo sơ mi</option>
                            <option value="Áo phông">Áo phông</option>
                            <option value="Quần dài">Quần dài</option>
                            <option value="Quần ngắn">Quần ngắn</option>
                            <option value="Váy">Váy</option>
                            <option value="Áo khoác">Áo khoác</option>
                            <option value="Đầm">Đầm</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dịch vụ</label>
                        <input type="text" name="ServiceName" class="form-control" placeholder="Ví dụ: Giặt ủi, Giặt khô, Ủi nhiệt">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tình trạng</label>
                        <select name="Condition" class="form-select">
                            <option value="">-- Chọn tình trạng --</option>
                            <option value="Tốt">Tốt</option>
                            <option value="Có vết bẩn nhẹ">Có vết bẩn nhẹ</option>
                            <option value="Có vết bẩn nặng">Có vết bẩn nặng</option>
                            <option value="Bị bay màu">Bị bay màu</option>
                            <option value="Bị rách nhẹ">Bị rách nhẹ</option>
                            <option value="Cần ủi">Cần ủi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="Status" class="form-select">
                            <option value="pending">Chờ xử lý</option>
                            <option value="in_progress">Đang thực hiện</option>
                            <option value="completed">Hoàn thành</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú trước</label>
                        <textarea name="BeforeNotes" class="form-control" rows="2" placeholder="Ghi chú trước khi xử lý..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú sau</label>
                        <textarea name="AfterNotes" class="form-control" rows="2" placeholder="Ghi chú sau khi xử lý..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú nhân viên</label>
                        <textarea name="StaffNotes" class="form-control" rows="2" placeholder="Ghi chú riêng của nhân viên..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu nhật ký</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
