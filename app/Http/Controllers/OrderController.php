<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use App\Models\order;
use App\Models\pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    // 🔹 Tampilkan form untuk Self Service
    public function selfservice()
    {
        $type_menu = 'order';
        $mesins = Mesin::where('status', 'Ready')->get();

        return view('pages.orders.self', compact('type_menu', 'mesins'));
    }

    // 🔹 Simpan data order Self Service
    public function storeSelfservice(Request $request)
    {
        $request->validate([
            'mesin_id' => 'required|exists:mesins,id',
            'tanggal_order' => 'required|date',
            'jam_order' => 'required',
            'durasi' => 'required|integer|min:1',
            'koin' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
            'total_biaya' => 'required|integer|min:0',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'service_type' => 'SelfService',
            'mesin_id' => $request->mesin_id,
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'durasi' => $request->durasi,
            'koin' => $request->koin,
            'catatan' => $request->catatan,
            'status' => 'Diproses',
            'total_biaya' => $request->total_biaya,
        ]);
        // Buat data pembayaran awal
        $pembayaran = Pembayaran::create([
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);
        return redirect()->route('pembayaran.edit', $pembayaran->id)
            ->with('success', 'Order Self Service berhasil dibuat. Silakan lengkapi pembayaran Anda.');
    }

    // 🔹 Tampilkan form untuk Drop Off
    public function dropoff()
    {
        $type_menu = 'order';
        return view('pages.orders.drop', compact('type_menu'));
    }

    // 🔹 Simpan data order Drop Off
    public function storeDropOff(Request $request)
    {
        $request->validate([
            'tanggal_order' => 'required|date',
            'jam_order' => 'required',
            'berat' => 'required|numeric|min:0.1',
            'detergent' => 'required|integer|min:0',
            'tanggal_ambil' => 'required|date|after_or_equal:tanggal_order',
            'catatan' => 'nullable|string',
            'total_biaya' => 'required|integer|min:0',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'service_type' => 'DropOff',
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'berat' => $request->berat,
            'detergent' => $request->detergent,
            'tangal_ambil' => $request->tanggal_ambil,
            'catatan' => $request->catatan,
            'status' => 'Diproses',
            'total_biaya' => $request->total_biaya,
        ]);
        // Buat data pembayaran awal
        $pembayaran = Pembayaran::create([
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);
        return redirect()->route('pembayaran.edit', $pembayaran->id)
            ->with('success', 'Order Self Service berhasil dibuat. Silakan lengkapi pembayaran Anda.');
    }
}
