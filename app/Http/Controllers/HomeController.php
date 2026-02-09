<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = \App\Models\Service::where('IsActive', 1)->get();
        $pets = [];
        
        if (auth()->check()) {
            $pets = \App\Models\Pet::where('OwnerID', auth()->id())->get();
        }

        return view('welcome', compact('services', 'pets'));
    }
}
