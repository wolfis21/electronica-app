<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Un rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'role_id'); // Asegúrate que 'role_id' es la FK en users
    }

    // Opcional: Definir relación con permisos
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }
}
