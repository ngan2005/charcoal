<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductCode',
        'ProductName',
        'CategoryID',
        'Price',
        'Weight',
        'Size',
        'Stock',
        'StatusID',
        'Description',
        'PurchaseCount'
    ];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'ProductID', 'ProductID');
    }

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'StatusID', 'StatusID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'ProductID', 'ProductID');
    }
}
