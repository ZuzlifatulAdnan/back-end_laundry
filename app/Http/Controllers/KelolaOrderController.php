<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use App\Models\order;
use App\Models\User;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KelolaOrderController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'kelolaOrder';

        // Ambil filter dari request
        $keyword = trim($request->input('search'));
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $service_type = $request->input('service_type');

        // Query orders dengan filter yang diterapkan
        $orders = order::with('user')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', '%' . $keyword . '%');
                    })
                        ->orWhere('no_order', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('tanggal_order', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('tanggal_order', $tahun);
            })
            ->when($service_type, function ($query, $service_type) {
                $query->where('service_type', $service_type);
            })
            ->latest()
            ->paginate(10);

        // Tambahkan parameter query ke pagination
        $orders->appends([
            'search' => $keyword,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'service_type' => $service_type,
        ]);

        // Arahkan ke view (misalnya: resources/views/pages/orders/index.blade.php)
        return view('pages.kelolaOrder.index', compact('type_menu', 'orders'));
    }
    public function create()
    {
        $type_menu = 'kelolaOrder';
        $users = User::all();
        $mesins = Mesin::all();
        return view('pages.kelolaOrder.create', compact('type_menu', 'users', 'mesins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'service_type' => 'required',
            'mesin_id' => 'nullable',
            'tanggal_order' => 'required|date',
            'jam_order' => 'required',
            'durasi' => 'nullable|numeric',
            'koin' => 'nullable|numeric',
            'berat' => 'nullable|numeric',
            'detergent' => 'nullable|numeric',
            'catatan' => 'nullable|string',
            'tanggal_ambil' => 'nullable|date',
            'status' => 'required|string',
            'total_biaya' => 'required|numeric',
        ]);

        $tanggal = date('Ymd');
        $jumlahOrderHariIni = Order::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_order = 'ORD-' . $tanggal . '-' . str_pad($jumlahOrderHariIni, 3, '0', STR_PAD_LEFT);

        $order = Order::create([
            'no_order' => $no_order,
            'user_id' => $request->user_id,
            'service_type' => $request->service_type,
            'mesin_id' => $request->mesin_id,
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'durasi' => $request->durasi,
            'koin' => $request->koin,
            'berat' => $request->berat,
            'detergent' => $request->detergen,
            'catatan' => $request->catatan,
            'tanggal_ambil' => $request->tanggal_ambil,
            'status' => $request->status,
            'total_biaya' => $request->total_biaya,
        ]);

        return redirect()->route('kelolaOrder.index')->with('success', 'Order dengan No Order ' . $order->no_order . ' berhasil ditambahkan.');
    }

    public function edit(Order $kelolaOrder) // ubah dari $order
    {
        $type_menu = 'kelolaOrder';
        $users = User::all();
        $mesins = Mesin::all();
        return view('pages.kelolaOrder.edit', [
            'type_menu' => $type_menu,
            'order' => $kelolaOrder, // atau langsung 'kelolaOrder' => $kelolaOrder
            'users' => $users,
            'mesins' => $mesins,
        ]);
    }

    public function update(Request $request, Order $kelolaOrder)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_type' => 'required|in:Self Service,Drop Off',
            'mesin_id' => 'nullable|exists:mesins,id',
            'tanggal_order' => 'required|date',
            'jam_order' => 'required',
            'durasi' => 'nullable|numeric',
            'koin' => 'nullable|numeric',
            'berat' => 'nullable|numeric',
            'detergent' => 'nullable|numeric', // Fix typo
            'catatan' => 'nullable|string',
            'tanggal_ambil' => 'nullable|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak,Dibatalkan,Selesai,Ditunda',
            'total_biaya' => 'required|numeric',
        ]);

        // Update data ke database
        $kelolaOrder->update([
            'user_id' => $request->user_id,
            'service_type' => $request->service_type,
            'mesin_id' => $request->mesin_id,
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'durasi' => $request->durasi,
            'koin' => $request->koin,
            'berat' => $request->berat,
            'detergent' => $request->detergent, // Fix typo dari 'detergen'
            'catatan' => $request->catatan,
            'tanggal_ambil' => $request->tanggal_ambil,
            'status' => $request->status,
            'total_biaya' => $request->total_biaya,
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('kelolaOrder.index')->with('success', 'Order dengan No order ' . $kelolaOrder->no_order . ' berhasil diperbarui.');
    }


    public function show(Order $kelolaOrder)
    {
        $type_menu = 'kelolaOrder';
        return view('pages.kelolaOrder.show', compact('kelolaOrder', 'type_menu'));
    }
    public function destroy(Order $kelolaOrder)
    {
        $kelolaOrder->delete();
        return redirect()->route('kelolaOrder.index')->with('success', 'Order dengan No Order ' . $kelolaOrder->no_order . ' berhasil dihapus.');
    }
    // ✅ Menampilkan order yang statusnya "Diproses"
    public function showProses()
    {
        $type_menu = 'kelolaOrder';
        $orders = Order::where('status', 'Diproses')->latest()->paginate(10);
        return view('pages.kelolaOrder.proses', compact('orders', 'type_menu'));
    }

    // ✅ Update status order via modal
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:Diproses,Diterima,Ditolak,Dibatalkan,Selesai,Ditunda'],
            'status_pembayaran' => ['nullable', 'in:Menunggu Pembayaran,Proses Pembayaran,Pembayaran Berhasil'],
        ]);

        $order = Order::with('pembayaran')->findOrFail($id);

        $order->status = $request->status;
        $order->save();

        if ($order->pembayaran) {
            $order->pembayaran->status = $request->status_pembayaran;
            $order->pembayaran->save();
        }
        // Kirim notifikasi
        $fonnteService = app(FonnteService::class);
        // 🔹 Kirim ke pelanggan
        $customerPhone = $order->user->no_handphone;

        if ($customerPhone) {
            $messageCustomer = "🧺 *Pesanan Self Service Anda!*\n\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                "⏳ *Durasi:* {$order->durasi} menit\n" .
                "🪙 *Mesin:* {$order->mesin->nama}\n" .
                "💰 *Koin:* {$order->koin}\n" .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n\n" .
                "💳 *Status Pembayaran:* " . ($order->pembayaran->status ?? '-') . "\n" .
                "💰 *Jumlah yang harus dibayar:* Rp " . number_format($order->pembayaran->jumlah_dibayar ?? 0, 0, ',', '.') . "\n\n" .
                "Terima kasih 🙏";

            $fonnteService->sendMessage($customerPhone, $messageCustomer);
        }

        return redirect()->back()->with('success', 'Status order ' . $order->no_order . ' berhasil diperbarui.');
    }
    public function showDiterima()
    {
        $type_menu = 'kelolaOrder';
        $orders = Order::where('status', 'Diterima')->latest()->paginate(10);
        return view('pages.kelolaOrder.terima', compact('orders', 'type_menu'));
    }

    public function autoBatal($id)
    {
        $order = Order::findOrFail($id);

        $deadline = Carbon::parse("{$order->tanggal_order} {$order->jam_order}")
            ->addMinutes(15);

        if (now()->greaterThan($deadline) && $order->status === 'Diterima') {
            $order->status = 'Ditunda';
            $order->save();

            // 🔔 Kirim notifikasi ke pelanggan bahwa pesanan ditunda
            $customerPhone = $order->user->no_handphone;

            if ($customerPhone) {
                $messageCustomer = "🧺 *Status Pesanan Anda DITUNDA!*\n\n" .
                    "Kami informasikan bahwa pesanan Self Service Anda:\n\n" .
                    "🧾 *No Order:* {$order->no_order}\n" .
                    "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                    "⏰ *Jam Order:* {$order->jam_order}\n\n" .
                    "telah *melewati batas waktu* yang ditentukan (lebih dari 15 menit) dan otomatis ditunda oleh sistem.\n\n" .
                    "Jika Anda masih ingin melanjutkan pesanan, *harap segera menghubungi admin* untuk proses lebih lanjut.\n\n" .
                    "📞 Kontak Admin: 08xxxxxxxxxx\n\n" .
                    "Terima kasih atas perhatian dan kerja samanya 🙏";

                app(FonnteService::class)->sendMessage($customerPhone, $messageCustomer);
            }

            return response()->json(['status' => 'Ditunda']);
        }

        // ✅ Jika pesanan masih dalam waktu yang diperbolehkan, kirim reminder
        $customerPhone = $order->user->no_handphone;

        if ($customerPhone) {
            $messageReminder = "🧺 *Pesanan Self Service Anda!*\n\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                "⏳ *Durasi:* {$order->durasi} menit\n" .
                "🪙 *Mesin:* {$order->mesin->nama}\n" .
                "💰 *Koin:* {$order->koin}\n" .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n\n" .
                "💳 *Status Pembayaran:* " . ($order->pembayaran->status ?? '-') . "\n" .
                "💰 *Jumlah yang harus dibayar:* Rp " . number_format($order->pembayaran->jumlah_dibayar ?? 0, 0, ',', '.') . "\n\n" .
                "⏰ *Mohon segera melakukan proses pemesanan sebelum waktu habis.*\n\n" .
                "Terima kasih 🙏";

            app(FonnteService::class)->sendMessage($customerPhone, $messageReminder);
        }

        return response()->json(['status' => 'masih berlaku'], 200);
    }
}
