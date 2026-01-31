<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    use HasFactory;

    protected $table = 'service_images';
    protected $primaryKey = 'ImageID';
    public $timestamps = false;

    protected $fillable = ['ServiceID', 'ImageUrl'];
}
