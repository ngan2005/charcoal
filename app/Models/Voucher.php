<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';
    protected $primaryKey = 'VoucherID';

    public $timestamps = false;

    protected $fillable = [
        'Code',
        'DiscountPercent',
        'ExpiredAt',
        'Quantity',
        'IsActive',
        'CreatedAt',
        'Description',
        'MinOrderAmount',
        'MaxDiscountAmount',
    ];

    protected $casts = [
        'ExpiredAt' => 'datetime',
        'CreatedAt' => 'datetime',
        'IsActive' => 'boolean',
        'DiscountPercent' => 'integer',
        'Quantity' => 'integer',
        'MinOrderAmount' => 'decimal:2',
        'MaxDiscountAmount' => 'decimal:2',
    ];

    /**
     * Get the orders that use this voucher
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_vouchers', 'VoucherID', 'OrderID');
    }

    /**
     * Check if voucher is still valid
     */
    public function isValid()
    {
        return $this->IsActive 
            && $this->ExpiredAt->isFuture() 
            && $this->Quantity > 0;
    }

    /**
     * Get usage count
     */
    public function getUsedCountAttribute()
    {
        return $this->orders()->count();
    }

    /**
     * Get remaining quantity
     */
    public function getRemainingQuantityAttribute()
    {
        return max(0, $this->Quantity - $this->orders()->count());
    }
}

