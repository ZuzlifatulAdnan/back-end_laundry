@extends('layouts.app')

@section('title', 'Mesin')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
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
                                    <h4>Data Mesin</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-2 mb-2">
                                                <a href="{{ route('mesin.create') }}" class="btn btn-primary w-100">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </a>
                                            </div>
                                            <div class="col-md-10">
                                                <form action="{{ route('mesin.index') }}" method="GET">
                                                    <div class="form-row row">
                                                        <div class="col-md-3 mb-2">
                                                            <select name="type" class="form-control"
                                                                onchange="this.form.submit()">
                                                                <option value=""
                                                                    {{ request('type') == '' ? 'selected' : '' }}>Semua Type
                                                                </option>
                                                                <option value="Cuci"
                                                                    {{ request('type') == 'Cuci' ? 'selected' : '' }}>Cuci
                                                                </option>
                                                                <option value="Pengering"
                                                                    {{ request('type') == 'Pengering' ? 'selected' : '' }}>
                                                                    Pengering</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <select name="status" class="form-control"
                                                                onchange="this.form.submit()">
                                                                <option value=""
                                                                    {{ request('status') == '' ? 'selected' : '' }}>Semua
                                                                    Status
                                                                </option>
                                                                <option value="Aktif"
                                                                    {{ request('status') == 'Aktif' ? 'selected' : '' }}>
                                                                    Aktif
                                                                </option>
                                                                <option value="Nonaktif"
                                                                    {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>
                                                                    Nonaktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <input type="text" name="name" class="form-control"
                                                                placeholder="Search" value="{{ request('name') }}">
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <button class="btn btn-primary w-100" type="submit">
                                                                <i class="fas fa-search"></i> Cari
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix  divider mb-3"></div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-lg" id="table-1">
                                            <tr>
                                                <th style="width: 3%">No</th>
                                                <th>Nama</th>
                                                <th>Type</th>
                                                <th>Durasi(Menit)</th>
                                                <th>Status</th>
                                                <th style="width: 5%" class="text-center">Action</th>
                                            </tr>
                                            @foreach ($mesins as $index => $mesin)
                                                <tr>
                                                    <td>
                                                        {{ $mesins->firstItem() + $index }}
                                                    </td>
                                                    <td>
                                                        {{ $mesin->nama }}
                                                    </td>
                                                    <td>
                                                        {{ $mesin->type }}
                                                    </td>
                                                    <td>
                                                        {{ $mesin->durasi }}
                                                    </td>
                                                    <td>
                                                        {{ $mesin->status }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('mesin.edit', $mesin) }}"
                                                                class="btn btn-sm btn-icon btn-primary m-1"
                                                                data-bs-toggle="tooltip" title="edit"><i
                                                                    class="fas fa-edit"></i></a>
                                                            <form action="{{ route('mesin.destroy', $mesin) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                                    data-bs-toggle="tooltip" title="Hapus">
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
                                                Showing {{ $mesins->firstItem() }}
                                                to {{ $mesins->lastItem() }}
                                                of {{ $mesins->total() }} entries
                                            </span>
                                            <div class="paginate-sm">
                                                {{ $mesins->onEachSide(0)->links() }}
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
        <div class="alert alert-danger">
            User role Anda tidak mendapatkan izin.
        </div>
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
