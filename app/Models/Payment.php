<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_date',
        'amount',
        'currency',
        'payment_method',
        'reference_number',
        'note',
        'status',
        'orders_id', // Clave foránea
    ];

    protected $casts = [
        'payment_date' => 'datetime', // Asegura que se convierta a un objeto Carbon
        'amount' => 'decimal:2',
    ];

    // Relación: Un pago pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}