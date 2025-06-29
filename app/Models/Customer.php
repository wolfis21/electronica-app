<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'dni',
        'phone',
        'email',
        'address',
    ];

    // Relación: Un cliente puede tener muchas órdenes
    public function orders()
    {
        return $this->hasMany(Order::class, 'customers_id');
    }
}
