@extends('layouts.app')

@section('title', 'Tambah Order')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form action="{{ route('kelolaOrder.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- USER --}}
                                <div class="form-group col-md-6">
                                    <label for="user_id">User</label>
                                    <select name="user_id" id="user_id" class="form-control select2">
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- MESIN --}}
                                <div class="form-group col-md-6">
                                    <label for="mesin_id">Mesin</label>
                                    <select name="mesin_id" id="mesin_id" class="form-control select2">
                                        <option value="">-- Pilih Mesin --</option>
                                        @foreach ($mesins as $mesin)
                                            <option value="{{ $mesin->id }}"
                                                {{ old('mesin_id') == $mesin->id ? 'selected' : '' }}>
                                                {{ $mesin->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- SERVICE TYPE --}}
                                <div class="form-group col-md-6">
                                    <label for="service_type">Tipe Layanan</label>
                                    <select name="service_type" id="service_type" class="form-control">
                                        <option value=""> -- Pilih Tipe Layanan -- </option>
                                        <option value="Self Service"
                                            {{ old('service_type') == 'Self Service' ? 'selected' : '' }}>Self Service
                                        </option>
                                        <option value="Drop Off" {{ old('service_type') == 'Drop Off' ? 'selected' : '' }}>
                                            Drop Off</option>
                                    </select>
                                </div>

                                {{-- TANGGAL & JAM --}}
                                <div class="form-group col-md-3">
                                    <label for="tanggal_order">Tanggal Order</label>
                                    <input type="date" class="form-control" name="tanggal_order"
                                        value="{{ old('tanggal_order') }}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="jam_order">Jam Order</label>
                                    <input type="time" class="form-control" name="jam_order"
                                        value="{{ old('jam_order') }}">
                                </div>

                                {{-- DURASI --}}
                                <div class="form-group col-md-3">
                                    <label for="durasi">Durasi (menit)</label>
                                    <input type="number" class="form-control" name="durasi" value="{{ old('durasi') }}">
                                </div>

                                {{-- KOIN --}}
                                <div class="form-group col-md-3">
                                    <label for="koin">Jumlah Koin</label>
                                    <input type="number" id="koin" class="form-control" name="koin"
                                        value="{{ old('koin') }}">
                                </div>

                                {{-- BERAT --}}
                                <div class="form-group col-md-3">
                                    <label for="berat">Berat (kg)</label>
                                    <input type="number" step="0.1" id="berat" class="form-control" name="berat"
                                        value="{{ old('berat') }}">
                                </div>

                                {{-- DETERGENT --}}
                                <div class="form-group col-md-3">
                                    <label for="detergent">Jumlah Detergen</label>
                                    <input type="number" id="detergent" class="form-control" name="detergent"
                                        value="{{ old('detergent') }}">
                                </div>

                                {{-- CATATAN --}}
                                <div class="form-group col-md-6">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                                </div>

                                {{-- TOTAL BIAYA --}}
                                <div class="form-group col-md-3">
                                    <label for="total_biaya">Total Biaya</label>
                                    <input type="number" id="total_biaya" name="total_biaya" class="form-control" readonly
                                        value="{{ old('total_biaya') }}">
                                </div>

                                {{-- STATUS --}}
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value=""> -- Pilih Status -- </option>
                                        <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>
                                            Diterima </option>
                                        <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>
                                            Diproses</option>
                                        <option value="Dibatalkan" {{ old('status') == 'Dibatalkan' ? 'selected' : '' }}>
                                            Dibatalkan</option>
                                        <option value="Ditunda" {{ old('status') == 'Ditunda' ? 'selected' : '' }}>
                                            Ditunda</option>
                                        <option value="Ditunda" {{ old('status') == 'Ditunda' ? 'selected' : '' }}>
                                            Ditunda</option>
                                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();

        function hitungTotal() {
            const koin = parseInt(document.getElementById('koin')?.value) || 0;
            const berat = parseFloat(document.getElementById('berat')?.value) || 0;
            const detergent = parseInt(document.getElementById('detergent')?.value) || 0;

            const total = (koin * 12000) + (berat * 32000) + (detergent * 1000);

            document.getElementById('total_biaya').value = total;
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['koin', 'berat', 'detergent'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', hitungTotal);
                }
            });
        });
    </script>
@endpush
