<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'CommentID';
    public $timestamps = true;

    protected $fillable = [
        'ProductID',
        'UserID',
        'Content',
        'Rating',
        'Status',
    ];

    protected $casts = [
        'Status' => 'boolean',
        'Rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
