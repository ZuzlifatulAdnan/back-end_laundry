<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\pembayaran;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PembayaranController extends Controller
{
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
        // Ambil order & user terkait pembayaran
        $order = $pembayaran->order;
        $user = $order->user;

        // Kirim notifikasi
        $fonnteService = app(FonnteService::class);

        // 🔹 Kirim ke admin
        $adminPhone = env('ADMIN_PHONE');
        $messageAdmin = "💳 *Pembayaran Baru Diterima!*\n\n" .
            "👤 *Pelanggan:* {$user->name}\n" .
            "🧾 *No Order:* {$order->no_order}\n" .
            "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
            "💳 *Metode Pembayaran:* {$pembayaran->metode_pembayaran}\n" .
            "💵 *Jumlah Dibayar:* Rp " . number_format($pembayaran->jumlah_dibayar, 0, ',', '.') . "\n" .
            "📄 *Status:* {$pembayaran->status}\n\n" .
            "✅ Segera cek & verifikasi pembayaran ini di sistem admin:\n" .
            "🔗 https://laundryku.com/kelolaPembayaran\n\n" .
            "Terima kasih 🙏";

        $fonnteService->sendMessage($adminPhone, $messageAdmin);

        // 🔹 Kirim ke pelanggan
        $customerPhone = $user->no_handphone;

        if ($customerPhone) {
            $messageCustomer = "💳 *Pembayaran Anda Berhasil Diterima!*\n\n" .
                "🧾 *No Order:* {$order->no_order}\n" .
                "📅 *Tanggal Order:* {$order->tanggal_order}\n" .
                "💳 *Metode Pembayaran:* {$pembayaran->metode_pembayaran}\n" .
                "💵 *Jumlah Dibayar:* Rp " . number_format($pembayaran->jumlah_dibayar, 0, ',', '.') . "\n" .
                "📄 *Status:* {$pembayaran->status}\n\n" .
                "🙏 Terima kasih telah melakukan pembayaran. Kami akan segera memproses pesanan Anda.";

            $fonnteService->sendMessage($customerPhone, $messageCustomer);
        }

        return Redirect::route('riwayat.index', $pembayaran->order_id)->with('success ', 'Pembayaran' . $pembayaran->no_pembayaran . ' berhasil diperbarui.');
    }
}
