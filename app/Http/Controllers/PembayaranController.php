<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PembayaranController extends Controller
{
    public function create(Request $request)
    {
        $type_menu = 'order';
        $order_id = $request->query('order_id');
        $order = Order::with('user')->findOrFail($order_id);
        return view('pages.pembayaran.create', compact('type_menu', 'order'));
    }

    public function store(Request $request)
    {
        // validasi data dari form tambah user
        $validatedData = $request->validate([
            'order_id' => 'required',
            'metode_pembayaran' => 'required|min:8',
            'jumlah_bayar' => 'required',
            'bukti_bayar' => 'required|mimes:jpg,jpeg,png,gif',
        ]);
        // Handle the image upload if present
        $imagePath = null;
        if ($request->hasFile('bukti_bayar')) {
            $image = $request->file('bukti_bayar');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/bukti_bayar/', $imagePath);
        }
        //masukan data kedalam tabel users
        pembayaran::create([
            'order_id' => $validatedData['order_id'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'jumlah_dibayar' => $validatedData['jumlah_bayar'],
            'bukti_bayar' => $imagePath, 
            'status' => 'Diproses'
        ]);

        //jika proses berhsil arahkan kembali ke halaman users dengan status success
        return Redirect::route('riwayatOrder.index')->with('success', 'User berhasil di tambah.');
    }
}
