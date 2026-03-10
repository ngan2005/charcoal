<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';
    protected $primaryKey = 'PetID';
    public $timestamps = false;

    protected $fillable = [
        'OwnerID',
        'PetName',
        'Species',
        'Breed',
        'Size',
        'Age',
        'Notes'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'OwnerID', 'UserID');
    }

    public function images()
    {
        return $this->hasMany(PetImage::class, 'PetID', 'PetID');
    }

    public function mainImage()
    {
        return $this->hasOne(PetImage::class, 'PetID', 'PetID')->where('IsMain', 1);
    }

    public function getImageUrlAttribute()
    {
        if ($this->mainImage && $this->mainImage->ImageUrl) {
            return str_starts_with($this->mainImage->ImageUrl, 'http') 
                ? $this->mainImage->ImageUrl 
                : asset('storage/' . $this->mainImage->ImageUrl);
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage && $firstImage->ImageUrl) {
            return str_starts_with($firstImage->ImageUrl, 'http') 
                ? $firstImage->ImageUrl 
                : asset('storage/' . $firstImage->ImageUrl);
        }
        
        return null;
    }
}
