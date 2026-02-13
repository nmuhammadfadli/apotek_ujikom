<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Admin') - {{ config('app.name') }}</title>

  <!-- Tabler (UI) -->
  <link href="https://unpkg.com/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>

  <!-- DataTables optional -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>

  @stack('styles')

  <style>
    /* THEME CUSTOMIZATION */
    :root{
      --brand: #0d6efd;
      --accent: #0a8a7a;
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 64px;
      --topbar-height: 56px; /* approximate topbar height (adjust if needed) */
    }

    /* brand */
    .brand { font-weight:700; letter-spacing: .3px; color:var(--brand); display:flex; align-items:center; gap:.5rem; }
    .brand img{ height:28px; }

    /* ------------------- SIDEBAR (fixed desktop) ------------------- */
    .navbar-vertical {
      width: var(--sidebar-width);
      min-height: 100vh;
      transition: width .18s ease, transform .18s ease, left .18s ease, top .18s ease;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1045; /* above header so it won't be hidden if overlap happens */
      background: #fff;
    }

    html, body {
    height: 100%;
    overflow-y: auto;
  }

    /* page wrapper/content should leave room for sidebar on desktop */
    .page-wrapper {
      margin-left: 0; /* default: mobile/fullwidth; will set for desktop in media query */
      transition: margin-left .18s ease;
      min-height: 100vh;
      overflow-y: auto;
      background: #f8f9fb;
    }
    .page-wrapper.sidebar-collapsed { /* desktop collapsed state handled in media query */
      margin-left: 0;
    }

    /* collapse behavior for nav titles */
    .navbar-vertical .nav-link .nav-link-title { transition: opacity .12s; }
    .page-wrapper.sidebar-collapsed .nav-link .nav-link-title { opacity: 0; visibility: hidden; width:0; }

    /* small-screen behaviour: overlay sidebar */
    @media (max-width: 991px){
      .navbar-vertical {
        left: -100%;
        top: 0;
        height: 100vh;
        z-index: 1030;
      }
      .navbar-vertical.show-mobile { left: 0; }

      /* content uses full width on mobile */
      .page-wrapper { margin-left: 0; }

      .page-overlay { display:block; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1020; }
    }
    .page-overlay { display:none; }

    /* on desktop, reserve space for sidebar and shift header */
    @media (min-width: 992px) {
      .page-wrapper {
        margin-left: var(--sidebar-width);
      }
      .page-wrapper.sidebar-collapsed {
        margin-left: var(--sidebar-collapsed-width);
      }

      header.navbar {
        margin-left: var(--sidebar-width);
      }
      .page-wrapper.sidebar-collapsed header.navbar {
        margin-left: var(--sidebar-collapsed-width);
      }

      /* ensure header doesn't overlap the left-most sidebar area visually */
      /* keep header on top of page content but under sidebar if any overlap */
      header.navbar { z-index: 1035; }
      .navbar-vertical { z-index: 1045; }
    }

    /* card accents */
    .card .card-title { color: var(--brand); }
    .low-stock { color:#fff; background:#dc3545; padding:2px 8px; border-radius:6px; font-size:.8rem; }

    /* smaller icons spacing */
    .nav-link-icon { width:28px; display:inline-flex; justify-content:center; align-items:center; margin-right:.5rem; }

    /* make topbar sticky (always visible) */
    header.navbar {
      position: sticky;
      top: 0;
      background: #fff;
      box-shadow: 0 1px 6px rgba(15,15,15,.04);
      transition: margin-left .18s ease;
      height: var(--topbar-height);
      line-height: var(--topbar-height);
    }

    /* page body padding tweak */
    min-height: calc(100vh - var(--topbar-height));

    /* responsive helpers */
    @media (max-width: 575px){
      .brand span { font-size: 0.95rem; }
    }
  </style>
</head>
<body class="antialiased">
  <div class="page">

    <!-- topbar -->
    @include('layouts.partials.topbar')

    <div id="page-wrapper" class="page-wrapper">
      <!-- sidebar -->
      @include('layouts.partials.sidebar')

      <div class="page-body">
        <div class="container-xl py-4">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
              </ul>
            </div>
          @endif

          @yield('content')
        </div>
      </div>

      @include('layouts.partials.footer')
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://unpkg.com/@tabler/core@latest/dist/js/tabler.min.js"></script>

  <script>
    (function(){
      const wrapper = document.getElementById('page-wrapper');
      const sidebar = document.querySelector('.navbar-vertical');
      const overlay = document.createElement('div');
      overlay.className = 'page-overlay';
      document.body.appendChild(overlay);

      const collapsedKey = 'sidebarCollapsed_v1';

      function applySavedState() {
        const saved = localStorage.getItem(collapsedKey) === '1';
        if (window.innerWidth > 991) {
          // desktop: restore collapsed state by toggling wrapper class
          if (saved) wrapper.classList.add('sidebar-collapsed');
          else wrapper.classList.remove('sidebar-collapsed');

          // ensure sidebar is visible in desktop (not overlay)
          sidebar.classList.remove('show-mobile');
          overlay.style.display = 'none';
        } else {
          // mobile: do not keep collapsed class so content uses full width
          wrapper.classList.remove('sidebar-collapsed');
        }
      }

      applySavedState();

      // delegated toggler (works with data-toggle="sidebar")
      document.addEventListener('click', function(e){
        const t = e.target.closest('[data-toggle="sidebar"]');
        if(!t) return;

        if(window.innerWidth <= 991){
          // mobile: open/close overlay sidebar
          sidebar.classList.toggle('show-mobile');
          overlay.style.display = sidebar.classList.contains('show-mobile') ? 'block' : 'none';
          return;
        }

        // desktop: collapse/expand
        wrapper.classList.toggle('sidebar-collapsed');
        const collapsed = wrapper.classList.contains('sidebar-collapsed');
        localStorage.setItem(collapsedKey, collapsed ? '1' : '0');
      });

      // hide mobile sidebar when overlay clicked
      overlay.addEventListener('click', function(){
        sidebar.classList.remove('show-mobile');
        this.style.display = 'none';
      });

      // handle resize: close mobile sidebar when going to desktop & reapply state
      let resizeTimer;
      window.addEventListener('resize', function(){
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function(){
          if(window.innerWidth > 991){
            sidebar.classList.remove('show-mobile');
            overlay.style.display = 'none';
          }
          applySavedState();
        }, 80);
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
