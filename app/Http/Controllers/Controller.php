<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Esto es CLAVE
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // Alias para evitar conflicto de nombres

class Controller extends BaseController // Extiende de BaseController
{
    use AuthorizesRequests, ValidatesRequests; // Aquí se incluye AuthorizesRequests
}