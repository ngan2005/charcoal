<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $primaryKey = 'OrderDetailID';
    public $timestamps = false;

    protected $fillable = [
        'OrderID',
        'ProductID',
        'Quantity',
        'ServiceID',
        'PetID',
        'UnitPrice'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
