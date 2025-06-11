<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index(Request $request)
    // {
    //     $type_menu = 'riwayatOrder';
    //     // Ambil data user yang sedang login
    //     $user = Auth::user();
    //     // Input values from the request
    //     $status = $request->input('status');
    //     $bulan = $request->input('bulan');
    //     $tahun = $request->input('tahun');
    //     $sort = $request->input('sort'); // Handle sort by created_at

    //     // Dynamic dropdown options
    //     $months = [
    //         1 => 'Januari',
    //         2 => 'Februari',
    //         3 => 'Maret',
    //         4 => 'April',
    //         5 => 'Mei',
    //         6 => 'Juni',
    //         7 => 'Juli',
    //         8 => 'Agustus',
    //         9 => 'September',
    //         10 => 'Oktober',
    //         11 => 'November',
    //         12 => 'Desember',
    //     ];
    //     $years = range(date('Y') - 10, date('Y')); // Last 10 years
    //     $statusOptions = ['Selesai', 'Diterima', 'Ditolak', 'Diproses'];

    //     // Query the `pemesanan` data with filters
    //     $orders = order::with(['user', 'ruangan', 'ruangan.gedung'])
    //         ->where('user_id', $user->id) // Filter berdasarkan user_id
    //         ->when($status, function ($query, $status) {
    //             $query->where('status', $status);
    //         })
    //         ->when($bulan, function ($query, $bulan) {
    //             $query->whereMonth('tanggal_pesan', $bulan);
    //         })
    //         ->when($tahun, function ($query, $tahun) {
    //             $query->whereYear('tanggal_pesan', $tahun);
    //         })->when($sort, function ($query, $sort) {
    //             $query->orderBy('created_at', $sort); // Sort by created_at in ascending or descending order
    //         })
    //         ->latest()
    //         ->paginate(10);

    //     // Append query parameters to pagination links
    //     $orders->appends([
    //         'status' => $status,
    //         'bulan' => $bulan,
    //         'tahun' => $tahun,
    //         'sort' => $sort,
    //     ]);

    //     // arahkan ke file pages/users/index.blade.php
    //     return view('pages.orders.index', compact('type_menu', 'orders', 'months', 'years', 'statusOptions'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $type_menu = 'order';
          $mesins = Mesin::where('status', 'Ready')->get();

        return view('pages.orders.create', compact('type_menu', 'mesins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string',
            'mesin_id' => 'nullable|exists:mesins,id',
            'tanggal_order' => 'required|date',
            'jam_order' => 'required',
            'durasi' => 'nullable|integer',
            'koin' => 'nullable|integer',
            'berat' => 'nullable|numeric',
            'detergent' => 'nullable|integer',
            'catatan' => 'nullable|string',
            'tangal_ambil' => 'nullable|date',
            'total_biaya' => 'required|integer',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'mesin_id' => $request->mesin_id,
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'durasi' => $request->durasi,
            'koin' => $request->koin,
            'berat' => $request->berat,
            'detergent' => $request->detergent,
            'catatan' => $request->catatan,
            'tangal_ambil' => $request->tangal_ambil,
            'status' => 'Proses',
            'total_biaya' => $request->total_biaya,
        ]);


        // 🔁 Redirect ke halaman create pembayaran dengan membawa order_id
        return redirect()->route('pembayaran.create', ['order_id' => $order->id])
            ->with('success', 'Order berhasil dibuat, silakan lanjutkan ke pembayaran.');
    }

    /**
     * Show the specified resource in detail.
     */
    public function show($id)
    {
        $type_menu = 'riwayatOrder';
        $order = order::find($id);

        // arahkan ke file pages/users/edit
        return view('pages.orders.show', compact('order', 'type_menu'));
    }
}
