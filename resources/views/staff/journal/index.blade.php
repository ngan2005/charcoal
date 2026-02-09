@extends('layouts.staff')

@section('title', 'Nhật ký chăm sóc - Nhân viên')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .journal-card {
            border-radius: 1rem;
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .journal-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: #2563eb;
        }
    </style>
@endpush

@section('header_title', 'Nhật ký chăm sóc')

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
            <h2 class="fw-bold text-dark">Nhật ký chăm sóc</h2>
            <p class="text-secondary mb-0">Ghi chép và theo dõi quá trình chăm sóc thú cưng</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary fw-semibold rounded-3" data-bs-toggle="modal" data-bs-target="#addJournalModal">
                <span class="material-symbols-outlined fs-6 align-middle">add</span>
                Thêm ghi chú mới
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('staff.journal') }}" method="GET" class="d-flex gap-3">
                <select name="pet_id" class="form-select" style="max-width: 200px;">
                    <option value="">Tất cả thú cưng</option>
                    @foreach($pets as $pet)
                        <option value="{{ $pet->PetID }}" {{ request('pet_id') == $pet->PetID ? 'selected' : '' }}>
                            {{ $pet->PetName }}
                        </option>
                    @endforeach
                </select>
                <select name="date_range" class="form-select" style="max-width: 200px;">
                    <option value="7days" {{ request('date_range', '7days') == '7days' ? 'selected' : '' }}>7 ngày gần nhất</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>30 ngày gần nhất</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">Lọc</button>
            </form>
        </div>
    </div>

    <!-- Journal List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @forelse($careRecords as $record)
                <div class="journal-card p-4 mb-3 mx-3 mt-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary flex-shrink-0">
                            <span class="material-symbols-outlined fs-4">pets</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $record->Title ?? 'Không có tiêu đề' }}</h5>
                                    <small class="text-muted">{{ $record->created_at->diffForHumans() }}</small>
                                </div>
                                @if($record->Status)
                                    <span class="badge rounded-pill bg-info-subtle text-info-emphasis border">{{ $record->Status }}</span>
                                @endif
                            </div>
                            <p class="text-secondary mb-3">{{ $record->Notes ?? 'Không có ghi chú' }}</p>
                            <div class="d-flex gap-4 text-sm text-muted">
                                <span class="d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined fs-6">pets</span>
                                    {{ $record->pet?->PetName ?? 'N/A' }}
                                </span>
                                @if($record->service)
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined fs-6">medical_services</span>
                                        {{ $record->service->ServiceName }}
                                    </span>
                                @endif
                                <span class="d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined fs-6">person</span>
                                    {{ $record->staff?->FullName ?? 'Nhân viên' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <span class="material-symbols-outlined fs-1 text-muted">event_note</span>
                    <h5 class="fw-bold mt-3">Chưa có nhật ký nào</h5>
                    <p class="text-muted">Hãy tạo nhật ký đầu tiên của bạn!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Thêm nhật ký -->
<div class="modal fade" id="addJournalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm nhật ký chăm sóc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.journal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Thú cưng <span class="text-danger">*</span></label>
                        <select name="PetID" class="form-select" required>
                            <option value="">-- Chọn thú cưng --</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->PetID }}">{{ $pet->PetName }} ({{ $pet->Species }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="Title" class="form-control" placeholder="Ví dụ: Tắm xong rất ngoan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="Notes" class="form-control" rows="4" placeholder="Nhập ghi chú chi tiết về quá trình chăm sóc..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="Status" class="form-select">
                            <option value="">-- Không chọn --</option>
                            <option value="Đang thực hiện">Đang thực hiện</option>
                            <option value="Hoàn thành">Hoàn thành</option>
                            <option value="Lưu ý quan trọng">Lưu ý quan trọng</option>
                        </select>
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

