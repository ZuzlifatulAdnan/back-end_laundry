@extends('layouts.app')

@section('title', 'Ruangan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'PLPP')
        <div class="main-content">
            <section class="section">
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
                                    <h4>Data Ruangan</h4>
                                </div>
                                <div class="card-body">
                                    <div class="p-2">
                                        <div class="float-left">
                                            <div class="section-header-button">
                                                <a href="{{ route('ruangan.create') }}" class="btn btn-primary">Tambah</a>
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <form action="{{ route('ruangan.index') }}" method="GET">
                                                <div class="input-group">
                                                    <select name="gedung_id" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value=""
                                                            {{ request('gedung_id') == '' ? 'selected' : '' }}>Semua Gedung
                                                        </option>
                                                        @foreach ($gedungs as $gedung)
                                                            <option value="{{ $gedung->id }}"
                                                                {{ request('gedung_id') == $gedung->id ? 'selected' : '' }}>
                                                                {{ $gedung->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="status" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value=""
                                                            {{ request('status') == '' ? 'selected' : '' }}>
                                                            Semua Status</option>
                                                        <option value="Tersedia"
                                                            {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia
                                                        </option>
                                                        <option value="Tidak Tersedia"
                                                            {{ request('status') == 'Tidak Tersedia' ? 'selected' : '' }}>
                                                            Tidak
                                                            Tersedia</option>
                                                    </select>
                                                    <input type="text" class="form-control" placeholder="Search"
                                                        name="nama" value="{{ request('nama') }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary"><i
                                                                class="fas fa-search"></i></button>
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
                                                <th>Nama</th>
                                                <th>Gedung</th>
                                                <th class="text-center">Status</th>
                                                <th style="width: 5%" class="text-center">Action</th>
                                            </tr>
                                            @foreach ($ruangans as $index => $ruangan)
                                                <tr>
                                                    <td>
                                                        {{ $ruangans->firstItem() + $index }}
                                                    </td>
                                                    <td>
                                                        {{ $ruangan->nama }}
                                                    </td>
                                                    <td>
                                                        {{ $ruangan->gedung->nama }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($ruangan->status == 'Tersedia')
                                                            <span class="badge badge-success">{{ $ruangan->status }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ $ruangan->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('ruangan.edit', $ruangan) }}"
                                                                class="btn btn-sm btn-icon btn-primary m-1"><i
                                                                    class="fas fa-edit"></i></a>
                                                            <form action="{{ route('ruangan.destroy', $ruangan) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon m-1 btn-danger confirm-delete ">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <div class="card-footer d-flex justify-content-between">
                                            <span>
                                                Showing {{ $ruangans->firstItem() }}
                                                to {{ $ruangans->lastItem() }}
                                                of {{ $ruangans->total() }} entries
                                            </span>
                                            <div class="paginate-sm">
                                                {{ $ruangans->onEachSide(0)->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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
@endpush
