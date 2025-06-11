<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $type_menu = 'beranda';
        
        return view('pages.beranda.index', compact(
            'type_menu',
        ));
    }
}