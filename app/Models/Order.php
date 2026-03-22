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
}
