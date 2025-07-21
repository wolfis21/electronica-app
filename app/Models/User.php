<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasFactory, Notifiable, HasRoles;

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

    // Relación con Role (un usuario tiene un rol)
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id'); // Asegúrate de que 'role_id' sea la FK en users
    }

    // Método para verificar si el usuario tiene un permiso
    public function hasPermissionTo($permissionName)
    {
        // Asume que el rol del usuario tiene una relación many-to-many con permisos
        // Asegúrate de que tu modelo Role tenga una relación 'permissions'
        // y que Permission tenga una relación 'roles' (many-to-many)
        if ($this->role) {
            return $this->role->permissions->contains('name', $permissionName);
        }
        return false;
    }

    // Método para verificar si el usuario tiene un rol específico
    public function hasRole($roleName)
    {
        if ($this->role) {
            return $this->role->name === $roleName;
        }
        return false;
    }
        // Un usuario puede ser responsable de muchas órdenes
    public function responsibleForOrders()
    {
        return $this->hasMany(Order::class, 'users_id'); // Usamos 'users_id' como clave foránea
    }

    public function orders(): HasMany
    {
        // El segundo argumento 'users_id' es la clave foránea en la tabla 'orders'
        return $this->hasMany(Order::class, 'users_id');
    }
}
