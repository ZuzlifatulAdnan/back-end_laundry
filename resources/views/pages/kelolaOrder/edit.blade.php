@extends('layouts.app')

@section('title', 'Edit Order')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Admin')
            <section class="section">
                <div class="section-body">
                    <form action="{{ route('kelolaOrder.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Order</h4>
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
                                                    {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
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
                                                    data-durasi="{{ $mesin->durasi }}"
                                                    {{ old('mesin_id', $order->mesin_id) == $mesin->id ? 'selected' : '' }}>
                                                    {{ $mesin->nama }} - {{ $mesin->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- SERVICE TYPE --}}
                                    <div class="form-group col-md-6">
                                        <label for="service_type">Tipe Layanan</label>
                                        <select name="service_type" class="form-control">
                                            <option value=""> -- Pilih Tipe Layanan -- </option>
                                            <option value="Self Service" {{ old('service_type', $order->service_type) == 'Self Service' ? 'selected' : '' }}>Self Service</option>
                                            <option value="Drop Off" {{ old('service_type', $order->service_type) == 'Drop Off' ? 'selected' : '' }}>Drop Off</option>
                                        </select>
                                    </div>

                                    {{-- TANGGAL & JAM --}}
                                    <div class="form-group col-md-3">
                                        <label for="tanggal_order">Tanggal Order</label>
                                        <input type="date" class="form-control" name="tanggal_order"
                                            value="{{ old('tanggal_order', $order->tanggal_order) }}">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="jam_order">Jam Order</label>
                                        <input type="time" class="form-control" name="jam_order"
                                            value="{{ old('jam_order', $order->jam_order) }}">
                                    </div>

                                    {{-- DURASI --}}
                                    <div class="form-group col-md-3">
                                        <label for="durasi">Durasi (menit)</label>
                                        <input type="number" class="form-control" name="durasi" id="durasi"
                                            value="{{ old('durasi', $order->durasi) }}" readonly>
                                    </div>

                                    {{-- KOIN --}}
                                    <div class="form-group col-md-3">
                                        <label for="koin">Jumlah Koin</label>
                                        <input type="number" id="koin" class="form-control" name="koin"
                                            value="{{ old('koin', $order->koin) }}">
                                    </div>

                                    {{-- BERAT --}}
                                    <div class="form-group col-md-3">
                                        <label for="berat">Berat (kg)</label>
                                        <input type="number" step="0.1" id="berat" class="form-control"
                                            name="berat" value="{{ old('berat', $order->berat) }}">
                                    </div>

                                    {{-- DETERGENT --}}
                                    <div class="form-group col-md-3">
                                        <label for="detergent">Jumlah Detergen</label>
                                        <input type="number" id="detergent" class="form-control" name="detergent"
                                            value="{{ old('detergent', $order->detergent) }}">
                                    </div>

                                    {{-- CATATAN --}}
                                    <div class="form-group col-md-6">
                                        <label for="catatan">Catatan</label>
                                        <textarea name="catatan" class="form-control">{{ old('catatan', $order->catatan) }}</textarea>
                                    </div>

                                    {{-- TOTAL BIAYA --}}
                                    <div class="form-group col-md-3">
                                        <label for="total_biaya">Total Biaya</label>
                                        <input type="number" id="total_biaya" name="total_biaya" class="form-control"
                                            readonly value="{{ old('total_biaya', $order->total_biaya) }}">
                                    </div>

                                    {{-- STATUS --}}
                                    <div class="form-group col-md-3">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value=""> -- Pilih Status -- </option>
                                            <option value="Diterima" {{ old('status', $order->status) == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="Diproses" {{ old('status', $order->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Dibatalkan" {{ old('status', $order->status) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                            <option value="Ditunda" {{ old('status', $order->status) == 'Ditunda' ? 'selected' : '' }}>Ditunda</option>
                                            <option value="Selesai" {{ old('status', $order->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Perbarui</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        @else
            <div class="alert alert-danger">User role Anda tidak mendapatkan izin.</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            function hitungTotal() {
                const koin = parseInt($('#koin').val()) || 0;
                const berat = parseFloat($('#berat').val()) || 0;
                const detergent = parseInt($('#detergent').val()) || 0;
                const beratCost = berat > 0 ? (berat / 7) * 31000 : 0;
                const total = (koin * 12000) + beratCost + (detergent * 1000);
                $('#total_biaya').val(Math.round(total));
            }

            $('#koin, #berat, #detergent').on('input', hitungTotal);
            hitungTotal();

            $('#mesin_id').on('change', function () {
                const selectedDurasi = $(this).find(':selected').data('durasi');
                $('#durasi').val(selectedDurasi || '');
            });

            // Set durasi saat halaman dimuat (untuk edit)
            const initialDurasi = $('#mesin_id').find(':selected').data('durasi');
            if (initialDurasi) {
                $('#durasi').val(initialDurasi);
            }
        });
    </script>
@endpush
