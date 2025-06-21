@extends('layouts.app')

@section('title', 'Mesin')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
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
                            <form action="{{ route('mesin.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Tambah Mesin</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- nama -->
                                            <div class="form-group col-md-12 mb-3">
                                                <label for="nama" class="form-label">Nama Mesin</label>
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    value="{{ old('nama') }}"
                                                    placeholder="Masukkan Nama Mesin (NO Mesin)">
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Type  -->
                                        <div class="row">
                                            <div class="form-group col-md-12 mb-3">
                                                <label for="type" class="form-label">Type</label>
                                                <select id="type" class="form-control" name="type" required
                                                    onchange="toggleAdditionalInputs()">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Cuci">Cuci</option>
                                                    <option value="Pengering">Pengering</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- status --}}
                                        <div class="row">
                                            <div class="form-group col-md-12 mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select id="status" class="form-control" name="status" required
                                                    onchange="toggleAdditionalInputs()">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Ready">Ready</option>
                                                    <option value="Tidak Ready">Tidak Ready</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="alert alert-danger">
            User role Anda tidak mendapatkan izin.
        </div>
    @endif

@endsection

@push('scripts')
@endpush
