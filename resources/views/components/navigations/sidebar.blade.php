@php
use Illuminate\Support\Facades\Request;
@endphp

@php
    $isActiveMaster = Request::routeIs('admin.supply','admin.product', 'admin.shift','admin.saham');
    $isActiveInvestor = Request::routeIs('admin.investor', 'admin.takeover');
@endphp
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="#">
            <span class="ms-1 font-weight-bold">Rantai Tani</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(auth()->check())
                @if(auth()->user()->hasRole('administrator'))
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.administrator') ? 'active' : '' }}" href="{{ route('dashboard.administrator') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard Admin</span>
                        </a>
                    </li>

                    {{-- Dropdown Master --}}
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#dropdownMaster" role="button" aria-expanded="{{ $isActiveMaster ? 'true' : 'false' }}" aria-controls="dropdownMaster">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-settings-gear-65 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Data Master</span>
                            </div>
                        </a>
                        <div class="collapse  {{ $isActiveMaster ? 'show' : '' }}" id="dropdownMaster">
                            <ul class="nav ms-4">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.supply') ? 'active' : '' }}" href="{{ route('admin.supply') }}">
                                        <span class="nav-link-text">Manajemen Supply Network</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.product') ? 'active' : '' }}" href="{{ route('admin.product') }}">
                                        <span class="nav-link-text">Manajemen Products</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.shift') ? 'active' : '' }}" href="{{ route('admin.shift') }}">
                                        <span class="nav-link-text">Manajemen Shift</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.saham') ? 'active' : '' }}" href="{{ route('admin.saham') }}">
                                        <span class="nav-link-text">Manajemen Saham</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- Dropdown Master End --}}

                    {{-- Dropdown Investor --}}

                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#dropdownInvestor" role="button" aria-expanded="{{ $isActiveInvestor ? 'true' : 'false' }}" aria-controls="dropdownInvestor">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-settings-gear-65 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Investor</span>
                            </div>
                        </a>
                        <div class="collapse  {{ $isActiveInvestor ? 'show' : '' }}" id="dropdownInvestor">
                            <ul class="nav ms-4">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.investor') ? 'active' : '' }}" href="{{ route('admin.investor') }}">
                                        <span class="nav-link-text">Manajemen Investor</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('admin.takeover') ? 'active' : '' }}" href="{{ route('admin.takeover') }}">
                                        <span class="nav-link-text">Manajemen Take Over</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>


                    {{-- Menu lainnya (non-master) --}}
                    <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('admin.user') ? 'active' : '' }}" href="{{ route('admin.user') }}">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-box-2 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text">Manajemen User</span>
                            </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('admin.stock') ? 'active' : '' }}" href="{{ route('admin.stock') }}">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-box-2 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text">Manajemen Stock</span>
                            </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('admin.sewa.index') ? 'active' : '' }}" href="{{ route('admin.sewa.index') }}">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-box-2 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text">Manajemen Sewa</span>
                            </a>
                    </li>

                @endif

                @if(auth()->user()->hasRole('mitra'))
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.mitra') ? 'active' : '' }}" href="{{ route('dashboard.mitra') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-briefcase-24 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard Mitra</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.mitra.transaksi') ? 'active' : '' }}" href="{{ route('dashboard.mitra.transaksi') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-briefcase-24 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Update Stock Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('mitra.laporan.index') ? 'active' : '' }}" href="{{ route('mitra.laporan.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-briefcase-24 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Laporan Lengkap</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->hasRole('investor'))
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.investor') ? 'active' : '' }}" href="{{ route('dashboard.investor') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard Investor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.investor.beli-saham') ? 'active' : '' }}" href="{{ route('dashboard.investor.beli-saham') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Pembelian Saham</span>
                        </a>
                    </li>
                @endif

                {{-- Logout --}}
                <li class="nav-item mt-3">
                    <hr class="horizontal dark">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-button-power text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endif
        </ul>
    </div>
</aside>
