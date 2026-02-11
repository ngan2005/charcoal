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
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Đơn hàng #{{ $order->OrderID }}</h1>
                    <p class="text-sm text-gray-500">Ngày đặt: {{ $order->CreatedAt->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @switch($order->Status)
                    @case('pending')
                        <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=confirmed" class="btn btn-success">
                            <span class="material-symbols-outlined me-1 align-middle">check</span>
                            Xác nhận
                        </a>
                        @break
                    @case('confirmed')
                        <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=processing" class="btn btn-primary">
                            <span class="material-symbols-outlined me-1 align-middle">sync</span>
                            Bắt đầu xử lý
                        </a>
                        @break
                    @case('processing')
                        <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=shipping" class="btn btn-info">
                            <span class="material-symbols-outlined me-1 align-middle">local_shipping</span>
                            Giao hàng
                        </a>
                        @break
                    @case('shipping')
                        <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=delivered" class="btn btn-success">
                            <span class="material-symbols-outlined me-1 align-middle">check_circle</span>
                            Đã giao
                        </a>
                        @break
                    @case('delivered')
                        <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=completed" class="btn btn-success">
                            <span class="material-symbols-outlined me-1 align-middle">task_alt</span>
                            Hoàn thành
                        </a>
                        @break
                @endswitch
                @if(!in_array($order->Status, ['cancelled', 'completed', 'refunded']))
                    <a href="{{ route('admin.orders.update-status', $order->OrderID) }}?Status=cancelled" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                        <span class="material-symbols-outlined me-1 align-middle">cancel</span>
                        Hủy đơn
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Order Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Thông tin đơn hàng
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Mã đơn</span>
                            <span class="fw-bold">#{{ $order->OrderID }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Ngày đặt</span>
                            <span>{{ $order->CreatedAt->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Trạng thái</span>
                            @switch($order->Status)
                                @case('pending')
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info">Đã xác nhận</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-primary">Đang xử lý</span>
                                    @break
                                @case('shipping')
                                    <span class="badge bg-secondary">Đang giao</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success">Đã giao</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Hoàn thành</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-dark">Hoàn tiền</span>
                                    @break
                            @endswitch
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Thanh toán</span>
                            @if($order->payment)
                                @switch($order->payment->StatusID)
                                    @case(1)
                                        <span class="badge bg-warning">Chưa thanh toán</span>
                                        @break
                                    @case(2)
                                        <span class="badge bg-info">Đang xử lý</span>
                                        @break
                                    @case(3)
                                        <span class="badge bg-success">Đã thanh toán</span>
                                        @break
                                    @case(4)
                                        <span class="badge bg-danger">Thất bại</span>
                                        @break
                                    @case(5)
                                        <span class="badge bg-dark">Đã hoàn tiền</span>
                                        @break
                                    @case(6)
                                        <span class="badge bg-secondary">Bị hủy</span>
                                        @break
                                    @case(7)
                                        <span class="badge bg-secondary">Hết hạn</span>
                                        @break
                                @endswitch
                            @else
                                <span class="badge bg-secondary">Chưa có</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Phương thức</span>
                            <span>{{ $order->payment?->Method ?? '--' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tổng tiền</span>
                            <span class="fw-bold text-success fs-5">{{ number_format($order->TotalAmount, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Thông tin khách hàng
                    </h5>
                    @if($order->user)
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-primary" style="font-size: 24px;">person</span>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $order->user->FullName }}</div>
                                    <small class="text-muted">{{ $order->user->Email }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Điện thoại</span>
                                <span>{{ $order->user->Phone ?? '--' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Địa chỉ</span>
                                <span class="text-end" style="max-width: 200px;">{{ $order->user->Address ?? '--' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Không có thông tin khách hàng</p>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">update</span>
                        Cập nhật trạng thái
                    </h5>
                    <form action="{{ route('admin.orders.update-status', $order->OrderID) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <select name="Status" class="form-select">
                            <option value="pending" @selected($order->Status === 'pending')>Chờ xác nhận</option>
                            <option value="confirmed" @selected($order->Status === 'confirmed')>Đã xác nhận</option>
                            <option value="processing" @selected($order->Status === 'processing')>Đang xử lý</option>
                            <option value="shipping" @selected($order->Status === 'shipping')>Đang giao hàng</option>
                            <option value="delivered" @selected($order->Status === 'delivered')>Đã giao hàng</option>
                            <option value="completed" @selected($order->Status === 'completed')>Hoàn thành</option>
                            <option value="cancelled" @selected($order->Status === 'cancelled')>Đã hủy</option>
                            <option value="refunded" @selected($order->Status === 'refunded')>Hoàn tiền</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <span class="material-symbols-outlined">save</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary">shopping_cart</span>
                    Sản phẩm/Dịch vụ trong đơn
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600">STT</th>
                        <th class="px-4 py-3 text-left text-gray-600">Sản phẩm/Dịch vụ</th>
                        <th class="px-4 py-3 text-left text-gray-600">Thú cưng</th>
                        <th class="px-4 py-3 text-center text-gray-600">Số lượng</th>
                        <th class="px-4 py-3 text-right text-gray-600">Đơn giá</th>
                        <th class="px-4 py-3 text-right text-gray-600">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orderDetails as $index => $item)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                @if($item->product)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-symbols-outlined text-success">inventory_2</span>
                                        <span>{{ $item->product->ProductName }}</span>
                                    </div>
                                @elseif($item->service)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-symbols-outlined text-info">content_cut</span>
                                        <span>{{ $item->service->ServiceName }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($item->pet)
                                    <span class="badge bg-primary">{{ $item->pet->PetName }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">{{ $item->Quantity }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($item->UnitPrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3 text-right fw-bold">{{ number_format($item->Quantity * $item->UnitPrice, 0, ',', '.') }} đ</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Không có sản phẩm nào
                            </td>
                        </tr>
                    @endforelse
                    <tr class="bg-light">
                        <td colspan="5" class="px-4 py-3 text-end fw-bold">Tổng tiền</td>
                        <td class="px-4 py-3 text-end fw-bold text-success fs-5">{{ number_format($calculatedTotal, 0, ',', '.') }} đ</td>
                    </tr>
                </tbody>
            </table>
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












