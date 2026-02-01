<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'FullName',
        'Email',
        'Password',
        'Phone',
        'Address',
        'Avatar',
        'RoleID',
        'IsActive',
        'LastLogin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Password' => 'hashed',
        'LastLogin' => 'datetime',
        'CreatedAt' => 'datetime',
    ];

    // Override to support custom Password column
    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the pets owned by this user
     */
    public function pets()
    {
        return $this->hasMany(Pet::class, 'OwnerID', 'UserID');
    }

    /**
     * Get the staff profile for this user (if staff)
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class, 'UserID', 'UserID');
    }
}
