<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * List riwayat order milik user login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'desc');

        $orders = Order::with('pembayaran', 'mesin')
            ->where('user_id', $user->id)
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_order', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal_order', $tahun))
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->appends($request->all());

        return response()->json([
            'orders' => $orders,
            'filters' => [
                'status' => $status,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'sort' => $sort,
            ]
        ]);
    }

    /**
     * Tampilkan detail order
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        $order = Order::with(['user', 'mesin', 'pembayaran'])->findOrFail($id);

        if ($order->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        return response()->json([
            'order' => $order
        ]);
    }
}
