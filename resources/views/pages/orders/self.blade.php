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
                                    <label for="mesin_id">Pilih Mesin</label>
                                    <select id="mesin_id" name="mesin_id" class="form-control select" required>
                                        <option value="">-- Pilih Mesin --</option>
                                        @foreach ($mesins as $mesin)
                                            <option value="{{ $mesin->id }}"
                                                {{ request('mesin_id') == $mesin->id ? 'selected' : '' }}>
                                                {{ $mesin->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="durasi">Durasi (menit)</label>
                                    <input type="number" name="durasi" id="durasi" class="form-control" min="1"
                                        value="0" required>
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
                                <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
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
    <script>
        function hitungTotal() {
            const koin = parseInt(document.getElementById('koin')?.value) || 0;
            const total = koin * 12000;

            document.getElementById('total_biaya_display').value = formatRupiah(total);
            document.getElementById('total_biaya').value = total;
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.addEventListener('DOMContentLoaded', () => {
            const koinEl = document.getElementById('koin');
            if (koinEl) {
                koinEl.addEventListener('input', hitungTotal);
            }
            hitungTotal();
        });
    </script>
@endpush
