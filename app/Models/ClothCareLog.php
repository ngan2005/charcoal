<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothCareLog extends Model
{
    use HasFactory;

    protected $table = 'cloth_care_logs';
    protected $primaryKey = 'LogID';

    protected $fillable = [
        'OrderID',
        'StaffID',
        'ItemName',
        'ItemType',
        'Condition',
        'ServiceName',
        'Status',
        'BeforeNotes',
        'AfterNotes',
        'StaffNotes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }
}
