@extends('layouts.app')

@section('title', 'Peminjaman')

@push('style')
<!-- Tambahkan CSS tambahan di sini jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Peminjaman</h4>
                    </div>
                    <div class="card-body">
                        @if ($pemesanan)
                            <div class="row">
                                @php
                                    $user = $pemesanan->user ?? null;
                                    $role = $user->role ?? null;
                                @endphp

                                <div class="col-md-{{ $role == 'Mahasiswa' ? 4 : 6 }} detail-value">
                                    <span class="detail-header">
                                        Nama Pemesan{{ $role == 'Mahasiswa' ? ' || NPM' : ($role == 'Dosen' ? ' || NIP' : '') }}:
                                    </span>
                                    <p>
                                        {{ $user->name ?? 'Nama tidak tersedia' }}
                                        @if ($role == 'Mahasiswa')
                                            || {{ $user->npm ?? '-' }}
                                        @elseif ($role == 'Dosen')
                                            || {{ $user->nip ?? '-' }}
                                        @endif
                                    </p>
                                </div>

                                @if ($role == 'Mahasiswa' && $pemesanan->ukm)
                                    <div class="col-md-4 detail-value">
                                        <span class="detail-header">UKM:</span>
                                        <p>{{ $pemesanan->ukm->nama ?? 'Data UKM tidak tersedia' }}</p>
                                    </div>
                                @endif

                                @if ($pemesanan->ruangan && $pemesanan->ruangan->gedung)
                                    <div class="col-md-{{ $role == 'Mahasiswa' ? 4 : 6 }} detail-value">
                                        <span class="detail-header">Ruangan || Gedung:</span>
                                        <p>
                                            {{ $pemesanan->ruangan->nama ?? 'Data ruangan tidak tersedia' }} || 
                                            {{ $pemesanan->ruangan->gedung->nama ?? 'Data gedung tidak tersedia' }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Tanggal Pesan:</span>
                                    <p>{{ $pemesanan->tanggal_pesan ? \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d/m/Y') : 'Tanggal tidak tersedia' }}</p>
                                </div>
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Waktu Mulai - Waktu Selesai:</span>
                                    <p>{{ $pemesanan->waktu_mulai ?? '-' }} - {{ $pemesanan->waktu_selesai ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Tujuan:</span>
                                    <p>{{ $pemesanan->tujuan ?? 'Tujuan tidak tersedia' }}</p>
                                </div>
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Status:</span>
                                    <p>{{ $pemesanan->status ?? 'Status tidak tersedia' }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-warning">Back</a>
                            </div>
                        @else
                            <p class="text-danger">Data pemesanan tidak ditemukan.</p>
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-warning">Back</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
