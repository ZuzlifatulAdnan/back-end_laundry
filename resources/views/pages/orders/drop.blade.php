@extends('layouts.app')

@section('title', 'Tambah Order Drop Off')

@section('main')
<div class="main-content">
    @if (Auth::user()->role == 'Customer')
        <section class="section">
            <div class="section-body">
                @include('layouts.alert')

                {{-- Tampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('order.storeDropoff') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Order Drop Off</h4>
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
                                <label for="berat">Berat Cucian (kg)</label>
                                <input type="number" name="berat" id="berat" class="form-control"
                                    step="0.1" min="0" value="0" required>
                            </div>

                            <div class="form-group">
                                <label for="detergent">Jumlah Detergent</label>
                                <input type="number" name="detergent" id="detergent" class="form-control"
                                    min="0" value="0" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_ambil">Tanggal Ambil</label>
                                <input type="date" name="tanggal_ambil" id="tanggal_ambil" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="catatan">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="total_biaya_display">Total Biaya</label>
                                <input type="text" id="total_biaya_display" class="form-control" readonly placeholder="Rp 0">
                                <input type="hidden" name="total_biaya" id="total_biaya">
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
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
        const berat = parseFloat(document.getElementById('berat')?.value) || 0;
        const detergent = parseInt(document.getElementById('detergent')?.value) || 0;

        // Pastikan berat dan detergent tidak negatif
        const beratValid = berat < 0 ? 0 : berat;
        const detergentValid = detergent < 0 ? 0 : detergent;

        // Perhitungan
        const beratCost = (beratValid > 0) ? (beratValid / 7) * 31000 : 0;
        const detergentCost = detergentValid * 1000;
        const total = Math.round(beratCost + detergentCost); // dibulatkan ke atas jika perlu

        document.getElementById('total_biaya_display').value = formatRupiah(total);
        document.getElementById('total_biaya').value = total;
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['berat', 'detergent'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', hitungTotal);
        });
        hitungTotal(); // inisialisasi saat halaman dibuka
    });
</script>
@endpush
