<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo_nama.png') }}" alt="Logo" style="width: 170px; height: auto;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" style="width: 40px; height: auto;">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ $type_menu === 'beranda' ? 'active' : '' }}">
                <a href="{{ route('beranda.index') }}" class="nav-link ha">
                    <i class="fas fa-tachometer-alt"></i><span>Beranda</span>
                </a>
            </li>

            <li class="menu-header">Master Data</li>

            {{-- Kelola Orderan --}}
            <li class="nav-item dropdown {{ $type_menu === 'kelola' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-box-open"></i><span>Kelola Orderan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('kelolaOrder') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('kelolaOrder.index') }}">Data Order</a>
                    </li>
                    <li class="{{ Request::is('kelola/terima') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('kelolaOrder/terima') }}">Data Order Diterima</a>
                    </li>
                    <li class="{{ Request::is('kelola/proses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('kelolaOrder/proses') }}">Data Order Diproses</a>
                    </li>
                </ul>
            </li>

            {{-- Kelola Pembayaran --}}
            <li class="nav-item dropdown {{ $type_menu === 'pembayaran' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-money-check-alt"></i><span>Kelola Pembayaran</span>
                </a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('kelolaPembayaran') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('kelolaPembayaran.index') }}">Data Pembayaran</a>
                    </li>
                    <li class="{{ Request::is('kelolaPembayaran/terima') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('kelolaPembayaran/terima') }}">Pembayaran Diterima</a>
                    </li>
                    <li class="{{ Request::is('kelolaPembayaran/proses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('kelolaPembayaran/proses') }}">Pembayaran Diproses</a>
                    </li>
                </ul>
            </li>

            {{-- Mesin --}}
            <li class="nav-item dropdown {{ $type_menu === 'mesin' ? 'active' : '' }}">
                <a href="{{ route('mesin.index') }}" class="nav-link ha">
                    <i class="fas fa-cogs"></i><span>Mesin</span>
                </a>
            </li>

            {{-- Order --}}
            <li class="nav-item dropdown {{ $type_menu === 'order' ? 'active' : '' }}">
                <a href="{{ route('order.index') }}" class="nav-link ha">
                    <i class="fas fa-shopping-cart"></i><span>Order</span>
                </a>
            </li>
            {{-- Riwayat Order --}}
            <li class="nav-item dropdown {{ $type_menu === 'riwayatOrder' ? 'active' : '' }}">
                <a href="" class="nav-link ha">
                    <i class="fas fa-shopping-cart"></i><span>Riwayat Order</span>
                </a>
            </li>
            {{-- Riwayat Pembayaran --}}
            <li class="nav-item dropdown {{ $type_menu === 'riwayatPembayaran' ? 'active' : '' }}">
                <a href="{{ route('riwayatPembayaran.index') }}" class="nav-link ha">
                    <i class="fas fa-history"></i><span>Riwayat Pembayaran</span>
                </a>
            </li>

            {{-- Users (Admin only) --}}
            @if (Auth::user()->role == 'Admin')
                <li class="nav-item dropdown {{ $type_menu === 'user' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link ha">
                        <i class="fas fa-users-cog"></i><span>Users</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
