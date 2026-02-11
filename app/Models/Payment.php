<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'PaymentID';
    public $timestamps = false;

    protected $fillable = [
        'OrderID',
        'Method',
        'StatusID',
        'PaidAt'
    ];

    protected $casts = [
        'PaidAt' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }

    public function status()
    {
        return $this->belongsTo(PaymentStatus::class, 'StatusID', 'StatusID');
    }
}












