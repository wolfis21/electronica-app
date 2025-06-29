<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employees_id', // Añadido
        'role_id',      // Añadido
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

        // Relación con Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    // Relación con Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Método para verificar si el usuario tiene un permiso
    public function hasPermissionTo($permissionName)
    {
        // Asume que el rol del usuario tiene una relación many-to-many con permisos
        return $this->role->permissions->contains('name', $permissionName);
    }

    // Método para verificar si el usuario tiene un rol específico
    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }
}
