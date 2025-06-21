@extends('layouts.app')

@section('title', 'Order Diproses')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Admin')
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')

                        <div class="card">
                            <div class="card-header">
                                <h4>Data Order Sedang Diproses</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Pembayaran</th>
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
                                            @forelse ($pembayarans as $pembayaran)
                                                <tr>
                                                    <td>{{ $loop->iteration + $pembayarans->firstItem() - 1 }}</td>
                                                    <td>{{ $pembayaran->no_pembayaran ?? '-' }}</td>
                                                    <td>{{ $pembayaran->order->user->name ?? '-' }}</td>
                                                    <td>{{ $pembayaran->order->service_type ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($pembayaran->order->tanggal_order)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $pembayaran->status == 'Pembayaran Berhasil' ? 'success' : ($pembayaran->status == 'Proses Pembayaran' ? 'warning' : 'danger') }}">
                                                            {{ $pembayaran->status }}
                                                        </span>
                                                    </td>
                                                    <td>Rp{{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($pembayaran->bukti_bayar)
                                                            <button class="btn btn-sm btn-outline-info" data-toggle="modal"
                                                                data-target="#buktiModal{{ $pembayaran->id }}">
                                                                Lihat
                                                            </button>
                                                            <div class="modal fade" id="buktiModal{{ $pembayaran->id }}"
                                                                tabindex="-1" role="dialog">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Bukti Pembayaran</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"><span>&times;</span></button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}"
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
                                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                                            title="Ubah Status"
                                                            data-target="#ubahStatusModal{{ $pembayaran->id }}">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Modal Ubah Status -->
                                                <div class="modal fade" id="ubahStatusModal{{ $pembayaran->id }}"
                                                    tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form
                                                            action="{{ route('kelolaPembayaran.updateStatus', $pembayaran->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Ubah Status Pembayaran</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"><span>&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Ubah status pembayaran
                                                                        <strong>{{ $pembayaran->order->user->name }}</strong>?
                                                                    </p>
                                                                    <div class="form-group">
                                                                        <label>Status Baru</label>
                                                                        <select name="status" class="form-control"
                                                                            required>
                                                                            <option value="">-- Pilih Status --
                                                                            </option>
                                                                            <option value="Pembayaran Berhasil">Pembayaran
                                                                                Berhasil</option>
                                                                            <option value="Menunggu Pembayaran">Menunggu
                                                                                Pembayaran</option>
                                                                            <option value="Proses Pembayaran">Proses
                                                                                Pembayaran
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-warning"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data order yang sedang
                                                        diproses.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <span>Menampilkan {{ $pembayarans->firstItem() }} - {{ $pembayarans->lastItem() }}
                                        dari
                                        {{ $pembayarans->total() }} data</span>
                                    <div>
                                        {{ $pembayarans->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger">
                User role Anda tidak mendapatkan izin.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                paging: false,
                info: false,
                searching: false
            });
        });
    </script>
@endpush
