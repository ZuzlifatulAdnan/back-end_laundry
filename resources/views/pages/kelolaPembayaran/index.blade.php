@extends('layouts.app')

@section('title', 'Kelola Pembayaran')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <div class="section-body">
            <div class="row">
                <div class="col-12">@include('layouts.alert')</div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row align-items-end">
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('kelolaPembayaran.create') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-plus"></i> Tambah
                                        </a>
                                    </div>
                                    <div class="col-md-10">
                                        <form action="{{ route('kelolaPembayaran.index') }}" method="GET">
                                            <div class="form-row row">
                                                <div class="col-md-2 mb-2">
                                                    <select name="status" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Status</option>
                                                        <option value="Menunggu Pembayaran"
                                                            {{ request('status') == 'Menunggu Pembayaran' ? 'selected' : '' }}>
                                                            Menunggu Pembayaran</option>
                                                        <option value="Proses Pembayaran"
                                                            {{ request('status') == 'Proses Pembayaran' ? 'selected' : '' }}>
                                                            Proses Pembayaran</option>
                                                        <option value="Pembayaran Berhasil"
                                                            {{ request('status') == 'Pembayaran Berhasil' ? 'selected' : '' }}>
                                                            Pembayaran Berhasil</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <select name="bulan" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Bulan</option>
                                                        @foreach (range(1, 12) as $m)
                                                            <option value="{{ $m }}"
                                                                {{ request('bulan') == $m ? 'selected' : '' }}>
                                                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <select name="tahun" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Tahun</option>
                                                        @for ($y = date('Y'); $y >= 2022; $y--)
                                                            <option value="{{ $y }}"
                                                                {{ request('tahun') == $y ? 'selected' : '' }}>
                                                                {{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <input type="text" name="search" class="form-control"
                                                        placeholder="Cari Nama User" value="{{ request('search') }}">
                                                </div>
                                                <div class="col-md-1 mb-2">
                                                    <button class="btn btn-primary w-100" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix divider mb-3"></div>

                            {{-- Table --}}
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Layanan</th>
                                            <th>Tgl Order</th>
                                            <th>Status</th>
                                            <th>Jumlah Dibayar</th>
                                            <th>Bukti Bayar</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pembayaran as $index => $p)
                                            <tr>
                                                <td>{{ $pembayaran->firstItem() + $index }}</td>
                                                <td>{{ $p->order->user->name ?? '-' }}</td>
                                                <td>{{ $p->order->service_type ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($p->order->tanggal_order)->translatedFormat('d M Y') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $p->status == 'Pembayaran Berhasil' ? 'success' : ($p->status == 'Proses Pembayaran' ? 'warning' : 'danger') }}">
                                                        {{ $p->status }}
                                                    </span>
                                                </td>
                                                <td>Rp{{ number_format($p->jumlah_dibayar, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($p->bukti_bayar)
                                                        <!-- Tombol untuk membuka modal -->
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                            data-toggle="modal"
                                                            data-target="#buktiModal{{ $p->id }}">
                                                            Lihat
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="buktiModal{{ $p->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="modalLabel{{ $p->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="modalLabel{{ $p->id }}">Bukti
                                                                            Pembayaran</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img src="{{ asset('img/bukti_bayar/' . $p->bukti_bayar) }}"
                                                                            class="img-fluid rounded" alt="Bukti Bayar">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Tidak Ada</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('kelolaPembayaran.edit', $p->id) }}"
                                                            class="btn btn-sm btn-icon btn-primary m-1" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('kelolaPembayaran.show', $p->id) }}"
                                                            class="btn btn-sm btn-icon btn-info m-1" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('kelolaPembayaran.destroy', $p->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button
                                                                class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                                title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="card-footer d-flex justify-content-between">
                                    <span>
                                        Menampilkan {{ $pembayaran->firstItem() }} sampai
                                        {{ $pembayaran->lastItem() }} dari total {{ $pembayaran->total() }} data
                                    </span>
                                    <div class="paginate-sm">
                                        {{ $pembayaran->onEachSide(0)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div> {{-- end card-body --}}
                    </div> {{-- end card --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
