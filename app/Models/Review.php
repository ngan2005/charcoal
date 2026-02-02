<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'ReviewID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'ProductID',
        'ServiceID',
        'StaffID',
        'ParentReviewID',
        'Rating',
        'Comment',
        'Deleted',
        'CreatedAt'
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'Rating' => 'integer',
        'Deleted' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'CustomerID', 'UserID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }

    public function parent()
    {
        return $this->belongsTo(Review::class, 'ParentReviewID', 'ReviewID');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'ParentReviewID', 'ReviewID');
    }
}





