<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'description_tec',
        'budget',
        'orders_id',
        'user_id', // Clave foránea
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    // Relación: Una revisión pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    // Relación: Muchas revisiones pueden usar muchos productos/servicios
    public function products() // Cambiado a 'products' para que coincida con el modelo Product
    {
        return $this->belongsToMany(Product::class, 'product_review', 'review_id', 'product_id')
                    ->withPivot('quantity', 'price_at_time_of_review'); // Añadir campos extra para el pivote
    }
        /**
     * Obtiene el usuario responsable de la revisión.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}