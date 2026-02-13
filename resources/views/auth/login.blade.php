@section('title','Login')

<style>
  /* --- page --- */
  :root{
    --blue-1: #daf4ff; /* very light */
    --blue-2: #bfeeff; /* light */
    --blue-3: #79c7ff; /* medium */
    --card-shadow: 0 8px 24px rgba(25, 45, 60, 0.08);
    --radius: 12px;
  }

  html,body {
    height: 100%;
    margin: 0;
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    background: #ffffff; /* user requested white background */
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
  }

  .login-viewport {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  /* --- the card --- */
  .login-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    border: 1px solid rgba(15, 23, 42, 0.04);
  }

  /* thin top accent strip with soft blue gradient */
  .login-card .accent {
    height: 8px;
    background: linear-gradient(90deg, var(--blue-1), var(--blue-2), var(--blue-3));
  }

  .login-card .body {
    padding: 28px 28px 32px;
  }

  .brand {
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom: 10px;
  }
  .brand .logo {
    width:44px;
    height:44px;
    border-radius:10px;
    background: linear-gradient(135deg, var(--blue-1), var(--blue-3));
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    color:#00365f;
    box-shadow: inset 0 -6px 18px rgba(0,0,0,0.02);
  }
  .brand .title {
    font-size:18px;
    font-weight:700;
    color:#0b1a2b;
  }
  .brand .subtitle {
    font-size:13px;
    color:#5f6b78;
  }

  h1.login-heading {
    margin: 8px 0 16px;
    font-size:20px;
    font-weight:700;
    color:#0b1a2b;
    text-align:center;
  }
  .login-desc {
    font-size:13px;
    color:#6b7785;
    text-align:center;
    margin-bottom:18px;
  }

  /* inputs */
  .form-control {
    width:100%;
    display:block;
    padding:12px 14px;
    border-radius:10px;
    border: 1px solid rgba(15, 23, 42, 0.08);
    background: #fff;
    transition: border-color .12s, box-shadow .12s, transform .08s;
    font-size:14px;
    color:#0b1a2b;
  }
  .form-control:focus {
    outline: none;
    border-color: rgba(120,170,255,0.9);
    box-shadow: 0 6px 18px rgba(120,170,255,0.12);
  }
  .form-label { font-size:13px; color:#44515a; margin-bottom:6px; display:block; }

  .field-row { margin-bottom:14px; position:relative; }

  /* password toggle button */
  .pw-toggle {
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    border:none;
    background:transparent;
    cursor:pointer;
    color:#6b7785;
    padding:6px;
    border-radius:6px;
  }

  /* actions area */
  .actions {
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:space-between;
    margin-top:6px;
    margin-bottom:18px;
  }
  .checkbox {
    display:flex;
    align-items:center;
    gap:8px;
    color:#52606b;
    font-size:13px;
  }

  /* primary button with light blue gradient */
  .btn-primary {
    display:inline-block;
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border: none;
    font-weight:600;
    color:white;
    cursor:pointer;
    background: linear-gradient(90deg, #bfeeff 0%, #9fe6ff 50%, #79c7ff 100%);
    box-shadow: 0 8px 20px rgba(56,128,255,0.08);
    transition: transform .06s ease, box-shadow .12s;
    font-size:15px;
  }
  .btn-primary:hover { transform: translateY(-1px); }

  /* small text */
  .muted {
    font-size:13px;
    color:#7b8794;
    text-align:center;
    margin-top:14px;
  }

  .alert {
    font-size:13px;
    padding:10px 12px;
    border-radius:8px;
  }

  /* responsive */
  @media (max-width: 480px){
    .login-card { margin: 0 6px; }
    .body { padding:20px; }
  }
</style>

<div class="login-viewport">
  <div class="login-card" role="main" aria-labelledby="login-heading">
    <div class="accent" aria-hidden="true"></div>

    <div class="body">
      <div class="brand" style="justify-content:center;">
        <div class="logo">FX</div>
      </div>

      <h1 id="login-heading" class="login-heading">Sign In</h1>
      <div class="login-desc">Masuk menggunakan akun Anda untuk mengelola aplikasi</div>

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" autocomplete="on" novalidate>
        @csrf

        <div class="field-row">
          <label class="form-label" for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="you@example.com">
          @error('email') <div class="invalid-feedback" style="color:#d9534f;margin-top:6px;font-size:13px">{{ $message }}</div> @enderror
        </div>

        <div class="field-row" style="margin-bottom:8px;">
          <label class="form-label" for="password">Password</label>
          <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Masukkan password">
          <button type="button" class="pw-toggle" aria-label="Toggle password visibility" title="Show / hide password" onclick="togglePassword()">
            <!-- simple eye icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </button>
          @error('password') <div class="invalid-feedback" style="color:#d9534f;margin-top:6px;font-size:13px">{{ $message }}</div> @enderror
        </div>

        <div class="actions">
          <label class="checkbox"><input type="checkbox" id="remember" name="remember" style="width:16px;height:16px"> Ingat saya</label>
          <a href="#" style="color: #5278ff; font-size:13px; text-decoration:none;">Lupa password?</a>
        </div>

        <button class="btn-primary" type="submit">Masuk</button>
      </form>

      <div class="muted">
        Demo akun: <br>
        <strong>owner@example.com</strong> / password • <strong>pegawai@example.com</strong> / password
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function togglePassword(){
    const pw = document.getElementById('password');
    if(!pw) return;
    if(pw.type === 'password'){ pw.type = 'text'; }
    else { pw.type = 'password'; }
  }

  // optional: submit on enter when inside input (default browser behavior usually works)
  (function(){
    const form = document.querySelector('form[method="POST"]');
    if(!form) return;
    form.addEventListener('submit', function(){
      // disable submit button to avoid double submit
      const btn = form.querySelector('button[type="submit"]');
      if(btn) btn.disabled = true;
    });
  })();
</script>
@endpush