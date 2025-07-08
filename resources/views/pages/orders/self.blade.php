@extends('layouts.app')

@section('title', 'Tambah Order Self Service')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Customer')
            <section class="section">
                <div class="section-body">
                    @include('layouts.alert')

                    <form action="{{ route('order.storeSelfservice') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Order Self Service</h4>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="tanggal_order">Tanggal Order</label>
                                    <input type="date" name="tanggal_order" id="tanggal_order" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="jam_order">Jam Order</label>
                                    <input type="time" name="jam_order" id="jam_order" class="form-control"
                                        value="{{ date('H:i') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="mesin_id">Mesin</label>
                                    <select name="mesin_id" id="mesin_id" class="form-control select2">
                                        <option value="">-- Pilih Mesin --</option>
                                        @foreach ($mesins as $mesin)
                                            <option value="{{ $mesin->id }}" data-durasi="{{ $mesin->durasi }}"
                                                {{ old('mesin_id') == $mesin->id ? 'selected' : '' }}>
                                                {{ $mesin->nama }} - {{ $mesin->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="durasi">Durasi (menit)</label>
                                    <input type="number" class="form-control" name="durasi" id="durasi"
                                        value="{{ old('durasi') }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="koin">Jumlah Koin</label>
                                    <input type="number" name="koin" id="koin" class="form-control" min="0"
                                        value="0" required>
                                </div>

                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="total_biaya_display">Total Biaya</label>
                                    <input type="text" id="total_biaya_display" class="form-control bg-light" readonly>
                                    <input type="hidden" name="total_biaya" id="total_biaya">
                                </div>

                                <button type="submit" class="btn btn-danger">Simpan</button>
                                <a href="javascript:history.back()" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        @else
            <div class="alert alert-danger">
                User role Anda tidak mendapatkan izin.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Hitung total biaya
            function hitungTotal() {
                const koin = parseInt($('#koin').val()) || 0;
                const total = koin * 12000;
                $('#total_biaya').val(Math.round(total));
                $('#total_biaya_display').val('Rp ' + total.toLocaleString('id-ID'));
            }

            $('#koin').on('input', hitungTotal);
            hitungTotal();

            // Auto isi durasi dari mesin
            $('#mesin_id').on('change', function() {
                const selectedDurasi = $(this).find(':selected').data('durasi');
                $('#durasi').val(selectedDurasi || '');
            });

            // Isi durasi awal jika ada mesin terpilih
            const initialDurasi = $('#mesin_id').find(':selected').data('durasi');
            if (initialDurasi) {
                $('#durasi').val(initialDurasi);
            }
        });
    </script>
@endpush
