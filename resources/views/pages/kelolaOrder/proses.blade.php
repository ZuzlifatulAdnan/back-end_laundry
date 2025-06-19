@extends('layouts.app')

@section('title', 'Order Diproses')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
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
                                            <th>Nama User</th>
                                            <th>Tanggal Order</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $index => $order)
                                            <tr>
                                                <td>{{ $orders->firstItem() + $index }}</td>
                                                <td>{{ $order->user->name ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d M Y') }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-warning">{{ $order->status }}</span>
                                                </td>
                                                <td>Rp{{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <!-- Tombol Modal -->
                                                    <button class="btn btn-sm btn-success" data-toggle="modal"
                                                        data-bs-toggle="tooltip" title="Ubah Status Order"
                                                        data-target="#ubahStatusModal{{ $order->id }}">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="ubahStatusModal{{ $order->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="ubahStatusModalLabel{{ $order->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('kelolaOrder.updateStatus', $order->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="ubahStatusModalLabel{{ $order->id }}">
                                                                    Ubah Status Order
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Ubah status order
                                                                    <strong>{{ $order->user->name }}</strong>?
                                                                </p>
                                                                <div class="form-group">
                                                                    <label>Status Baru</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="">-- Pilih Status --</option>
                                                                        <option value="Diterima">Diterima</option>
                                                                        <option value="Ditolak">Ditola</option>
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
                                                <td colspan="6" class="text-center">Tidak ada data order yang sedang
                                                    diproses.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <span>Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                                    {{ $orders->total() }} data</span>
                                <div>
                                    {{ $orders->links() }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
@endpush
