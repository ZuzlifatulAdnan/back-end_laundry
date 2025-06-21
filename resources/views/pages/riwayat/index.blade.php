@extends('layouts.app')

@section('title', 'Riwayat Order')

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Customer')
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
                            {{-- Filter & Tambah --}}
                            <div class="mb-3">
                                <div class="row align-items-end">
                                    <div class="col-md-10">
                                        <form action="{{ route('riwayat.index') }}" method="GET">
                                            <div class="form-row row mb-3">
                                                {{-- Status Order --}}
                                                <div class="col-md-2 mb-2">
                                                    <select name="status" class="form-control">
                                                        <option value="">Status Order</option>
                                                        <option value="Diproses"
                                                            {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses
                                                        </option>
                                                        <option value="Selesai"
                                                            {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                                                        </option>
                                                        <option value="Dibatalkan"
                                                            {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>
                                                            Dibatalkan
                                                        </option>
                                                        <option value="Ditunda"
                                                            {{ request('status') == 'Ditunda' ? 'selected' : '' }}>
                                                            Ditunda
                                                        </option>
                                                        <option value="Diterima"
                                                            {{ request('status') == 'Diterima' ? 'selected' : '' }}>
                                                            Diterima
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- Status Pembayaran --}}
                                                <div class="col-md-2 mb-2">
                                                    <select name="status_pembayaran" class="form-control">
                                                        <option value="">Status Pembayaran</option>
                                                        <option value="Pembayaran Berhasil"
                                                            {{ request('status_pembayaran') == 'Pembayaran Berhasil' ? 'selected' : '' }}>
                                                            Pembayaran Berhasil</option>
                                                        <option value="Proses Pembayaran"
                                                            {{ request('status_pembayaran') == 'Proses Pembayaran' ? 'selected' : '' }}>
                                                            Proses Pembayaran</option>
                                                        <option value="Menunggu Pembayaran"
                                                            {{ request('status_pembayaran') == 'Menunggu Pembayaran' ? 'selected' : '' }}>
                                                            Menunggu Pembayaran</option>
                                                    </select>
                                                </div>

                                                {{-- Bulan --}}
                                                <div class="col-md-2 mb-2">
                                                    <select name="bulan" class="form-control">
                                                        <option value="">Semua Bulan</option>
                                                        @foreach (range(1, 12) as $m)
                                                            <option value="{{ $m }}"
                                                                {{ request('bulan') == $m ? 'selected' : '' }}>
                                                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Tahun --}}
                                                <div class="col-md-2 mb-2">
                                                    <select name="tahun" class="form-control">
                                                        <option value="">Semua Tahun</option>
                                                        @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                                            <option value="{{ $y }}"
                                                                {{ request('tahun') == $y ? 'selected' : '' }}>
                                                                {{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                {{-- Sort By --}}
                                                <div class="col-md-2 mb-2">
                                                    <select name="sort" class="form-control">
                                                        <option value="desc"
                                                            {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru
                                                        </option>
                                                        <option value="asc"
                                                            {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- Search --}}
                                                <div class="col-md-2 mb-2">
                                                    <div class="input-group">
                                                        <input type="text" name="search" class="form-control"
                                                            placeholder="Cari Nama User" value="{{ request('search') }}">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="submit"><i
                                                                    class="fas fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Order</th>
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
                                                <td>{{ ucfirst($order->no_order) }}</td>
                                                <td>{{ ucfirst($order->service_type) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') }}
                                                </td>
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
            @else
                <div class="alert alert-danger">
                    User role Anda tidak mendapatkan izin.
                </div>
            @endif
        </div>
    @endsection
