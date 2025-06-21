@extends('layouts.app')

@section('title', 'Beranda')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h4>Selamat Datang, {{ Auth::user()->name }} di Clean Wash Laundromat</h4>
            </div>

            <div class="section-body">
                {{-- 🔹 Statistik Count --}}
                @if (Auth::user()->role == 'Admin')
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Users</h4>
                                    </div>
                                    <div class="card-body">{{ $totalUsers }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success"><i class="fas fa-clipboard-list"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Orders</h4>
                                    </div>
                                    <div class="card-body">{{ $totalOrders }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning"><i class="fas fa-money-check-alt"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Pembayaran</h4>
                                    </div>
                                    <div class="card-body">{{ $totalPembayaran }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info"><i class="fas fa-cogs"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Mesin</h4>
                                    </div>
                                    <div class="card-body">{{ $totalMesin }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        User role Anda tidak mendapatkan izin.
                    </div>
                @endif
                @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Customer')
                    {{-- 🔹 Carousel --}}
                    <div id="carouselCustomer" class="carousel slide mb-4" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselCustomer" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselCustomer" data-slide-to="1"></li>
                            <li data-target="#carouselCustomer" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner rounded">
                            <div class="carousel-item active">
                                <img src="{{ asset('img/beranda/customer1.jpg') }}" class="d-block w-100" alt="Slide 1">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Selamat Datang di Clean Wash</h5>
                                    <p>Laundry cepat, bersih, dan profesional.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('img/beranda/customer1.jpg') }}" class="d-block w-100" alt="Slide 2">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Pesan Self Service</h5>
                                    <p>Pilih mesin sesuai kebutuhan Anda kapan saja.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('img/beranda/customer1.jpg') }}" class="d-block w-100" alt="Slide 3">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Antar Jemput Tersedia</h5>
                                    <p>Hemat waktu dengan layanan antar jemput.</p>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselCustomer" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#carouselCustomer" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>

                    {{-- 🔹 Daftar Mesin Ready --}}
                    <div class="section">
                        <h5 class="mb-3">Daftar Mesin Tersedia</h5>
                        <div class="row">
                            @forelse ($mesinReady as $mesin)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <a href="{{ route('order.selfservice', ['mesin_id' => $mesin->id]) }}"
                                        class="text-decoration-none">
                                        <div class="card border-success shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $mesin->nama }}</h6>
                                                <p class="mb-1">Tipe: <strong>{{ $mesin->type }}</strong></p>
                                                <p>Status: <span class="badge badge-success">{{ $mesin->status }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">Tidak ada mesin yang tersedia saat ini.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        User role Anda tidak mendapatkan izin.
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $('#carouselCustomer').carousel({
            interval: 4000,
            pause: 'hover'
        });
    </script>
@endpush
