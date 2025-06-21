<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\pembayaran;
use Illuminate\Http\Request;

class KelolaPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'kelolaPembayaran';

        $keyword = trim($request->input('search'));
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $pembayarans = Pembayaran::with(['order.user'])
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('order.user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', '%' . $keyword . '%');
                    })->orWhere('no_pembayaran', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereHas('order', function ($q) use ($bulan) {
                    $q->whereMonth('tanggal_order', $bulan);
                });
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereHas('order', function ($q) use ($tahun) {
                    $q->whereYear('tanggal_order', $tahun);
                });
            })
            ->latest()
            ->paginate(10);

        $pembayarans->appends($request->all());

        return view('pages.kelolaPembayaran.index', [
            'type_menu' => $type_menu,
            'pembayaran' => $pembayarans
        ]);
    }
    public function create()
    {
        $type_menu = 'kelolaPembayaran';
        $orders = order::all();
        return view('pages.kelolaPembayaran.create', compact('type_menu', 'orders'));
    }

    public function store(Request $request)
    {
        // validasi data dari form tambah user
        $validatedData = $request->validate([
            'order_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_dibayar' => 'required',
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
            'jumlah_dibayar' => $validatedData['jumlah_dibayar'],
            'bukti_bayar' => $imagePath,
            'status' => 'Proses Pembayaran'
        ]);
        return redirect()->route('kelolaPembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function edit(pembayaran $kelolaPembayaran)
    {
        $type_menu = 'kelolaPembayaran';
        $orders = order::all();
        return view('pages.kelolaPembayaran.edit', [
            'type_menu' => $type_menu,
            'pembayaran' => $kelolaPembayaran,
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, pembayaran $kelolaPembayaran)
    {
        // Validate the form data
        $request->validate([
            'order_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_dibayar' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);

        // Update the user data
        $kelolaPembayaran->update([
            'order_id' => $request->order_id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'status' => $request->status,
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $image = $request->file('bukti_bayar');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/bukti_bayar/', $path);
            $kelolaPembayaran->update([
                'bukti_bayar' => $path
            ]);
        }

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('kelolaPembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }


    public function show(pembayaran $kelolaPembayaran)
    {
        $type_menu = 'kelolaPembayaran';
        return view('pages.kelolaPembayaran.show', compact('kelolaPembayaran', 'type_menu'));
    }
    public function destroy(pembayaran $kelolaPembayaran)
    {
        // Hapus bukti bayar jika ada
        if ($kelolaPembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $kelolaPembayaran->bukti_bayar))) {
            unlink(public_path('img/bukti_bayar/' . $kelolaPembayaran->bukti_bayar));
        }
        // Hapus data pembayaran
        $kelolaPembayaran->delete();
        return redirect()->route('kelolaPembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
    // Menampilkan pembayaran yang sedang diproses
    public function showProses()
    {
        $type_menu = 'kelolaPembayaran';
        $pembayarans = pembayaran::where('status', 'Proses Pembayaran')
            ->latest()
            ->paginate(10);

        return view('pages.kelolaPembayaran.proses', compact('pembayarans', 'type_menu'));
    }

    // Mengubah status pembayaran
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Pembayaran,Proses Pembayaran,Pembayaran Berhasil',
        ]);

        $pembayaran = pembayaran::find($id);
        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $pembayaran->status = $request->status;
        $pembayaran->save();

        // Opsional: Update status order jika dibutuhkan
        if ($request->status === 'Pembayaran Berhasil' && $pembayaran->order) {
            $pembayaran->order->status = 'Selesai';
            $pembayaran->order->save();
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
