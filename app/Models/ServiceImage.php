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

    protected $fillable = ['ServiceID', 'ImageUrl', 'IsMain'];

    /** URL đầy đủ để hiển thị ảnh (dùng trong view) */
    public function getDisplayUrlAttribute(): string
    {
        $url = $this->ImageUrl ?? '';
        if ($url === '') {
            return 'https://placehold.co/80/F4C2C3/fff?text=Anh';
        }
        if (str_starts_with($url, 'http')) {
            return $url;
        }
        if (str_contains($url, '/storage/')) {
            $url = substr($url, strpos($url, '/storage/') + 9);
        }
        return asset('storage/' . $url);
    }
}
