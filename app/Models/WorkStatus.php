<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStatus extends Model
{
    use HasFactory;

    protected $table = 'work_status';
    protected $primaryKey = 'WorkStatusID';
    public $timestamps = false;

    protected $fillable = [
        'WorkStatusID',
        'WorkStatusName'
    ];
}

