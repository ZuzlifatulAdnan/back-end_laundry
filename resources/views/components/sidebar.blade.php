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
                    <i class="fas fa-home"></i><span>Beranda</span>
                </a>
            </li>

            <li class="menu-header">Master Data</li>

            {{-- Kelola Orderan --}}
            <li class="nav-item dropdown {{ $type_menu === 'kelola' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-boxes"></i><span>Kelola Orderan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('kelolaOrder') ? 'active' : '' }}">
                        <a class="nav-link" href="">Data Order</a>
                    </li>
                    <li class="{{ request()->is('kelolaOrder/terima') ? 'active' : '' }}">
                        <a class="nav-link" href="">Data Order Diterima</a>
                    </li>
                    <li class="{{ request()->is('kelolaOrder/proses') ? 'active' : '' }}">
                        <a class="nav-link" href="">Data Order Diproses</a>
                    </li>
                </ul>
            </li>

            {{-- Kelola Pembayaran --}}
            <li class="nav-item dropdown {{ $type_menu === 'pembayaran' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-wallet"></i><span>Kelola Pembayaran</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('kelolaPembayaran') ? 'active' : '' }}">
                        <a class="nav-link" href="">Data Pembayaran</a>
                    </li>
                    <li class="{{ request()->is('kelolaPembayaran/terima') ? 'active' : '' }}">
                        <a class="nav-link" href="">Pembayaran Diterima</a>
                    </li>
                    <li class="{{ request()->is('kelolaPembayaran/proses') ? 'active' : '' }}">
                        <a class="nav-link" href="">Pembayaran Diproses</a>
                    </li>
                </ul>
            </li>

            {{-- Mesin --}}
            <li class="nav-item dropdown {{ $type_menu === 'mesin' ? 'active' : '' }}">
                <a href="{{ route('mesin.index') }}" class="nav-link ha">
                    <i class="fas fa-tools"></i><span>Mesin</span>
                </a>
            </li>

            {{-- Order --}}
            <li class="nav-item dropdown {{ $type_menu === 'order' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-shopping-basket"></i><span>Order</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('order/dropoff') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('order/dropoff') }}">Drop Off</a>
                    </li>
                    <li class="{{ request()->is('order/selfservice') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('order/selfservice') }}">Self Service</a>
                    </li>
                </ul>
            </li>

            {{-- Riwayat --}}
            <li class="nav-item dropdown {{ $type_menu === 'riwayat' ? 'active' : '' }}">
                <a href="{{ route('riwayat.index') }}" class="nav-link ha">
                    <i class="fas fa-history"></i><span>Riwayat</span>
                </a>
            </li>

            {{-- Users (Admin only) --}}
            @if (Auth::user()->role == 'Admin')
                <li class="nav-item dropdown {{ $type_menu === 'user' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link ha">
                        <i class="fas fa-user-cog"></i><span>Users</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
