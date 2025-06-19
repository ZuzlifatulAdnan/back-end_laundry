@extends('layouts.app')

@section('title', 'Detail Order')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Order</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Order</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nama User</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->user->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Jenis Layanan</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->service_type }}</dd>

                        <dt class="col-sm-3">Mesin</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->mesin->nama ?? '-' }}</dd>

                        <dt class="col-sm-3">Tanggal Order</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->tanggal_order }}</dd>

                        <dt class="col-sm-3">Jam Order</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->jam_order }}</dd>

                        <dt class="col-sm-3">Durasi (menit)</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->durasi }} menit</dd>

                        <dt class="col-sm-3">Koin</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->koin }} x Rp12.000</dd>

                        <dt class="col-sm-3">Berat</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->berat }} kg x Rp32.000</dd>

                        <dt class="col-sm-3">Detergen</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->detergent }} x Rp1.000</dd>

                        <dt class="col-sm-3">Catatan</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->catatan ?? '-' }}</dd>

                        <dt class="col-sm-3">Tanggal Ambil</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->tanggal_ambil ?? '-' }}</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{ $kelolaOrder->status }}</dd>

                        <dt class="col-sm-3">Total Biaya</dt>
                        <dd class="col-sm-9">Rp{{ number_format($kelolaOrder->total_biaya, 0, ',', '.') }}</dd>
                    </dl>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('kelolaOrder.edit', $kelolaOrder->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
