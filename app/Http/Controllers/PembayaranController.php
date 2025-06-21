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
            'metode_pembayaran' => 'required',
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
        // Buat no_pembayaran
        $tanggal = date('Ymd');
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        //masukan data kedalam tabel users
        pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'order_id' => $validatedData['order_id'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'jumlah_dibayar' => $validatedData['jumlah_bayar'],
            'bukti_bayar' => $imagePath,
            'status' => 'Proses Pembayaran'
        ]);

        //jika proses berhsil arahkan kembali ke halaman users dengan status success
        return Redirect::route('riwayat.index')->with('success', 'Pembayaran dengan No Pembayaran '. $no_pembayaran . ' berhasil ditambah.');
    }
    public function edit($id)
    {
        $type_menu = 'riwayat';
        $pembayaran = Pembayaran::with('order.user')->findOrFail($id);
        return view('pages.pembayaran.edit', compact('pembayaran', 'type_menu'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        // Validasi form
        $request->validate([
            'metode_pembayaran' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Update data pembayaran
        $pembayaran->update([
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // Cek jika ada upload bukti bayar baru
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/bukti_bayar/'), $filename);

            // Hapus file lama jika ada
            if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
                unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
            }

            // Simpan nama file baru
            $pembayaran->update([
                'bukti_bayar' => $filename,
                'status' => 'Proses Pembayaran'
            ]);
        }

        return Redirect::route('riwayat.index', $pembayaran->order_id)->with('success ', 'Pembayaran'.$pembayaran->no_pembayaran. ' berhasil diperbarui.');
    }
}
