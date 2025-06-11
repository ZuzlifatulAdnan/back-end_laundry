@extends('layouts.app')

@section('title', 'Order')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush

@section('main')
    {{-- @if (Auth::user()->role == 'Admin') --}}
    <div class="main-content">
        <div class="section-body">
            <div class="row">
                <div class="col-12">

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                <div class="float mt-4">
                                    <form action="{{ route('order.index') }}" method="GET">
                                        <div class="row">
                                            <!-- Bulan Filter -->
                                            <div class="col-12 col-md-3 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <select name="bulan" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Pilih Bulan</option>
                                                        @foreach ($months as $key => $month)
                                                            <option value="{{ $key }}"
                                                                {{ request('bulan') == $key ? 'selected' : '' }}>
                                                                {{ $month }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Tahun Filter -->
                                            <div class="col-12 col-md-3 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <select name="tahun" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Pilih Tahun</option>
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ request('tahun') == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Status Filter -->
                                            <div class="col-12 col-md-3 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <select name="status" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Pilih Status</option>
                                                        @foreach ($statusOptions as $status)
                                                            <option value="{{ $status }}"
                                                                {{ request('status') == $status ? 'selected' : '' }}>
                                                                {{ $status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Sort By -->
                                            <div class="col-12 col-md-3 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <select name="sort" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Urutkan Berdasarkan</option>
                                                        <option value="desc"
                                                            {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru
                                                            (DESC)</option>
                                                        <option value="asc"
                                                            {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama (ASC)
                                                        </option>
                                                    </select>
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
                                        <th class="text-center">Tanggal order</th>
                                        <th class="text-center">Status Pesanan</th>
                                        <th class="text-center">Waktu order</th>
                                        <th style="width: 5%" class="text-center">Action</th>
                                    </tr>
                                    @foreach ($orders as $index => $order)
                                        <tr>
                                            <td>
                                                {{ $orders->firstItem() + $index }}
                                            </td>
                                            <td>
                                                {{ $order->user->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $order->ruangan->gedung->nama }} ||
                                                {{ $order->ruangan->nama }}
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($order->status == 'Selesai')
                                                    <span class="badge badge-success">{{ $order->status }}</span>
                                                @elseif ($order->status == 'Diterima')
                                                    <span class="badge badge-primary">{{ $order->status }}</span>
                                                @elseif ($order->status == 'Diproses')
                                                    <span class="badge badge-warning">{{ $order->status }}</span>
                                                @elseif ($order->status == 'Ditolak')
                                                    <span class="badge badge-danger">{{ $order->status }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $order->waktu_mulai }} - {{ $order->waktu_selesai }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('order.edit', $order) }}"
                                                        class="btn btn-sm btn-icon btn-primary m-1"><i
                                                            class="fas fa-edit"></i></a>
                                                    <a href="{{ route('order.show', $order) }}"
                                                        class="btn btn-sm btn-icon btn-info m-1"><i
                                                            class="fas fa-eye"></i></a>
                                                    <form action="{{ route('order.destroy', $order) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon m-1 btn-danger confirm-delete ">
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
                                        Showing {{ $orders->firstItem() }}
                                        to {{ $orders->lastItem() }}
                                        of {{ $orders->total() }} entries
                                    </span>
                                    <div class="paginate-sm">
                                        {{ $orders->onEachSide(0)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @else
    @endif --}}

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
