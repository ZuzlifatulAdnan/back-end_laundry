<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mesin;
use App\Models\Order;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteService;
class OrderController extends Controller
{
    /**
     * Ambil mesin ready (untuk SelfService)
     */
    public function mesinReady()
    {
        $mesins = Mesin::where('status', 'Ready')->get();

        return response()->json([
            'mesins_ready' => $mesins
        ]);
    }

    /**
     * Simpan order SelfService
     */
    public function storeSelfservice(Request $request, FonnteService $fonnte)
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

        $tanggal = date('Ymd');
        $jumlahOrderHariIni = Order::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_order = 'ORD-' . $tanggal . '-' . str_pad($jumlahOrderHariIni, 3, '0', STR_PAD_LEFT);

        $order = Order::create([
            'no_order' => $no_order,
            'user_id' => Auth::id(),
            'service_type' => 'Self Service',
            'mesin_id' => $request->mesin_id,
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'durasi' => $request->durasi,
            'koin' => $request->koin,
            'catatan' => $request->catatan,
            'status' => 'Diproses',
            'total_biaya' => $request->total_biaya,
        ]);

        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad(
            Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);

        $this->kirimNotifikasi($order, $pembayaran, $fonnte);

        return response()->json([
            'message' => 'Order Self Service berhasil dibuat',
            'order' => $order,
            'pembayaran' => $pembayaran,
        ]);
    }

    /**
     * Simpan order DropOff
     */
    public function storeDropoff(Request $request, FonnteService $fonnte)
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

        $tanggal = date('Ymd');
        $jumlahOrderHariIni = Order::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_order = 'ORD-' . $tanggal . '-' . str_pad($jumlahOrderHariIni, 3, '0', STR_PAD_LEFT);

        $order = Order::create([
            'no_order' => $no_order,
            'user_id' => Auth::id(),
            'service_type' => 'Drop Off',
            'tanggal_order' => $request->tanggal_order,
            'jam_order' => $request->jam_order,
            'berat' => $request->berat,
            'detergent' => $request->detergent,
            'tanggal_ambil' => $request->tanggal_ambil,
            'catatan' => $request->catatan,
            'status' => 'Diproses',
            'total_biaya' => $request->total_biaya,
        ]);

        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad(
            Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);

        $this->kirimNotifikasi($order, $pembayaran, $fonnte);

        return response()->json([
            'message' => 'Order DropOff berhasil dibuat',
            'order' => $order,
            'pembayaran' => $pembayaran,
        ]);
    }

    /**
     * Kirim notifikasi Fonnte ke admin & customer
     */
    protected function kirimNotifikasi(Order $order, Pembayaran $pembayaran, FonnteService $fonnte)
    {
        $user = $order->user;
        $adminPhone = '6282178535114';

        if ($order->service_type === 'Self Service') {
            $msgAdmin = "🧺 *Pesanan SelfService Baru!*\n\n" .
                "👤 *Pelanggan:* {$user->name}\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                "⏳ *Durasi:* {$order->durasi} menit\n" .
                "🪙 *Mesin:* {$order->mesin->nama}\n" .
                "💰 *Koin:* {$order->koin}\n" .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n\n" .
                "🔗 https://laundryku.com/kelolaOrder";
        } else {
            $msgAdmin = "🧺 *Pesanan DropOff Baru!*\n\n" .
                "👤 *Pelanggan:* {$user->name}\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                "⚖️ *Berat Cucian:* {$order->berat} kg\n" .
                "🧼 *Detergent:* {$order->detergent}\n" .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n\n" .
                "🔗 https://laundryku.com/kelolaOrder";
        }

        $fonnte->sendMessage($adminPhone, $msgAdmin);

        $customerPhone = $user->no_handphone;
        if ($customerPhone) {
            $msgCustomer = "🧺 *Pesanan {$order->service_type} Anda!*\n\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                ($order->service_type === 'Self Service'
                    ? "⏳ *Durasi:* {$order->durasi} menit\n🪙 *Mesin:* {$order->mesin->nama}\n💰 *Koin:* {$order->koin}\n"
                    : "⚖️ *Berat Cucian:* {$order->berat} kg\n🧼 *Detergent:* {$order->detergent}\n") .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n" .
                "💳 *Status Pembayaran:* {$pembayaran->status}\n" .
                "💰 *Jumlah yang harus dibayar:* Rp " . number_format($pembayaran->jumlah_dibayar, 0, ',', '.') . "\n\n" .
                "Terima kasih 🙏";

            $fonnte->sendMessage($customerPhone, $msgCustomer);
        }
    }
}
