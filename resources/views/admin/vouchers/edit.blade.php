@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-label {
            font-weight: 500;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
        }
        .input-group .form-control {
            border-left: 0;
        }
        .input-group .form-control:focus {
            border-color: #ced4da;
            box-shadow: none;
        }
        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-radius: 0.25rem;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #86b7fe;
        }
        .voucher-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .voucher-preview::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }
        .voucher-preview::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary me-3">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="h3 fw-bold text-gray-900">Chỉnh sửa mã giảm giá</h1>
                <p class="text-muted small mb-0">Cập nhật thông tin mã khuyến mãi</p>
            </div>
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vouchers.update', $voucher->VoucherID) }}" id="voucherForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Mã giảm giá -->
                        <div class="col-md-6">
                            <label class="form-label required">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="Code" 
                                       class="form-control @error('Code') is-invalid @enderror" 
                                       value="{{ old('Code', $voucher->Code) }}"
                                       placeholder="Nhập mã giảm giá"
                                       style="text-transform: uppercase;"
                                       required>
                                <button type="button" class="btn btn-outline-secondary input-group-text" id="generateCode">
                                    <span class="material-symbols-outlined">autorenew</span>
                                </button>
                            </div>
                            @error('Code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="form-text">Mã giảm giá phải duy nhất, không dấu và không chứa khoảng trắng.</div>
                            @endif
                        </div>

                        <!-- Phần trăm giảm giá -->
                        <div class="col-md-6">
                            <label class="form-label required">Phần trăm giảm giá (%)</label>
                            <div class="input-group">
                                <input type="number" 
                                       name="DiscountPercent" 
                                       class="form-control @error('DiscountPercent') is-invalid @enderror" 
                                       value="{{ old('DiscountPercent', $voucher->DiscountPercent) }}"
                                       min="1" 
                                       max="100"
                                       placeholder="0"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('DiscountPercent')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @endif
                        </div>

                        <!-- Số lượng -->
                        <div class="col-md-6">
                            <label class="form-label required">Số lượng mã</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </span>
                                <input type="number" 
                                       name="Quantity" 
                                       class="form-control @error('Quantity') is-invalid @enderror" 
                                       value="{{ old('Quantity', $voucher->Quantity) }}"
                                       min="1"
                                       placeholder="Nhập số lượng"
                                       required>
                            </div>
                            @error('Quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="form-text">Số lượng mã có thể sử dụng.</div>
                            @endif
                        </div>

                        <!-- Ngày hết hạn -->
                        <div class="col-md-6">
                            <label class="form-label required">Ngày hết hạn</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="material-symbols-outlined">event</span>
                                </span>
                                <input type="datetime-local" 
                                       name="ExpiredAt" 
                                       class="form-control @error('ExpiredAt') is-invalid @enderror" 
                                       value="{{ old('ExpiredAt', $voucher->ExpiredAt->format('Y-m-d\TH:i')) }}"
                                       required>
                            </div>
                            @error('ExpiredAt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="form-text">Sau thời điểm này, mã giảm giá sẽ tự động hết hạn.</div>
                            @endif
                        </div>

                        <!-- Mô tả -->
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="Description" 
                                      class="form-control @error('Description') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Nhập mô tả cho mã giảm giá (không bắt buộc)">{{ old('Description', $voucher->Description) }}</textarea>
                            @error('Description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @endif
                        </div>

                        <!-- Điều kiện áp dụng -->
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-start">
                                <span class="material-symbols-outlined me-2 mt-1">info</span>
                                <div>
                                    <strong>Điều kiện áp dụng (tùy chọn)</strong>
                                    <p class="mb-0 small">Thiết lập mức giảm giá tối thiểu và tối đa để tránh lỗ hổng khuyến mãi.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Giá trị đơn hàng tối thiểu -->
                        <div class="col-md-6">
                            <label class="form-label">Giá trị đơn hàng tối thiểu</label>
                            <div class="input-group">
                                <span class="input-group-text">₫</span>
                                <input type="number" 
                                       name="MinOrderAmount" 
                                       class="form-control @error('MinOrderAmount') is-invalid @else @endif" 
                                       value="{{ old('MinOrderAmount', $voucher->MinOrderAmount) }}"
                                       min="0"
                                       step="1000"
                                       placeholder="0">
                            </div>
                            @error('MinOrderAmount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="form-text">Để trống nếu không giới hạn.</div>
                            @endif
                        </div>

                        <!-- Giảm giá tối đa -->
                        <div class="col-md-6">
                            <label class="form-label">Giảm giá tối đa</label>
                            <div class="input-group">
                                <span class="input-group-text">₫</span>
                                <input type="number" 
                                       name="MaxDiscountAmount" 
                                       class="form-control @error('MaxDiscountAmount') is-invalid @else @endif" 
                                       value="{{ old('MaxDiscountAmount', $voucher->MaxDiscountAmount) }}"
                                       min="0"
                                       step="1000"
                                       placeholder="0">
                            </div>
                            @error('MaxDiscountAmount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="form-text">Để trống nếu không giới hạn.</div>
                            @endif
                        </div>

                        <!-- Preview -->
                        <div class="col-12">
                            <label class="form-label">Xem trước</label>
                            <div class="voucher-preview">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 small opacity-75">Mã giảm giá</p>
                                        <h3 class="mb-0 fw-bold">{{ $voucher->Code }}</h3>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0 small opacity-75">Giảm giá</p>
                                        <h2 class="mb-0 fw-bold">{{ $voucher->DiscountPercent }}%</h2>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-between small">
                                    <span>HSD: {{ $voucher->ExpiredAt->format('d/m/Y H:i') }}</span>
                                    <span>Còn lại: {{ $voucher->Quantity - $voucher->orders()->count() }} mã</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                                <span class="material-symbols-outlined me-1">close</span>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="material-symbols-outlined me-1">save</span>
                                Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate random code
            document.getElementById('generateCode').addEventListener('click', function() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let code = '';
                for (let i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.querySelector('input[name="Code"]').value = code;
            });

            // Form validation
            document.getElementById('voucherForm').addEventListener('submit', function(e) {
                const code = document.querySelector('input[name="Code"]').value.trim();
                const discount = document.querySelector('input[name="DiscountPercent"]').value;
                const quantity = document.querySelector('input[name="Quantity"]').value;
                const expiredAt = document.querySelector('input[name="ExpiredAt"]').value;

                if (!code) {
                    e.preventDefault();
                    alert('Vui lòng nhập mã giảm giá!');
                    return;
                }

                if (discount < 1 || discount > 100) {
                    e.preventDefault();
                    alert('Phần trăm giảm giá phải từ 1% đến 100%!');
                    return;
                }

                if (quantity < 1) {
                    e.preventDefault();
                    alert('Số lượng phải lớn hơn 0!');
                    return;
                }

                if (!expiredAt) {
                    e.preventDefault();
                    alert('Vui lòng chọn ngày hết hạn!');
                    return;
                }
            });
        });
    </script>
@endpush

