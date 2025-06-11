@extends('layouts.app')

@section('title', 'Peminjaman')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('peminjaman.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4>Tambah Peminjaman</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="ruangan_id" class="form-label">Ruangan</label>
                                            <select id="ruangan_id"
                                                class="form-control select2 @error('ruangan_id') is-invalid @enderror"
                                                name="ruangan_id" required>
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($ruangans->groupBy('gedung.nama') as $gedungNama => $ruanganList)
                                                    <optgroup label="Gedung {{ $gedungNama }}">
                                                        @foreach ($ruanganList as $ruangan)
                                                            <option value="{{ $ruangan->id }}"
                                                                {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                                                {{ $ruangan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            @error('ruangan_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="tanggal_pesan" class="form-label">Tanggal Pesan</label>
                                            <input type="date"
                                                class="form-control @error('tanggal_pesan') is-invalid @enderror"
                                                id="tanggal_pesan" name="tanggal_pesan" value="{{ old('tanggal_pesan') }}">
                                            @error('tanggal_pesan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4 mb-3">
                                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                            <input type="time" id="waktu_mulai" name="waktu_mulai"
                                                class="form-control @error('waktu_mulai') is-invalid @enderror"
                                                min="07:00" max="19:00" required list="jam-list">
                                            @error('waktu_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-3">
                                            <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                                            <input type="time" id="waktu_selesai" name="waktu_selesai"
                                                class="form-control @error('waktu_selesai') is-invalid @enderror"
                                                min="09:00" max="21:00" required list="jam-list">
                                            @error('waktu_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-3">
                                            <label for="tujuan" class="form-label">Tujuan</label>
                                            <input type="text" class="form-control @error('tujuan') is-invalid @enderror"
                                                id="tujuan" name="tujuan" value="{{ old('tujuan') }}"
                                                placeholder="Masukkan Tujuan">
                                            @error('tujuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <datalist id="jam-list">
                                            @for ($jam = 7; $jam <= 21; $jam++)
                                                <option value="{{ str_pad($jam, 2, '0', STR_PAD_LEFT) }}:00">
                                                <option value="{{ str_pad($jam, 2, '0', STR_PAD_LEFT) }}:30">
                                            @endfor
                                        </datalist>
                                    </div>
                                    <div class="row">
                                        @if (Auth::user()->role == 'Mahasiswa')
                                            <div class="form-group col-md-4 mb-3">
                                                <label for="ukm_id" class="form-label">UKM</label>
                                                <select id="ukm_id"
                                                    class="form-control select2 @error('ukm_id') is-invalid @enderror"
                                                    name="ukm_id">
                                                    <option value="">Pilih UKM</option>
                                                    @foreach ($ukms as $ukm)
                                                        <option value="{{ $ukm->id }}"
                                                            {{ old('ukm_id') == $ukm->id ? 'selected' : '' }}>
                                                            {{ $ukm->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('ukm_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @else
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-3">
                                            <img src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('img/avatar/avatar-1.png') }}"
                                                class="img-thumbnail" width="200" height="200" alt="User Image"
                                                data-toggle="modal" data-target="#profileModal">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt-2">Ajukan Peminjaman</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal untuk menampilkan gambar -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Foto Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('img/avatar/avatar-1.png') }}"
                        class="img-fluid rounded" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let waktuMulai = document.getElementById('waktu_mulai');
            let waktuSelesai = document.getElementById('waktu_selesai');

            function setWaktuSelesai() {
                let mulai = waktuMulai.value;

                if (!mulai) return;

                let jamMulai = parseInt(mulai.split(':')[0]);
                let menitMulai = parseInt(mulai.split(':')[1]);

                // Hitung waktu selesai (+2 jam)
                let jamSelesai = jamMulai + 2;
                if (jamSelesai > 21) jamSelesai = 21; // Maksimal jam 21:00

                let waktuSelesaiValue =
                    `${jamSelesai.toString().padStart(2, '0')}:${menitMulai.toString().padStart(2, '0')}`;
                waktuSelesai.value = waktuSelesaiValue;
            }

            // Event listener untuk perubahan waktu mulai
            waktuMulai.addEventListener("change", setWaktuSelesai);
        });
    </script>
@endpush
