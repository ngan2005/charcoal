<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'OrderID';
    
    // CreatedAt is in schema, but UpdatedAt is missing used by default timestamps.
    // Schema has 'CreatedAt'->useCurrent(). 
    public $timestamps = false; 

    protected $fillable = [
        'UserID',
        'OrderCode',
        'TotalAmount',
        'ShippingName',
        'ShippingPhone',
        'ShippingAddress',
        'PaymentMethod',
        'PaymentStatus',
        'VoucherID',
        'DiscountAmount',
        'Status',
        'CreatedAt',
        'UpdatedAt',
    ];

    protected $casts = [
        'TotalAmount' => 'decimal:2',
        'DiscountAmount' => 'decimal:2',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'OrderID', 'OrderID');
    }

    /**
     * Get the payment for this order
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'OrderID', 'OrderID');
    }

    /**
     * Get the vouchers applied to this order
     */
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'order_vouchers', 'OrderID', 'VoucherID');
    }

    /**
     * Lịch hẹn dịch vụ tạo từ đơn hàng (phân công nhân viên)
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'OrderID', 'OrderID');
    }

    /**
     * Nhãn phương thức thanh toán (cột orders.PaymentMethod hoặc payments.Method).
     */
    public function paymentMethodLabel(): string
    {
        $key = $this->PaymentMethod ?? $this->payment?->Method;

        return match ($key) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'vnpay' => 'VNPay',
            null, '' => '--',
            default => (string) $key,
        };
    }

    /**
     * Badge thanh toán cho admin: [class bootstrap không tiền tố bg-, text hiển thị].
     */
    public function adminPaymentStatusDisplay(): array
    {
        if ($this->payment) {
            return match ((int) $this->payment->StatusID) {
                1 => ['warning', 'Chưa thanh toán'],
                2 => ['info', 'Đang xử lý'],
                3 => ['success', 'Đã thanh toán'],
                4 => ['danger', 'Thất bại'],
                5 => ['dark', 'Đã hoàn tiền'],
                6 => ['secondary', 'Bị hủy'],
                7 => ['secondary', 'Hết hạn'],
                default => ['secondary', 'Không xác định'],
            };
        }

        return match ($this->PaymentStatus ?? '') {
            'paid' => ['success', 'Đã thanh toán'],
            'failed' => ['danger', 'Thất bại'],
            'unpaid' => ['warning', 'Chưa thanh toán'],
            default => ['secondary', 'Chưa có'],
        };
    }
}
