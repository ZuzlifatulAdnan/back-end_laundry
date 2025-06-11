@extends('layouts.app')

@section('title', 'Peminjaman')

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'PLPP')
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
                                    @php $role = optional($pemesanan->user)->role; @endphp

                                    <!-- Foto User -->
                                    <div class="col-md-3 text-center">
                                        <img src="{{ optional($pemesanan->user)->image ? asset('img/user/' . optional($pemesanan->user)->image) : asset('img/avatar/avatar-1.png') }}" 
                                             class="img-thumbnail rounded" 
                                             alt="Foto User"
                                             style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;"
                                             data-toggle="modal" 
                                             data-target="#modalFoto">
                                    </div>

                                    <div class="col-md-{{ $role == 'Mahasiswa' ? 4 : 6 }} detail-value">
                                        <span class="detail-header">
                                            Nama Pemesan{{ $role == 'Mahasiswa' ? ' || NPM' : ($role == 'Dosen' ? ' || NIP' : '') }}:
                                        </span>
                                        <p>
                                            {{ optional($pemesanan->user)->name ?? 'Nama tidak tersedia' }}
                                            @if ($role == 'Mahasiswa')
                                                || {{ optional($pemesanan->user)->npm ?? '-' }}
                                            @elseif($role == 'Dosen')
                                                || {{ optional($pemesanan->user)->nip ?? '-' }}
                                            @endif
                                        </p>
                                    </div>

                                    @if ($role == 'Mahasiswa' && $pemesanan->ukm)
                                        <div class="col-md-4 detail-value">
                                            <span class="detail-header">UKM:</span>
                                            <p>{{ optional($pemesanan->ukm)->nama }}</p>
                                        </div>
                                    @endif

                                    @if ($pemesanan->ruangan)
                                        <div class="col-md-{{ $role == 'Mahasiswa' ? 4 : 6 }} detail-value">
                                            <span class="detail-header">Ruangan || Gedung:</span>
                                            <p>
                                                {{ optional($pemesanan->ruangan)->nama ?? 'Data ruangan tidak tersedia' }} ||
                                                {{ optional(optional($pemesanan->ruangan)->gedung)->nama ?? 'Data gedung tidak tersedia' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6 detail-value">
                                        <span class="detail-header">Tanggal Pesan:</span>
                                        <p>{{ optional($pemesanan->tanggal_pesan)->format('d/m/Y') ?? 'Tanggal tidak tersedia' }}</p>
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
                                    <a href="{{ route('pemesanan.edit', $pemesanan->id) }}" class="btn btn-primary">Edit</a>
                                    <a href="{{ route('pemesanan.index') }}" class="btn btn-warning">Back</a>
                                </div>
                            @else
                                <p class="text-danger">Data pemesanan tidak ditemukan.</p>
                                <a href="{{ route('pemesanan.index') }}" class="btn btn-warning">Back</a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Modal untuk Menampilkan Foto -->
        <div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFotoLabel">Foto User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ optional($pemesanan->user)->image ? asset('img/user/' . optional($pemesanan->user)->image) : asset('img/avatar/avatar-1.png') }}" 
                             class="img-fluid rounded" 
                             alt="Foto User">
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <p class="text-danger text-center">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    <a href="{{ route('beranda.index') }}" class="btn btn-warning">Kembali ke Beranda</a>
                </div>
            </section>
        </div>
    @endif
@endsection
