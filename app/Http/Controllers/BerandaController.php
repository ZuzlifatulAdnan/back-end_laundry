<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use App\Models\order;
use App\Models\pembayaran;
use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $type_menu = 'beranda';
        $totalUsers = User::count();
        $totalOrders = order::count();
        $totalPembayaran = pembayaran::count();
        $totalMesin = mesin::count();
        $mesinReady = mesin::where('status', 'Ready')->get();
        return view('pages.beranda.index', compact(
            'type_menu',
            'mesinReady',
            'totalUsers',
            'totalOrders',
            'totalPembayaran',
            'totalMesin'
        ));
    }
}