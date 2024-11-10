<?php

namespace App\Models;

use App\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait;

    const REDIRECT_TO = 'admin/dashboard';
    const GENDER_MALE = 'Male';
    const GENDER_FEMALE = 'Female';
    const GENDER_OTHER = 'Other';
    const GENDER_ARR = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
        self::GENDER_OTHER,
    ];

    const TYPE_USER = 0;
    const TYPE_SUPER_ADMIN = 1;
    const STATUSACTIVE = 1;
    const STATUSINACTIVE = 0;
    const STATUSARR = [0 => 'Inactive', 1 => 'Active'];

    protected $casts = [
        'hobbies' => 'array',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'first_name',
        'last_name',
        'contact_number',
        'postcode',
        'gender',
        'hobbies',
        'is_super_admin',
        'status',
        'password',
        'state_id',
        'city_id'
    ];

    public function uploads()
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    public function profile()
    {
        return $this->morphOne(Upload::class, 'uploadable')->where('file_usage', 'profile');
    }

    public function documents()
    {
        return $this->morphMany(Upload::class, 'uploadable')->where('file_usage', 'document');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function scopeUserRole($query, $withActive = false)
    {
        return $query->whereNot('is_super_admin', true);
    }

    public function getFullNameAttribute()
    {
        return  $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }


    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
}
