<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetImage extends Model
{
    use HasFactory;

    protected $table = 'pet_images';
    protected $primaryKey = 'ImageID';
    public $timestamps = false;

    protected $fillable = [
        'PetID',
        'ImageUrl',
        'IsMain'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'PetID', 'PetID');
    }
}
