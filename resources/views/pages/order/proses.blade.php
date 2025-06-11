@extends('layouts.app')

@section('title', 'Daftar Proses Peminjaman')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'PLPP')
        <div class="main-content">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Pemesanan Diproses</h4>
                            </div>
                            <div class="card-body">
                                <div class="p-2">
                                    <div class="floatmt-4">
                                        <form action="{{ route('peminjaman.proses') }}" method="GET">
                                            <div class="row">
                                                <!-- Ruangan Filter -->
                                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                                    <div class="form-group">
                                                        <select name="ruangan" class="form-control select2"
                                                            onchange="this.form.submit()">
                                                            <option value="">Pilih Ruangan</option>
                                                            @foreach ($ruangans->groupBy('gedung.nama') as $gedungNama => $ruanganList)
                                                                <optgroup label="Gedung {{ $gedungNama }}">
                                                                    @foreach ($ruanganList as $ruangan)
                                                                        <option value="{{ $ruangan->id }}"
                                                                            {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                                                            {{ $ruangan->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Search Filter -->
                                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Search Name" name="nama"
                                                                value="{{ request('nama') }}">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary"><i
                                                                        class="fas fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="clearfix  divider mb-3"></div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-lg" id="table-1">
                                        <tr>
                                            <th style="width: 3%">No</th>
                                            <th>Nama Pemesan</th>
                                            <th class="text-center">Gedung || Ruangan</th>
                                            <th class="text-center">Tanggal Pemesanan</th>
                                            <th class="text-center">Status Pesanan</th>
                                            <th class="text-center">Waktu Pemesanan</th>
                                            <th class="text-center">Created At</th>
                                            <th style="width: 5%" class="text-center">Action</th>
                                        </tr>
                                        @foreach ($pemesanans as $index => $pemesanan)
                                            <tr>
                                                <td>{{ $pemesanans->firstItem() + $index }}</td>
                                                <td>{{ $pemesanan->user->name }}</td>
                                                <td class="text-center">{{ $pemesanan->ruangan->gedung->nama }} ||
                                                    {{ $pemesanan->ruangan->nama }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-{{ $pemesanan->status == 'Selesai'
                                                            ? 'success'
                                                            : ($pemesanan->status == 'Diterima'
                                                                ? 'primary'
                                                                : ($pemesanan->status == 'Diproses'
                                                                    ? 'warning'
                                                                    : 'danger')) }}">
                                                        {{ $pemesanan->status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">{{ $pemesanan->waktu_mulai }} -
                                                    {{ $pemesanan->waktu_selesai }}</td>
                                                <td class="text-center">{{ $pemesanan->created_at }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('peminjaman.editProses', $pemesanan->id) }}"
                                                            class="btn btn-sm btn-icon btn-primary m-1"><i
                                                                class="fas fa-edit"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="card-footer d-flex justify-content-between">
                                        <span>
                                            Showing {{ $pemesanans->firstItem() }}
                                            to {{ $pemesanans->lastItem() }}
                                            of {{ $pemesanans->total() }} entries
                                        </span>
                                        <div class="paginate-sm">
                                            {{ $pemesanans->onEachSide(0)->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    @endif

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset() }}"></script> --}}
    {{-- <script src="{{ asset() }}"></script> --}}
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
