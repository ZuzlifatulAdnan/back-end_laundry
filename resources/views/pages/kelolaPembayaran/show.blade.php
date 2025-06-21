@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Admin')
            <section class="section">
                <div class="section-header">
                    <h1>Detail Pembayaran</h1>
                    <div class="section-header-breadcrumb">
                        <a href="{{ route('kelolaPembayaran.index') }}" class="btn btn-light btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="section-body">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <strong>No Pembayaran</strong>
                                    <p>{{ $kelolaPembayaran->no_pembayaran ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Nama Pengguna</strong>
                                    <p>{{ $kelolaPembayaran->order->user->name ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Layanan</strong>
                                    <p>{{ $pembaykelolaPembayaranaran->order->service_type ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Metode Pembayaran</strong>
                                    <p>{{ $kelolaPembayaran->metode_pembayaran }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Jumlah Dibayar</strong>
                                    <p>Rp {{ number_format($kelolaPembayaran->jumlah_dibayar, 0, ',', '.') }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Status</strong>
                                    <p>
                                        <span
                                            class="badge 
                                        {{ $kelolaPembayaran->status == 'Pembayaran Berhasil'
                                            ? 'badge-success'
                                            : ($kelolaPembayaran->status == 'Proses Pembayaran'
                                                ? 'badge-warning'
                                                : 'badge-secondary') }}">
                                            {{ $kelolaPembayaran->status }}
                                        </span>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Tanggal Pembayaran</strong>
                                    <p>{{ \Carbon\Carbon::parse($kelolaPembayaran->created_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>

                                <div class="col-12">
                                    <strong>Bukti Bayar</strong><br>
                                    @if ($kelolaPembayaran->bukti_bayar)
                                        <img src="{{ asset('img/bukti_bayar/' . $kelolaPembayaran->bukti_bayar) }}"
                                            alt="Bukti Bayar" style="max-width: 400px; margin-top: 10px;"
                                            class="img-thumbnail">
                                    @else
                                        <p><em>Tidak ada bukti bayar</em></p>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('kelolaPembayaran.index') }}" class="btn btn-warning">Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <div class="alert alert-danger">
                User role Anda tidak mendapatkan izin.
            </div>
        @endif
    </div>
@endsection
