<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffService extends Model
{
    use HasFactory;

    protected $table = 'staff_services';
    protected $primaryKey = 'StaffServiceID';
    public $timestamps = false;

    protected $fillable = [
        'StaffID',
        'ServiceID',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }
}
