<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffShift extends Model
{
    use HasFactory;

    protected $table = 'staff_shifts';
    protected $primaryKey = 'ShiftID';
    public $timestamps = false;

    protected $fillable = [
        'StaffID',
        'StartTime',
        'EndTime',
        'ShiftStatusID',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }

    public function shiftStatus()
    {
        return $this->belongsTo(ShiftStatus::class, 'ShiftStatusID', 'ShiftStatusID');
    }
}








