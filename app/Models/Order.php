<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_equip',
        'serial',
        'description',
        'accessories',
        'extra_notes',
        'status',
        'customers_id', // Clave foránea
        'users_id',     // Clave foránea
    ];

    // Relación: Una orden pertenece a un cliente
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    // Relación: Una orden fue gestionada por un usuario (empleado)
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relación: Una orden puede tener muchas revisiones
    public function reviews()
    {
        return $this->hasMany(Review::class, 'orders_id');
    }

    // Relación: Una orden puede tener muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class, 'orders_id');
    }

    // Relación: Una orden puede tener muchos reportes
    public function reports()
    {
        return $this->hasMany(Report::class, 'orders_id');
    }
}