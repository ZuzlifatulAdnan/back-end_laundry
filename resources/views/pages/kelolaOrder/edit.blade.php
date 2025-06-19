@extends('layouts.app')

@section('title', 'Edit Order')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
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
                                    <select name="user_id" class="form-control select2">
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $order->user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- MESIN --}}
                                <div class="form-group col-md-6">
                                    <label for="mesin_id">Mesin</label>
                                    <select name="mesin_id" class="form-control select2">
                                        <option value="">-- Pilih Mesin --</option>
                                        @foreach ($mesins as $mesin)
                                            <option value="{{ $mesin->id }}"
                                                {{ $order->mesin_id == $mesin->id ? 'selected' : '' }}>
                                                {{ $mesin->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- SERVICE TYPE --}}
                                <div class="form-group col-md-6">
                                    <label for="service_type">Tipe Layanan</label>
                                    <select name="service_type" class="form-control">
                                        <option value="Self Service"
                                            {{ $order->service_type == 'Self Service' ? 'selected' : '' }}>Self Service
                                        </option>
                                        <option value="Drop Off" {{ $order->service_type == 'Drop Off' ? 'selected' : '' }}>
                                            Drop Off</option>
                                    </select>
                                </div>

                                {{-- TANGGAL & JAM --}}
                                <div class="form-group col-md-3">
                                    <label for="tanggal_order">Tanggal Order</label>
                                    <input type="date" name="tanggal_order" class="form-control"
                                        value="{{ $order->tanggal_order }}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="jam_order">Jam Order</label>
                                    <input type="time" name="jam_order" class="form-control"
                                        value="{{ $order->jam_order }}">
                                </div>

                                {{-- DURASI --}}
                                <div class="form-group col-md-3">
                                    <label for="durasi">Durasi (menit)</label>
                                    <input type="number" name="durasi" class="form-control" value="{{ $order->durasi }}">
                                </div>

                                {{-- KOIN --}}
                                <div class="form-group col-md-3">
                                    <label for="koin">Jumlah Koin</label>
                                    <input type="number" id="koin" name="koin" class="form-control"
                                        value="{{ $order->koin }}">
                                </div>

                                {{-- BERAT --}}
                                <div class="form-group col-md-3">
                                    <label for="berat">Berat (kg)</label>
                                    <input type="number" step="0.1" id="berat" name="berat" class="form-control"
                                        value="{{ $order->berat }}">
                                </div>

                                {{-- DETERGENT --}}
                                <div class="form-group col-md-3">
                                    <label for="detergent">Jumlah Detergen</label>
                                    <input type="number" id="detergent" name="detergent" class="form-control"
                                        value="{{ $order->detergent }}">
                                </div>

                                {{-- CATATAN --}}
                                <div class="form-group col-md-6">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" class="form-control">{{ $order->catatan }}</textarea>
                                </div>

                                {{-- TOTAL BIAYA --}}
                                <div class="form-group col-md-3">
                                    <label for="total_biaya">Total Biaya</label>
                                    <input type="number" id="total_biaya" name="total_biaya" class="form-control" readonly
                                        value="{{ $order->total_biaya }}">
                                </div>

                                {{-- STATUS --}}
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value=""> -- Pilih Status -- </option>
                                        <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>
                                            Diproses</option>
                                        <option value="Diterima" {{ $order->status == 'Diterima' ? 'selected' : '' }}>
                                            Diterima</option>
                                        <option value="Ditolak" {{ $order->status == 'Ditolak' ? 'selected' : '' }}>Ditolak
                                        </option>
                                        <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>
                                            Dibatalkan</option>
                                        <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-warning">Kembali</a>
                            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
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
