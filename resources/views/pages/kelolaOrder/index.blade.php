@extends('layouts.app')

@section('title', 'Kelola Order')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @if (Auth::user()->role == 'Admin')
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
                                    <h4>Data Order</h4>
                                </div>
                                <div class="card-body">
                                    {{-- Filter & Tambah --}}
                                    <div class="mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-2 mb-2">
                                                <a href="{{ route('kelolaOrder.create') }}" class="btn btn-primary w-100">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </a>
                                            </div>
                                            <div class="col-md-10">
                                                <form action="{{ route('kelolaOrder.index') }}" method="GET">
                                                    <div class="form-row row">
                                                        <div class="col-md-2 mb-2">
                                                            <select name="status" class="form-control"
                                                                onchange="this.form.submit()">
                                                                <option value="">Semua Status</option>
                                                                <option value="Diproses"
                                                                    {{ request('status') == 'Diproses' ? 'selected' : '' }}>
                                                                    Diproses</option>
                                                                <option value="Selesai"
                                                                    {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                                                                    Selesai</option>
                                                                <option value="Dibatalkan"
                                                                    {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>
                                                                    Dibatalkan</option>
                                                                <option value="Ditunda"
                                                                    {{ request('status') == 'Ditunda' ? 'selected' : '' }}>
                                                                    Ditunda
                                                                </option>
                                                                <option value="Diterima"
                                                                    {{ request('status') == 'Diterima' ? 'selected' : '' }}>
                                                                    Diterima
                                                                </option>
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
                                                        <div class="col-md-2 mb-2">
                                                            <select name="service_type" class="form-control"
                                                                onchange="this.form.submit()">
                                                                <option value="">Semua Layanan</option>
                                                                <option value="SelfService"
                                                                    {{ request('service_type') == 'SelfService' ? 'selected' : '' }}>
                                                                    SelfService</option>
                                                                <option value="DropOff"
                                                                    {{ request('service_type') == 'DropOff' ? 'selected' : '' }}>
                                                                    DropOff</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <input type="text" name="search" class="form-control"
                                                                placeholder="Cari " value="{{ request('search') }}">
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
                                            <tr>
                                                <th style="width: 3%">No</th>
                                                <th>No Order</th>
                                                <th>Nama User</th>
                                                <th>Layanan</th>
                                                <th>Tgl Order</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th class="text-center" style="width: 5%">Aksi</th>
                                            </tr>
                                            @foreach ($orders as $index => $order)
                                                <tr>
                                                    <td>{{ $orders->firstItem() + $index }}</td>
                                                    <td>{{ $order->no_order }}</td>
                                                    <td>{{ $order->user->name ?? '-' }}</td>
                                                    <td>{{ $order->service_type }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $order->status == 'Selesai' ? 'success' : ($order->status == 'Diproses' ? 'warning' : 'danger') }}">
                                                            {{ $order->status }}
                                                        </span>
                                                    </td>
                                                    <td>Rp{{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('kelolaOrder.edit', $order) }}"
                                                                class="btn btn-sm btn-icon btn-primary m-1" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('kelolaOrder.show', $order) }}"
                                                                class="btn btn-sm btn-icon btn-info m-1"
                                                                title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            {{-- Tombol WhatsApp --}}
                                                            @php
                                                                $phone = preg_replace(
                                                                    '/[^0-9]/',
                                                                    '',
                                                                    $order->user->no_handphone ?? '',
                                                                );
                                                                if (Str::startsWith($phone, '0')) {
                                                                    $phone = '62' . substr($phone, 1); // konversi 08xx ke 628xx
                                                                }

                                                                $message = urlencode(
                                                                    "Halo *{$order->user->name}*,\n\nBerikut detail pesanan Anda:\n\n" .
                                                                        "📦 *No Order:* {$order->no_order}\n" .
                                                                        '🗓️ *Tanggal Order:* ' .
                                                                        \Carbon\Carbon::parse(
                                                                            $order->tanggal_order,
                                                                        )->translatedFormat('d M Y') .
                                                                        "\n" .
                                                                        "💼 *Layanan:* {$order->service_type}\n" .
                                                                        "💳 *Metode Pembayaran:* {$order->metode_pembayaran}\n" .
                                                                        "💸 *Status Pembayaran:* {$order->status_pembayaran}\n" .
                                                                        "📌 *Status Order:* {$order->status}\n" .
                                                                        '💰 *Total Biaya:* Rp' .
                                                                        number_format(
                                                                            $order->total_biaya,
                                                                            0,
                                                                            ',',
                                                                            '.',
                                                                        ) .
                                                                        "\n\n" .
                                                                        'Silakan lakukan pembayaran atau hubungi kami jika ada pertanyaan. Terima kasih. 🙏',
                                                                );

                                                                $waUrl = "https://wa.me/{$phone}?text={$message}";
                                                            @endphp

                                                            {{-- @if ($phone) --}}
                                                                <a href="{{ $waUrl }}" target="_blank"
                                                                    class="btn btn-sm btn-icon btn-success m-1"
                                                                    title="Chat WhatsApp">
                                                                    <i class="fab fa-whatsapp"></i>
                                                                </a>
                                                            {{-- @endif --}}
                                                            <form action="{{ route('kelolaOrder.destroy', $order) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon m-1 btn-danger confirm-delete "
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
                                                Menampilkan {{ $orders->firstItem() }} sampai {{ $orders->lastItem() }}
                                                dari total {{ $orders->total() }} data
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
            </section>
        @else
            <div class="alert alert-danger">
                User role Anda tidak mendapatkan izin.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
