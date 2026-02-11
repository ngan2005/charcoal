<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    protected $table = 'staff_profiles';
    protected $primaryKey = 'UserID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'Position',
        'ExperienceYears',
        'WorkStatusID',
        'Rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function workStatus()
    {
        return $this->belongsTo(WorkStatus::class, 'WorkStatusID', 'WorkStatusID');
    }
}












