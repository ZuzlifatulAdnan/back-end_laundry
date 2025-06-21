@extends('layouts.app')

@section('title', 'Order Diterima')

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
                                <h4>Data Order Diterima</h4>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <h6 class="text-dark">
                                        Waktu Sekarang (WIB): <span id="current-wib-time"
                                            class="text-primary font-weight-bold"></span>
                                    </h6>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Order</th>
                                                <th>Nama User</th>
                                                <th>Tanggal Order</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Durasi Tunggu</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orders as $index => $order)
                                                <tr>
                                                    <td>{{ $orders->firstItem() + $index }}</td>
                                                    <td>{{ $order->no_order ?? '-' }}</td>
                                                    <td>{{ $order->user->name ?? '-' }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d M Y') }}
                                                        {{ $order->jam_order }}
                                                    </td>
                                                    <td>
                                                        <span id="status-badge-{{ $order->id }}"
                                                            class="badge badge-success">{{ $order->status }}</span>
                                                    </td>
                                                    <td>Rp{{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                                    <td>
                                                        <div id="timer-{{ $order->id }}"
                                                            class="text-danger font-weight-bold"
                                                            data-deadline="{{ \Carbon\Carbon::parse($order->tanggal_order . ' ' . $order->jam_order)->format('Y-m-d H:i:s') }}">
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-icon btn-primary" title="Ubah Status"
                                                            data-toggle="modal"
                                                            data-target="#ubahStatusModal{{ $order->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Modal Ubah Status -->
                                                <div class="modal fade" id="ubahStatusModal{{ $order->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="ubahStatusModalLabel{{ $order->id }}"
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
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Ubah status order
                                                                        <strong>{{ $order->user->name }}</strong>?
                                                                    </p>
                                                                    <div class="form-group">
                                                                        <label>Status Baru</label>
                                                                        <select name="status" class="form-control"
                                                                            required>
                                                                            <option value="">-- Pilih Status --
                                                                            </option>
                                                                            <option value="Diproses">Diproses</option>
                                                                            <option value="Selesai">Selesai</option>
                                                                            <option value="Ditunda">Ditunda</option>
                                                                            <option value="Dibatalkan">Dibatalkan</option>
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
                                                    <td colspan="7" class="text-center">Tidak ada data order diterima.
                                                    </td>
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
        @else
            <div class="alert alert-danger">
                User role Anda tidak mendapatkan izin.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Waktu Sekarang WIB (UTC+7)
        function updateCurrentWIBTime() {
            const wibOffset = 7 * 60;
            const now = new Date();
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const wibTime = new Date(utc + (wibOffset * 60000));

            const hours = String(wibTime.getHours()).padStart(2, '0');
            const minutes = String(wibTime.getMinutes()).padStart(2, '0');
            const seconds = String(wibTime.getSeconds()).padStart(2, '0');

            document.getElementById('current-wib-time').innerText = `${hours}:${minutes}:${seconds}`;
            setTimeout(updateCurrentWIBTime, 1000);
        }

        $(function() {
            updateCurrentWIBTime();

            function startCountdown(id, deadlineStr) {
                const timerElement = document.getElementById('timer-' + id);
                const orderTime = new Date(deadlineStr.replace(' ', 'T'));
                const autoTundaTime = new Date(orderTime.getTime() + 15 * 60 * 1000);

                timerElement.classList.add('text-danger');

                function updateTimer() {
                    const now = new Date().getTime();
                    const distance = autoTundaTime.getTime() - now;

                    if (distance <= 0) {
                        timerElement.innerHTML = 'Ditunda';
                        updateToTunda(id);
                        return;
                    }

                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    timerElement.innerHTML = `${minutes}m ${seconds}s`;

                    setTimeout(updateTimer, 1000);
                }

                updateTimer();
            }

            function updateToTunda(orderId) {
                $.ajax({
                    url: '/kelolaOrder/auto-batal/' + orderId,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.status === 'tunda') {
                            $('#timer-' + orderId).html('Ditunda');
                            $('#status-badge-' + orderId)
                                .removeClass('badge-success')
                                .addClass('badge-warning')
                                .text('Tunda');
                        }
                    }
                });
            }

            $('[id^=timer-]').each(function() {
                const id = $(this).attr('id').split('-')[1];
                const deadline = $(this).data('deadline');
                startCountdown(id, deadline);
            });
        });
    </script>
@endpush
