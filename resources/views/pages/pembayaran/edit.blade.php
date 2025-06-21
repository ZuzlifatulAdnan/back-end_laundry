@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('main')
<div class="main-content">
    @if (Auth::user()->role == 'Customer')
    <section class="section">
        <div class="section-header">
            <h1>Edit Pembayaran untuk Order {{ $pembayaran->order->user->name }}</h1>
        </div>

        <div class="section-body">
            @include('layouts.alert')

            <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $pembayaran->order_id }}">

                {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran"
                        class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank" {{ $pembayaran->metode_pembayaran == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="QRIS" {{ $pembayaran->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Tunai" {{ $pembayaran->metode_pembayaran == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    </select>
                    @error('metode_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Informasi Transfer --}}
                <div id="transfer_info" style="display: none;" class="mb-3">
                    <h6>Daftar Rekening Transfer:</h6>
                    <ul>
                        <li><strong>BCA</strong> - 1234567890 a.n PT Contoh Nama</li>
                        <li><strong>BNI</strong> - 9876543210 a.n PT Contoh Nama</li>
                        <li><strong>Mandiri</strong> - 1122334455 a.n PT Contoh Nama</li>
                    </ul>
                </div>

                {{-- Gambar QRIS --}}
                <div id="qris_info" style="display: none;" class="mb-3 text-center">
                    <h6>Scan QRIS Berikut:</h6>
                    <img src="{{ asset('img/qris.png') }}" alt="QRIS" style="max-width: 250px;" class="img-fluid rounded shadow">
                </div>

                {{-- Jumlah Bayar --}}
                <div class="form-group">
                    <label for="jumlah_bayar">Jumlah Bayar (Rp)</label>
                    <input type="number" id="jumlah_bayar" name="jumlah_bayar"
                        class="form-control @error('jumlah_bayar') is-invalid @enderror"
                        value="{{ $pembayaran->jumlah_dibayar }}" readonly required>
                    @error('jumlah_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bukti Bayar --}}
                <div class="form-group">
                    <label for="bukti_bayar">Upload Bukti Bayar Baru (opsional)</label>
                    <input type="file" id="bukti_bayar" name="bukti_bayar"
                        class="form-control @error('bukti_bayar') is-invalid @enderror" accept="image/*">
                    @error('bukti_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($pembayaran->bukti_bayar)
                        <div class="mt-2">
                            <p>Bukti Bayar Sebelumnya:</p>
                            <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}" alt="Bukti Bayar" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Pembayaran</button>
                <a href="{{ url()->previous() }}" class="btn btn-warning mt-3 ml-2">Kembali</a>
            </form>
        </div>
    </section>
    @else
        <div class="alert alert-danger">
            User role Anda tidak mendapatkan izin.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const metodeSelect = document.getElementById('metode_pembayaran');
        const transferInfo = document.getElementById('transfer_info');
        const qrisInfo = document.getElementById('qris_info');

        function togglePaymentInfo() {
            const selected = metodeSelect.value;
            transferInfo.style.display = selected === 'Transfer Bank' ? 'block' : 'none';
            qrisInfo.style.display = selected === 'QRIS' ? 'block' : 'none';
        }

        metodeSelect.addEventListener('change', togglePaymentInfo);
        togglePaymentInfo(); // Initial state
    });
</script>
@endpush
