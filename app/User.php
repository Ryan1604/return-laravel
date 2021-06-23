<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_url',
        'role_id',
        'disabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function isAdmin()
    {
        return $this->role()->whereIn('id', [1])->exists();
    }

    public function isManager()
    {
        return $this->role()->whereIn('id', [2])->exists();
    }

    public function isUser()
    {
        return $this->role()->whereIn('id', [3])->exists();
    }

    public function isActiveSession()
    {
        return $this->session_id != session()->getId();
    }

    public function isActive()
    {
        return $this->disabled === '1';
    }
}
