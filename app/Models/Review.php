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
        'orders_id', // Clave foránea
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
    public function productsServices()
    {
        return $this->belongsToMany(ProductService::class, 'reviews_products_services', 'review_id', 'product_service_id');
    }
}