@extends('layouts.app')

@section('title', 'Edit Ruangan')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'PLPP')
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
                            <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card">
                                    <div class="card-header">
                                        <h4>Edit Ruangan</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Nama Ruangan (Read-only) -->
                                            <div class="form-group col-md-4">
                                                <label for="nama">Nama Ruangan</label>
                                                <input type="text"
                                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                    name="nama" value="{{ $ruangan->nama }}" readonly required>
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Gedung -->
                                            <div class="form-group col-md-4">
                                                <label for="gedung_id">Gedung</label>
                                                <select id="gedung_id" class="form-control" name="gedung_id" required>
                                                    @foreach ($gedungs as $gedung)
                                                        <option value="{{ $gedung->id }}" data-nama="{{ $gedung->nama }}"
                                                            {{ $ruangan->gedung_id == $gedung->id ? 'selected' : '' }}>
                                                            {{ $gedung->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Lantai -->
                                            <div class="form-group col-md-4">
                                                <label for="lantai">Lantai</label>
                                                <select id="lantai" class="form-control" name="lantai">
                                                    <option value="">Pilih Lantai</option>
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $ruangan->lantai == $i ? 'selected' : '' }}>
                                                            Lantai {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <!-- Nomor Ruangan -->
                                            <div class="form-group col-md-4">
                                                <label for="nomor_ruangan">Nomor Ruangan</label>
                                                <input type="text" class="form-control" id="nomor_ruangan"
                                                    name="nomor_ruangan" value="{{ $ruangan->nomor_ruangan }}" required>
                                            </div>

                                            <!-- Status -->
                                            <div class="form-group col-md-4">
                                                <label for="status">Status</label>
                                                <select id="status" class="form-control" name="status" required>
                                                    <option value="Tersedia"
                                                        {{ $ruangan->status == 'Tersedia' ? 'selected' : '' }}>
                                                        Tersedia
                                                    </option>
                                                    <option value="Tidak Tersedia"
                                                        {{ $ruangan->status == 'Tidak Tersedia' ? 'selected' : '' }}>
                                                        Tidak Tersedia
                                                    </option>
                                                </select>
                                            </div>

                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="alert alert-danger">Anda tidak memiliki akses ke halaman ini.</div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const gedungSelect = document.getElementById("gedung_id");
            const lantaiSelect = document.getElementById("lantai");
            const nomorRuanganInput = document.getElementById("nomor_ruangan");
            const namaInput = document.getElementById("nama");

            function updateNamaRuangan() {
                const gedungNama = gedungSelect.options[gedungSelect.selectedIndex].dataset.nama || "";
                const lantai = lantaiSelect.value ? "." + lantaiSelect.value : "";
                const nomorRuangan = nomorRuanganInput.value ? "." + nomorRuanganInput.value : "";
                namaInput.value = gedungNama ? `${gedungNama}${lantai}${nomorRuangan}` : "";
            }

            gedungSelect.addEventListener("change", updateNamaRuangan);
            lantaiSelect.addEventListener("change", updateNamaRuangan);
            nomorRuanganInput.addEventListener("input", updateNamaRuangan);
        });
    </script>
@endpush
