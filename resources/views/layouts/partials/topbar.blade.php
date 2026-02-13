<header class="navbar navbar-expand-md navbar-light d-print-none">
<div class="container-xl">
<!-- toggler visible on small screens -->
<button class="navbar-toggler d-md-none" type="button" aria-label="Toggle sidebar" data-toggle="sidebar">
<span class="navbar-toggler-icon"></span>
</button>


<a href="{{ route('dashboard') ?? url('/') }}" class="navbar-brand brand ms-2">
<img src="{{ asset('logo.jpg') }}" alt="logo" onerror="this.src='{{ asset('images/avatar.png') }}'">
<span>{{ config('app.name', 'POS Apotek') }}</span>
</a>


<div class="ms-auto d-flex align-items-center gap-3">
<a href="{{ route('dashboard') }}" class="text-muted small d-none d-md-inline">Dashboard</a>


<div class="dropdown">
<a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5z"/><path d="M20.59 20.59A9 9 0 0 0 12 21a9 9 0 0 0-8.59-6.41"/></svg>
<span class="d-none d-md-inline">Admin</span>
</a>
<div class="dropdown-menu dropdown-menu-end">
  <a class="dropdown-item" href="#"><svg class="icon me-2" width="16" height="16"><use xlink:href="#"/></svg> Profil</a>
  <div class="dropdown-divider"></div>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="dropdown-item text-danger">Logout</button>
  </form>
</div>
</div>
</div>
</div>
</header>