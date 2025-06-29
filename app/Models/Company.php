<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    // Opcional: Especificar columnas que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'address',
    ];
}
