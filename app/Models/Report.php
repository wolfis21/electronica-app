<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'orders_id', // Clave foránea
    ];

    // Relación: Un reporte pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}