<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Welcome');
    }
    
    public function about()
    {
        return Inertia::render('About');
    }

    public function services()
    {
        return Inertia::render('Services');
    }

    public function contact()
{
    return Inertia::render('Contact');
}
}