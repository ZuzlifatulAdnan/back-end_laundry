<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'riwayat';
        $user = Auth::user();

        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'desc');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $years = range(date('Y') - 10, date('Y'));
        $statusOptions = ['Proses', 'Selesai', 'Batal'];

        $orders = order::with('pembayaran', 'mesin')
            ->where('user_id', $user->id)
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_order', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal_order', $tahun))
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->appends($request->all());

        return view('pages.riwayat.index', compact('type_menu', 'orders', 'months', 'years', 'statusOptions'));
    }

    public function show($id)
    {
        $type_menu = 'riwayat';
        $order = order::with(['user', 'mesin', 'pembayaran'])->findOrFail($id);

        // Cek apakah order milik user yang sedang login
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('pages.riwayat.detail', compact('order', 'type_menu'));
    }
}
