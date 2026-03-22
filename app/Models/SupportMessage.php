<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $table = 'support_messages';
    protected $primaryKey = 'MessageID';
    public $timestamps = true;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'UserID',
        'SessionID',
        'Message',
        'IsFromAdmin',
        'ReadAt',
    ];

    protected $casts = [
        'IsFromAdmin' => 'boolean',
        'ReadAt' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}
