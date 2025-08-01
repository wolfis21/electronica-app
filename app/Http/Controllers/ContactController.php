<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Enviar el correo electrónico
        Mail::to('infobermutech@gmail.com')->send(new ContactFormSubmitted($validatedData));

        // 3. Redirigir de vuelta con un mensaje de éxito
        return back()->with('success', '¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.');
    }
}