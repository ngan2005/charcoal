<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $orders = Order::with('user', 'payment')
            ->when($status, function($query) use ($status) {
                return $query->where('Status', $status);
            })
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('OrderID', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q2) use ($search) {
                          $q2->where('FullName', 'like', "%{$search}%")
                             ->orWhere('Email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('CreatedAt', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('CreatedAt', '<=', $dateTo);
            })
            ->orderBy('CreatedAt', 'desc')
            ->paginate(15);

        $orderStatuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
        ];

        return view('admin.orders.index', compact('orders', 'orderStatuses'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'payment'])
            ->where('OrderID', $id)
            ->firstOrFail();

        $orderDetails = OrderDetail::with(['product', 'service', 'pet'])
            ->where('OrderID', $id)
            ->get();

        // Tính tổng tiền từ chi tiết
        $calculatedTotal = $orderDetails->sum(function($item) {
            return $item->Quantity * $item->UnitPrice;
        });

        $paymentStatuses = PaymentStatus::all();

        return view('admin.orders.show', compact('order', 'orderDetails', 'calculatedTotal', 'paymentStatuses'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'Status' => 'required|string',
        ]);

        Order::where('OrderID', $id)->update([
            'Status' => $request->Status,
        ]);

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'PaymentStatusID' => 'required|exists:payment_status,StatusID',
        ]);

        $payment = Payment::where('OrderID', $id)->first();

        if ($payment) {
            Payment::where('OrderID', $id)->update([
                'StatusID' => $request->PaymentStatusID,
            ]);
        } else {
            // Tạo payment mới nếu chưa có
            Payment::create([
                'OrderID' => $id,
                'StatusID' => $request->PaymentStatusID,
                'PaidAt' => now(),
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Cập nhật thanh toán thành công!');
    }
}












