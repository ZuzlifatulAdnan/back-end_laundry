<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use App\Models\order;
use App\Models\pembayaran;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function selfservice()
    {
        $type_menu = 'order';
        $mesins = Mesin::where('status', 'Ready')->get();

        return view('pages.orders.self', compact('type_menu', 'mesins'));
    }

    // 🔹 Simpan data order Self Service
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
        // Buat no_order
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

        // Buat no_pembayaran
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        // Buat data pembayaran awal
        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);

        // Kirim notifikasi
        $fonnteService = app(FonnteService::class);

        // 🔹 Kirim ke admin
        $adminPhone = '6282178535114';
        $user = Auth::user();
        ;
        $messageAdmin = "🧺 *Pesanan SelfService Baru!*\n\n" .
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
            "✅ Segera cek & kelola pesanan ini melalui sistem admin:\n" .
            "🔗 https://laundryku.com/kelolaOrder\n\n" .
            "Terima kasih 🙏";

        $fonnteService->sendMessage($adminPhone, $messageAdmin);

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
                "💳 *Status Pembayaran:* {$pembayaran->status}\n" .
                "💰 *Jumlah yang harus dibayar:* Rp " . number_format($pembayaran->jumlah_dibayar, 0, ',', '.') . "\n\n" .
                "Terima kasih 🙏";

            $fonnteService->sendMessage($customerPhone, $messageCustomer);
        }
        
        return redirect()->route('pembayaran.edit', $pembayaran->id)
            ->with('success', 'Order Self Service dengan No Order ' . $order->no_order . ' berhasil dibuat. Silakan lengkapi pembayaran Anda.');
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

        // Buat no_pembayaran
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        // Buat data pembayaran awal
        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'order_id' => $order->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $order->total_biaya,
            'status' => 'Menunggu Pembayaran',
        ]);
        // Kirim notifikasi
        $fonnteService = app(FonnteService::class);

        // 🔹 Kirim ke admin
        $adminPhone = '6282178535114';
        $user = Auth::user();

        $messageAdmin = "🧺 *Pesanan DropOff Baru!*\n\n" .
            "👤 *Pelanggan:* {$user->name}\n" .
            "🧾 *No Order:* {$order->no_order}\n" .
            "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
            "⏰ *Jam Order:* {$order->jam_order}\n" .
            "⚖️ *Berat Cucian:* {$order->berat} kg\n" .
            "🧼 *Detergent:* {$order->detergent}\n" .
            "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
            "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
            "📌 *Layanan:* {$order->service_type}\n\n" .
            "✅ Segera cek & kelola pesanan ini melalui sistem admin:\n" .
            "🔗 https://laundryku.com/kelolaOrder\n\n" .
            "Terima kasih 🙏";

        $fonnteService->sendMessage($adminPhone, $messageAdmin);

        // 🔹 Kirim ke pelanggan
        $customerPhone = $order->user->no_handphone;

        if ($customerPhone) {
            $messageCustomer = "🧺 *Pesanan DropOff Anda!*\n\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "⏰ *Jam Order:* {$order->jam_order}\n" .
                "⚖️ *Berat Cucian:* {$order->berat} kg\n" .
                "🧼 *Detergent:* {$order->detergent}\n" .
                "📝 *Catatan:* " . ($order->catatan ?: '-') . "\n" .
                "💵 *Total Biaya:* Rp " . number_format($order->total_biaya, 0, ',', '.') . "\n\n" .
                "📌 *Layanan:* {$order->service_type}\n\n" .
                "Terima kasih 🙏";

            $fonnteService->sendMessage($customerPhone, $messageCustomer);
        }

        return redirect()->route('pembayaran.edit', $pembayaran->id)
            ->with('success', 'Order DropOff dengan No Order' . $order->no_order . 'berhasil dibuat. Silakan lengkapi pembayaran Anda.');
    }
}
