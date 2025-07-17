<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Services\FonnteService;
class PembayaranController extends Controller
{
    /**
     * Tampilkan detail pembayaran
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with('order.user')->findOrFail($id);

        return response()->json([
            'pembayaran' => $pembayaran
        ]);
    }

    /**
     * Update pembayaran (metode & bukti bayar)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $pembayaran = Pembayaran::with('order.user')->findOrFail($id);

        $pembayaran->update([
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // Upload bukti bayar jika ada
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/bukti_bayar/'), $filename);

            // hapus lama jika ada
            if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
                unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
            }

            $pembayaran->update([
                'bukti_bayar' => $filename,
                'status' => 'Proses Pembayaran'
            ]);
        }

        $order = $pembayaran->order;
        $user = $order->user;

        // 🔹 Kirim notifikasi
        $fonnteService = app(FonnteService::class);

        // 🔹 Admin
        $adminPhone = '6282178535114';
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

        // 🔹 Customer
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

        return response()->json([
            'message' => 'Pembayaran berhasil diperbarui.',
            'pembayaran' => $pembayaran
        ]);
    }
}
