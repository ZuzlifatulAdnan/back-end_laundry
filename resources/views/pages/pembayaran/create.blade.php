@extends('layouts.app')

@section('title', 'Pembayaran')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pembayaran Order {{ $order->user->name }}</h1>
            </div>

            <div class="section-body">
                @include('layouts.alert')

                <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran"
                            class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="">Pilih Metode</option>
                            <option value="Transfer Bank"
                                {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai
                            </option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar"
                            class="form-control @error('jumlah_bayar') is-invalid @enderror"
                            value="{{ old('jumlah_bayar', $order->total_biaya) }}" readonly required>
                        @error('jumlah_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Bayar</label>
                        <input type="file" name="bukti_bayar"
                            class="form-control @error('bukti_bayar') is-invalid @enderror" required>
                        @error('bukti_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Kirim Pembayaran</button>
                </form>
            </div>
        </section>
    </div>
@endsection
