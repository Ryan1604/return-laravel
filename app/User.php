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
        'sn',
        'name',
        'email',
        'username',
        'phone_number',
        'dob',
        'address',
        'password',
        'profile_url',
        'role_id',
        'faculty_id',
        'gender',
        'disabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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

    public function role() {
        return $this->belongsTo('App\Models\Role');
    }

    public function faculty() {
        return $this->belongsTo('App\Models\Faculty');
    }

    public function isAdmin() {
        return $this->role()->whereIn('id', [1, 2])->exists();
    }

    public function isLibrarian() {
        return $this->role_id === 1;
    }

    public function isActiveSession() {
        return $this->session_id != session()->getId();
    }

    public function isActive() {
        return $this->disabled === '1';
    }
}
