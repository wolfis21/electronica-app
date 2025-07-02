<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code', // Nuevo campo
        'description',
        'price', // Ahora nullable en la BD
        'price_sale', // Nuevo campo
        'is_service',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',      // Mantiene el cast a decimal
        'price_sale' => 'decimal:2', // Nuevo cast a decimal
        'is_service' => 'boolean',
    ];

        // Relación inversa para la tabla pivote product_review
    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'product_review', 'product_id', 'review_id')
                    ->withPivot('quantity', 'price_at_time_of_review');
    }
}