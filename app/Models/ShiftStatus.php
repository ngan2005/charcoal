<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftStatus extends Model
{
    use HasFactory;

    protected $table = 'shift_status';
    protected $primaryKey = 'ShiftStatusID';
    public $timestamps = false;

    protected $fillable = [
        'ShiftStatusID',
        'ShiftStatusName'
    ];
}












