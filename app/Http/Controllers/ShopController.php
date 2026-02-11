<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the shop page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('shop');
    }
}


