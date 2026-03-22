<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'CartItemID';

    public $timestamps = false;

    protected $fillable = [
        'CartID',
        'ProductID',
        'ServiceID',
        'Quantity',
        'AddedAt',
    ];

    protected $casts = [
        'AddedAt' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'CartID', 'CartID');
    }
}
