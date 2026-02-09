<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetCareRecord extends Model
{
    use HasFactory;

    protected $table = 'pet_care_records';
    protected $primaryKey = 'RecordID';

    protected $fillable = [
        'PetID',
        'ServiceID',
        'StaffID',
        'Title',
        'Notes',
        'Status',
        'StartDate',
        'EndDate',
    ];

    protected $casts = [
        'StartDate' => 'datetime',
        'EndDate' => 'datetime',
    ];

    // Quan hệ với Pet
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'PetID', 'PetID');
    }

    // Quan hệ với Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }

    // Quan hệ với Staff (User)
    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }
}
