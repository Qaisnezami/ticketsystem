<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserve;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([UserObserve::class])]
class User extends Authenticatable implements HasAvatar
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }

    public function hasPermission($permission)
    {
       $roles = $this->roles;
       foreach ($roles as $role) {
           $permissions = $role->permissions;
           if ($permissions->contains('title',$permission)) {
               return true;
           }else
            {
                return false;
            }
       }
    }

    public function hasRole($role)
    {
       return $this->roles->contains('title',$role);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return asset($this->profile->image);
    }


    
}
