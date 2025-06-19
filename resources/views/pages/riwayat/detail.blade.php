@extends('layouts.app')

@section('title', 'Detail Order')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Order</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Order</h4>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Nama Pengguna</dt>
                            <dd class="col-sm-9">{{ $order->user->name }}</dd>

                            <dt class="col-sm-3">Nama Mesin</dt>
                            <dd class="col-sm-9">{{ $order->mesin->nama ?? '-' }}</dd>

                            <dt class="col-sm-3">Tanggal Order</dt>
                            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') }}</dd>

                            <dt class="col-sm-3">Jam Order</dt>
                            <dd class="col-sm-9">{{ $order->jam_order }}</dd>

                            <dt class="col-sm-3">Catatan</dt>
                            <dd class="col-sm-9">{{ $order->catatan ?? '-' }}</dd>

                            <dt class="col-sm-3">Tipe Order</dt>
                            <dd class="col-sm-9">
                                @if ($order->service_type == 'SelfService')
                                    <span class="badge badge-info">Self Service</span>
                                @elseif ($order->service_type == 'DropOff')
                                    <span class="badge badge-dark">Drop Off</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($order->type) }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Status Order</dt>
                            <dd class="col-sm-9">
                                @switch($order->status)
                                    @case('Diproses')
                                        <span class="badge badge-warning">Diproses</span>
                                    @break

                                    @case('Diterima')
                                        <span class="badge badge-primary">Diterima</span>
                                    @break

                                    @case('Dibatalkan')
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @break

                                    @case('Ditunda')
                                        <span class="badge badge-dark">Ditunda</span>
                                    @break

                                    @case('Selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @break

                                    @default
                                        <span class="badge badge-secondary">{{ $order->status }}</span>
                                @endswitch
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Informasi Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        @php $pembayaran = $order->pembayaran; @endphp

                        @if ($pembayaran)
                            <dl class="row">
                                <dt class="col-sm-3">Metode Pembayaran</dt>
                                <dd class="col-sm-9">{{ ucfirst($pembayaran->metode_pembayaran) }}</dd>

                                <dt class="col-sm-3">Jumlah Bayar</dt>
                                <dd class="col-sm-9">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</dd>

                                <dt class="col-sm-3">Status Pembayaran</dt>
                                <dd class="col-sm-9">
                                    @switch($pembayaran->status)
                                        @case('Menunggu Pembayaran')
                                            <span class="badge badge-danger">Menunggu Pembayaran</span>
                                        @break

                                        @case('Proses Pembayaran')
                                            <span class="badge badge-warning">Proses Pembayaran</span>
                                        @break

                                        @case('Pembayaran Berhasil')
                                            <span class="badge badge-success">Pembayaran Berhasil</span>
                                        @break

                                        @default
                                            <span class="badge badge-secondary">{{ $pembayaran->status }}</span>
                                    @endswitch
                                </dd>

                                @if ($pembayaran->bukti_bayar)
                                    <dt class="col-sm-3">Bukti Bayar</dt>
                                    <dd class="col-sm-9">
                                        <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}" alt="Bukti Bayar"
                                            class="img-fluid" style="max-height: 200px;">
                                    </dd>
                                @endif
                            </dl>

                            @if ($pembayaran->status == 'Menunggu Pembayaran')
                                <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-warning">
                                    <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                                </a>
                            @endif
                        @else
                            <p class="text-danger">Belum ada data pembayaran.</p>
                            <a href="{{ route('pembayaran.create', ['order_id' => $order->id]) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>


                <a href="{{ url()->previous() }}" class="btn btn-warning mt-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </section>
    </div>
@endsection
