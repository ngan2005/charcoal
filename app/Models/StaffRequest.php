<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffRequest extends Model
{
    use HasFactory;

    protected $table = 'staff_requests';
    protected $primaryKey = 'RequestID';
    public $timestamps = false;

    protected $fillable = [
        'FullName',
        'Email',
        'Phone',
        'Address',
        'Position',
        'ReasonForApplication',
        'Status',
        'RejectReason',
        'ApprovedByUserID',
        'ApprovedAt'
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
        'ApprovedAt' => 'datetime',
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'ApprovedByUserID', 'UserID');
    }
}
