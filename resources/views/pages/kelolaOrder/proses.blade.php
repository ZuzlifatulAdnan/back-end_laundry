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
                                                <th>No Order</th>
                                                <th>Nama User</th>
                                                <th>Tanggal Order</th>
                                                <th>Status</th>
                                                <th>Status Pembayaran</th>
                                                <th>Total</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orders as $index => $order)
                                                <tr>
                                                    <td>{{ $orders->firstItem() + $index }}</td>
                                                    <td>{{ $order->no_order ?? '-' }}</td>
                                                    <td>{{ $order->user->name ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $orderBadge = match ($order->status) {
                                                                'Diterima' => 'primary',
                                                                'Ditolak' => 'danger',
                                                                'Dibatalkan' => 'dark',
                                                                'Selesai' => 'success',
                                                                'Ditunda' => 'warning',
                                                                'Diproses' => 'info',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge badge-{{ $orderBadge }}">{{ $order->status }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $badge = match ($order->pembayaran?->status) {
                                                                'Pembayaran Berhasil' => 'success',
                                                                'Proses Pembayaran' => 'warning',
                                                                'Menunggu Pembayaran' => 'secondary',
                                                                default => 'light',
                                                            };
                                                        @endphp
                                                        <span class="badge badge-{{ $badge }}">
                                                            {{ $order->pembayaran?->status ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>Rp{{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                                            data-target="#ubahStatusModal{{ $order->id }}"
                                                            title="Ubah Status Order">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada data order yang sedang
                                                        diproses.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <span>Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                                        {{ $orders->total() }} data</span>
                                    <div>{{ $orders->links() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Ubah Status --}}
            @foreach ($orders as $order)
                <div class="modal fade" id="ubahStatusModal{{ $order->id }}" tabindex="-1" role="dialog"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('kelolaOrder.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ubah Status Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>Ubah status untuk order <strong>{{ $order->user->name }}</strong>?</p>

                                    @php
                                        $statusList = [
                                            'Diproses',
                                            'Diterima',
                                            'Ditolak',
                                            'Dibatalkan',
                                            'Selesai',
                                            'Ditunda',
                                        ];
                                        $pembayaranList = [
                                            'Menunggu Pembayaran',
                                            'Proses Pembayaran',
                                            'Pembayaran Berhasil',
                                        ];
                                    @endphp

                                    <div class="form-group">
                                        <label>Status Order</label>
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Pilih Status --</option>
                                            @foreach ($statusList as $status)
                                                <option value="{{ $status }}"
                                                    {{ $order->status === $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Status Pembayaran</label>
                                        <select name="status_pembayaran" class="form-control">
                                            <option value="">-- Pilih Status Pembayaran --</option>
                                            @foreach ($pembayaranList as $pay)
                                                <option value="{{ $pay }}"
                                                    {{ $order->pembayaran?->status === $pay ? 'selected' : '' }}>
                                                    {{ $pay }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($order->pembayaran?->bukti_bayar)
                                        <div class="form-group text-center">
                                            <label>Bukti Pembayaran</label><br>
                                            <img src="{{ asset('img/bukti_bayar/' . $order->pembayaran->bukti_bayar) }}"
                                                alt="Bukti Bayar" class="img-fluid img-thumbnail"
                                                style="max-height: 300px; cursor: pointer; border-radius: 10px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Yakin ingin mengubah status order ini?')">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-danger">Anda tidak memiliki akses ke halaman ini.</div>
        @endif
    </div>
@endsection

@push('scripts')
@endpush
