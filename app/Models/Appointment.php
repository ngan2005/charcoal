<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'AppointmentID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'StaffID',
        'PetID',
        'AppointmentTime',
        'Status'
    ];

    protected $casts = [
        'AppointmentTime' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'CustomerID', 'UserID');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'PetID', 'PetID');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'AppointmentID', 'ServiceID');
    }
}
