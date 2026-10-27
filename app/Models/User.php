<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Impersonate, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'initials',
        'email',
        'is_active',
        'password',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'tanggal_sk_notaris' => 'date',
        'tanggal_sk_ppat' => 'date',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'owner_id');
    }

    public function salesActivities()
    {
        return $this->hasMany(SalesActivity::class, 'user_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'assigned_to');
    }

    public function customRoles()
    {
        return $this->belongsToMany(CustomRole::class, 'model_has_roles', 'model_id', 'role_id')
            ->where('model_type', User::class);
    }

    protected $dates = ['deleted_at'];

    public function canImpersonate()
    {
        return $this->can('impersonate user');
    }

    public function canBeImpersonated()
    {
        return true;
    }
}
