<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'dni',
        'phone',
        'email',
        'address',
        'name_company',
    ];

    // Relación: Un cliente puede tener muchas órdenes
    public function orders()
    {
        return $this->hasMany(Order::class, 'customers_id');
    }

    // Relación: Un cliente puede tener muchos pagos
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Order::class, 'customers_id', 'orders_id');
    }
}
