@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Admin')
            <section class="section">
                <form action="{{ route('kelolaPembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="section-body">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Tambah Pembayaran</h4>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-md-6">
                                    <label>Order</label>
                                    <select name="order_id" class="form-control select2" required>
                                        <option value="">-- Pilih Order --</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}" data-jumlah="{{ $order->total_biaya }}">
                                                {{ $order->user->name ?? 'User' }} - {{ $order->service_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Metode Pembayaran</label>
                                    <select name="metode_pembayaran"
                                        class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="Transfer Bank"
                                            {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer
                                            Bank
                                        </option>
                                        <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>
                                            QRIS
                                        </option>
                                        <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>
                                            Tunai
                                        </option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Jumlah Dibayar</label>
                                    <input type="number" name="jumlah_dibayar" class="form-control" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                        <option value="Proses Pembayaran">Proses Pembayaran</option>
                                        <option value="Pembayaran Berhasil">Pembayaran Berhasil</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Bukti Bayar (Opsional)</label>
                                    <input type="file" name="bukti_bayar" class="form-control"
                                        onchange="previewImage(event)">
                                    <br>
                                    <img id="preview-bukti" src="#" alt="Preview Bukti Bayar"
                                        style="display: none; max-width: 300px; margin-top: 10px;" />
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('kelolaPembayaran.index') }}" class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
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
        $('.select2').select2();

        // Isi otomatis jumlah bayar saat pilih order
        $('select[name="order_id"]').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            let jumlah = selectedOption.data('jumlah');
            $('input[name="jumlah_dibayar"]').val(jumlah);
        });

        // Preview gambar bukti bayar
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-bukti');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
