<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'ServiceID';
    public $timestamps = false; // Based on migration, no timestamps column

    protected $fillable = [
        'ServiceName',
        'BasePrice',
        'MemberPrice',
        'Duration',
        'Description'
    ];

    public function images()
    {
        return $this->hasMany(ServiceImage::class, 'ServiceID', 'ServiceID');
    }
}
