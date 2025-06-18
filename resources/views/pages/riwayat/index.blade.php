@extends('layouts.app')

@section('title', 'Riwayat Order')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Riwayat Order</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Service</th>
                                        <th>Tanggal Order</th>
                                        <th>Jam</th>
                                        <th>Status Order</th>
                                        <th>Status Pembayaran</th>
                                        <th>Total Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $index => $order)
                                        <tr>
                                            <td>{{ $orders->firstItem() + $index }}</td>
                                            <td>{{ ucfirst($order->service_type) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') }}</td>
                                            <td>{{ $order->jam_order }}</td>
                                            <td>
                                                @switch($order->status)
                                                    @case('Diproses')
                                                        <span class="badge badge-warning">Diproses</span>
                                                    @break

                                                    @case('Diterima')
                                                        <span class="badge badge-primary">Diterima</span>
                                                    @break

                                                    @case('Ditolak')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @break

                                                    @case('Dibatalkan')
                                                        <span class="badge badge-dark">Dibatalkan</span>
                                                    @break

                                                    @case('Selesai')
                                                        <span class="badge badge-success">Selesai</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ $order->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @php
                                                    $statusPembayaran =
                                                        $order->pembayaran->status ?? 'Menunggu Pembayaran';
                                                @endphp

                                                @if ($statusPembayaran === 'Pembayaran Berhasil')
                                                    <span class="badge badge-success">Pembayaran Berhasil</span>
                                                @elseif ($statusPembayaran === 'Proses Pembayaran')
                                                    <span class="badge badge-warning">Proses Pembayaran</span>
                                                @else
                                                    <span class="badge badge-danger">Menunggu Pembayaran</span>
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('riwayat.show', $order->id) }}"
                                                    class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Detail">
                                                    <i class="fas fa-eye"></i> 
                                                </a>
                                                {{-- @if ($statusPembayaran === 'Menunggu Pembayaran')
                                                    <a href="{{ route('pembayaran.create', ['order_id' => $order->id]) }}"
                                                        class="btn btn-warning btn-sm mt-1" data-bs-toggle="tooltip" title="bayar Sekarang">
                                                        <i class="fas fa-credit-card"></i> 
                                                    </a>
                                                @endif --}}
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data order.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                                        {{ $orders->total() }} data
                                    </div>
                                    <div>
                                        {{ $orders->onEachSide(0)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
