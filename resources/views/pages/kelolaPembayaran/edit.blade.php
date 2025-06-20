@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <form action="{{ route('kelolaPembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="section-body">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Pembayaran</h4>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label>Order</label>
                                <select name="order_id" class="form-control select2" required>
                                    <option value="">-- Pilih Order --</option>
                                    @foreach ($orders as $order)
                                        <option value="{{ $order->id }}"
                                            data-jumlah="{{ $order->total_biaya }}"
                                            {{ old('order_id', $pembayaran->order_id) == $order->id ? 'selected' : '' }}>
                                            {{ $order->user->name ?? 'User' }} - {{ $order->service_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="Transfer Bank" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="QRIS" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="Tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Jumlah Dibayar</label>
                                <input type="number" name="jumlah_dibayar" class="form-control" required
                                       value="{{ old('jumlah_dibayar', $pembayaran->jumlah_dibayar) }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Menunggu Pembayaran" {{ old('status', $pembayaran->status) == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="Proses Pembayaran" {{ old('status', $pembayaran->status) == 'Proses Pembayaran' ? 'selected' : '' }}>Proses Pembayaran</option>
                                    <option value="Pembayaran Berhasil" {{ old('status', $pembayaran->status) == 'Pembayaran Berhasil' ? 'selected' : '' }}>Pembayaran Berhasil</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Bukti Bayar (Opsional)</label>
                                <input type="file" name="bukti_bayar" accept="image/*" class="form-control" onchange="previewImage(event)">
                                <br>
                                @if ($pembayaran->bukti_bayar)
                                    <p class="mt-2">Bukti Lama:</p>
                                    <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}" alt="Bukti Lama" style="max-width: 300px; display:block; margin-bottom:10px;">
                                @endif
                                <img id="preview-bukti" src="#" alt="Preview Bukti Baru"
                                     style="display: none; max-width: 300px; margin-top: 10px;" />
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('kelolaPembayaran.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();

        // Isi otomatis jumlah bayar saat pilih order
        $('select[name="order_id"]').on('change', function () {
            let selectedOption = $(this).find('option:selected');
            let jumlah = selectedOption.data('jumlah');
            $('input[name="jumlah_dibayar"]').val(jumlah);
        });

        // Preview gambar baru
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-bukti');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
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
