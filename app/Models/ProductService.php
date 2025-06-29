<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductService extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla si no sigue la convención (plural de snake_case)
    protected $table = 'products_services'; // Importante si usaste 'products-services' en la migración y la renombraste

    protected $fillable = [
        'name',
        'price_internal', // Usar snake_case para Laravel
        'price_external', // Usar snake_case para Laravel
        'suppliers',
    ];

    // Asegúrate de que estos atributos se "casteen" a los tipos correctos si no son strings
    protected $casts = [
        'price_internal' => 'decimal:2',
        'price_external' => 'decimal:2',
    ];

    // Relación: Muchos productos/servicios pueden estar en muchas revisiones
    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'reviews_products_services', 'product_service_id', 'review_id');
    }
}
