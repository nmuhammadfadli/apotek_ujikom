{{-- resources/views/layouts/partials/sidebar.blade.php --}}
@php $u = auth()->user(); @endphp

<aside class="navbar navbar-vertical navbar-expand-lg navbar-light bg-white shadow-sm h-100">
  <div class="container-xl">
    <div class="navbar-collapse">
      <ul class="navbar-nav">

        {{-- OWNER ONLY MENU --}}
        @if($u && $u->isOwner())
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('das.*') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 7h18"/><path d="M5 7v14h14V7"/></svg>
              </span>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('obat.*') ? 'active' : '' }}" href="{{ route('obat.index') }}" title="Obat">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="7" width="18" height="10" rx="2"/><path d="M8 7v10"/><path d="M16 7v10"/></svg>
              </span>
              <span class="nav-link-title">Obat</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}" href="{{ route('supplier.index') }}" title="Supplier">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
              </span>
              <span class="nav-link-title">Supplier</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}" title="Pelanggan">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
              </span>
              <span class="nav-link-title">Pelanggan</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}" href="{{ route('pembelian.index') }}" title="Pembelian">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 7h18"/><path d="M5 7v14h14V7"/></svg>
              </span>
              <span class="nav-link-title">Pembelian</span>
            </a>
          </li>
        @endif

        {{-- VISIBLE FOR ANY AUTHENTICATED USER (pegawai + owner) --}}
        @if($u)
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}" href="{{ route('penjualan.index') }}" title="Penjualan">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 3h18v13H3z"/><path d="M7 21h10"/></svg>
              </span>
              <span class="nav-link-title">Penjualan</span>
            </a>
          </li>
        @endif

        {{-- OPTIONAL: show login link for guests --}}
        @unless($u)
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
              <span class="nav-link-icon d-none d-lg-inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5M15 12H3"/></svg>
              </span>
              <span class="nav-link-title">Login</span>
            </a>
          </li>
        @endunless

      </ul>
    </div>
  </div>
</aside>
