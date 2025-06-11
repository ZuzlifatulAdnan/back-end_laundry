@extends('layouts.app')

@section('title', 'Edit Mesin')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    {{-- @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'PLPP') --}}
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
                        <form action="{{ route('mesin.update', $mesin->id) }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Mesin</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- nama mesin -->
                                        <div class="form-group col-md-12 mb-3">
                                            <label for="nama" class="form-label">Nama Mesin</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                value="{{ old('nama', $mesin->nama) }}" placeholder="Masukkan Nama Mesin">
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- type  -->
                                    <div class="row">
                                        <div class="form-group col-md-12 mb-3">
                                            <label for="type" class="form-label">Type</label>
                                            <select id="type" class="form-control" name="type" required
                                                onchange="toggleAdditionalInputs()">
                                                <option value="">Pilih Type</option>
                                                <option value="Cuci"
                                                    {{ old('type', $mesin->type) == 'Cuci' ? 'selected' : '' }}>Cuci
                                                </option>
                                                <option value="Pengering"
                                                    {{ old('type', $mesin->type) == 'Pengering' ? 'selected' : '' }}>
                                                    Pengering
                                                </option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- status -->
                                    <div class="row">
                                        <div class="form-group col-md-12 mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select id="status" class="form-control" name="status" required
                                                onchange="toggleAdditionalInputs()">
                                                <option value="">Pilih Status</option>
                                                <option value="Ready"
                                                    {{ old('status', $mesin->status) == 'Ready' ? 'selected' : '' }}>Ready
                                                </option>
                                                <option value="Tidak Ready"
                                                    {{ old('status', $mesin->status) == 'Tidak Ready' ? 'selected' : '' }}>
                                                    Tidak Ready
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt-2">Ubah</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- @else
    @endif --}}

@endsection

@push('scripts')
@endpush
