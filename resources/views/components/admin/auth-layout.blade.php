<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | ShopMe</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --indigo: #4f46e5;
      --indigo-dark: #4338ca;
      --indigo-light: #eef2ff;
      --indigo-border: #c7d2fe;
      --navy: #1a1f3e;
      --white: #ffffff;
      --bg: #f0f2f8;
      --card-border: #e2e5ef;
      --text-main: #0f172a;
      --text-sub: #64748b;
      --text-muted: #94a3b8;
      --input-bg: #f8fafc;
      --input-border: #e2e8f0;
      --success: #10b981;
      --error: #ef4444;
      --warning: #f59e0b;
      --blue: #3b82f6;
      --radius-sm: 9px;
      --radius-md: 11px;
      --radius-lg: 22px;
    }

    html, body { height: 100%; font-family: 'Inter', system-ui, sans-serif; background: var(--bg); -webkit-font-smoothing: antialiased; }

    .page { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }

    /* LEFT PANEL */
    .left {
      background: var(--navy);
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 2.5rem; position: relative; overflow: hidden;
    }
    .bg-circle { position: absolute; border-radius: 50%; pointer-events: none; }
    .bg-circle.c1 { width: 400px; height: 400px; top: -120px; left: -130px; background: rgba(99,102,241,.13); }
    .bg-circle.c2 { width: 280px; height: 280px; bottom: -70px; right: -90px; background: rgba(139,92,246,.1); }
    .bg-circle.c3 { width: 130px; height: 130px; top: 46%; right: 18px; background: rgba(99,102,241,.07); }

    .brand { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
    .logo-box {
      width: 40px; height: 40px;
      background: rgba(99,102,241,.22); border: 1px solid rgba(99,102,241,.4);
      border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .logo-box i { font-size: 21px; color: #a5b4fc; }
    .brand-name { font-size: 1.08rem; font-weight: 700; color: #fff; letter-spacing: .02em; }
    .brand-tag {
      font-size: .62rem; font-weight: 600; color: #818cf8;
      background: rgba(99,102,241,.2); border: 1px solid rgba(99,102,241,.35);
      border-radius: 5px; padding: 2px 8px; letter-spacing: .06em;
    }

    .left-body { position: relative; z-index: 1; }
    .left-body h2 { font-size: clamp(1.5rem, 2.5vw, 1.85rem); font-weight: 700; color: #fff; line-height: 1.28; margin-bottom: .6rem; }
    .left-body p { font-size: .88rem; color: rgba(255,255,255,.5); line-height: 1.65; max-width: 290px; }

    .feat-list { margin-top: 1.6rem; display: flex; flex-direction: column; gap: .8rem; }
    .feat { display: flex; align-items: center; gap: 11px; }
    .feat-icon {
      width: 30px; height: 30px; flex-shrink: 0;
      background: rgba(99,102,241,.18); border: 1px solid rgba(99,102,241,.3);
      border-radius: 8px; display: flex; align-items: center; justify-content: center;
    }
    .feat-icon i { font-size: 15px; color: #a5b4fc; }
    .feat span { font-size: .82rem; color: rgba(255,255,255,.55); }

    .stat-row {
      display: flex; border: 1px solid rgba(255,255,255,.08);
      border-radius: 14px; overflow: hidden;
      background: rgba(255,255,255,.04); position: relative; z-index: 1;
    }
    .stat-item { flex: 1; padding: .9rem .5rem; text-align: center; border-right: 1px solid rgba(255,255,255,.07); }
    .stat-item:last-child { border-right: none; }
    .stat-n { font-size: 1.15rem; font-weight: 700; color: #fff; }
    .stat-l { font-size: .65rem; color: rgba(255,255,255,.38); margin-top: 3px; text-transform: uppercase; letter-spacing: .07em; }

    /* RIGHT PANEL */
    .right { background: var(--bg); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2.5rem; }

    .card { width: 100%; max-width: 400px; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--card-border); padding: 2.25rem; position: relative; }

    .alert {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 14px; border-radius: var(--radius-sm);
      font-size: .8rem; font-weight: 500; margin-bottom: 1.25rem;
    }
    .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .alert i { font-size: 15px; flex-shrink: 0; }

    .card-head { margin-bottom: 1.75rem; }
    .secure-badge {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: .72rem; font-weight: 600; color: var(--indigo);
      background: var(--indigo-light); border: 1px solid var(--indigo-border);
      border-radius: 20px; padding: 4px 12px; margin-bottom: 1rem; letter-spacing: .03em;
    }
    .secure-badge i { font-size: 13px; }
    .card-head h2 { font-size: 1.45rem; font-weight: 700; color: var(--text-main); margin-bottom: .25rem; }
    .card-head p { font-size: .83rem; color: var(--text-sub); }

    .field { margin-bottom: 1.1rem; }
    .field label { display: block; font-size: .75rem; font-weight: 600; color: #374151; margin-bottom: .35rem; letter-spacing: .02em; }
    .inp-wrap { position: relative; }
    .inp-wrap .ico-l { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 16px; color: var(--text-muted); pointer-events: none; }
    .inp-wrap input {
      width: 100%; height: 46px;
      padding: 0 42px 0 42px;
      border: 1.5px solid var(--input-border);
      border-radius: var(--radius-md);
      font-size: .875rem; color: var(--text-main);
      background: var(--input-bg); outline: none;
      transition: border-color .15s, box-shadow .15s, background .15s;
      font-family: inherit; -webkit-appearance: none;
    }
    .inp-wrap input::placeholder { color: #c0c8d8; }
    .inp-wrap input:focus { border-color: var(--indigo); background: var(--white); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
    .inp-wrap input.is-invalid { border-color: var(--error); }
    .inp-wrap input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
    .invalid-feedback { font-size: .72rem; color: var(--error); margin-top: 5px; display: flex; align-items: center; gap: 4px; }
    .invalid-feedback i { font-size: 13px; }

    .eye-btn {
      position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      color: var(--text-muted); font-size: 16px; padding: 4px;
      display: flex; align-items: center; transition: color .12s;
      -webkit-tap-highlight-color: transparent;
    }
    .eye-btn:hover { color: var(--indigo); }

    .strength-wrap { margin-top: 7px; display: none; }
    .strength-bars { display: flex; gap: 4px; }
    .sb { flex: 1; height: 3px; border-radius: 2px; background: var(--input-border); transition: background .3s; }
    .sb.weak { background: var(--error); }
    .sb.fair { background: var(--warning); }
    .sb.good { background: var(--blue); }
    .sb.strong { background: var(--success); }
    .strength-txt { font-size: .69rem; color: var(--text-muted); margin-top: 4px; }

    .opts { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.3rem; margin-top: .1rem; }
    .remember { display: flex; align-items: center; gap: 7px; cursor: pointer; user-select: none; }
    .remember input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--indigo); cursor: pointer; }
    .remember span { font-size: .78rem; color: #475569; }
    .forgot-link { font-size: .78rem; color: var(--indigo); text-decoration: none; font-weight: 600; }
    .forgot-link:hover { text-decoration: underline; }

    .btn-login {
      width: 100%; height: 46px;
      background: var(--indigo); color: var(--white);
      border: none; border-radius: var(--radius-md);
      font-size: .9rem; font-weight: 600;
      cursor: pointer; font-family: inherit;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      letter-spacing: .01em;
      transition: background .15s, transform .1s;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-login:hover { background: var(--indigo-dark); }
    .btn-login:active { transform: scale(.98); }
    .btn-login:disabled { background: #a5b4fc; cursor: not-allowed; transform: none; }

    .or { display: flex; align-items: center; gap: 10px; margin: 1rem 0; }
    .or .ln { flex: 1; height: 1px; background: var(--input-border); }
    .or span { font-size: .72rem; color: var(--text-muted); font-weight: 500; }

    .sso-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .sso-btn {
      height: 42px; background: var(--white);
      border: 1.5px solid var(--input-border); border-radius: var(--radius-sm);
      font-size: .78rem; font-weight: 500; color: #374151;
      cursor: pointer; font-family: inherit;
      display: flex; align-items: center; justify-content: center; gap: 7px;
      transition: border-color .12s, background .12s, color .12s;
      -webkit-tap-highlight-color: transparent;
      text-decoration: none;
    }
    .sso-btn i { font-size: 16px; }
    .sso-btn:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo-dark); }

    .card-foot { text-align: center; margin-top: 1.25rem; font-size: .72rem; color: var(--text-muted); }
    .card-foot a { color: var(--indigo); text-decoration: none; font-weight: 500; }
    .card-foot a:hover { text-decoration: underline; }
    .page-foot { text-align: center; margin-top: 1.25rem; font-size: .7rem; color: var(--text-muted); }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spinning { animation: spin .7s linear infinite; display: inline-block; }

    @media (max-width: 900px) {
      .left { padding: 2rem; }
      .right { padding: 2rem; }
    }
    @media (max-width: 700px) {
      .page { grid-template-columns: 1fr; min-height: unset; }
      .left { padding: 1.5rem; min-height: unset; }
      .left-body h2 { font-size: 1.35rem; }
      .left-body p { max-width: 100%; }
      .feat-list { display: none; }
      .stat-row { margin-top: 1.25rem; }
      .stat-n { font-size: 1rem; }
      .right { padding: 1.5rem 1rem 2rem; }
      .card { border-radius: 18px; padding: 1.75rem 1.25rem; border: none; box-shadow: 0 2px 20px rgba(15,23,42,.08); }
    }
    @media (max-width: 400px) {
      .right { padding: 1.25rem .75rem 2rem; }
      .card { padding: 1.5rem 1rem; }
      .card-head h2 { font-size: 1.25rem; }
      .sso-grid { grid-template-columns: 1fr; }
      .stat-row { display: none; }
    }
  </style>
</head>
<body>

<div class="page">

  {{-- ── LEFT PANEL ── --}}
  <div class="left">
    <div class="bg-circle c1"></div>
    <div class="bg-circle c2"></div>
    <div class="bg-circle c3"></div>

    <div class="brand">
      <div class="logo-box"><i class="ti ti-shield-check" aria-hidden="true"></i></div>
      <span class="brand-name">ShopMe</span>
      <span class="brand-tag">ADMIN</span>
    </div>

    <div class="left-body">
      <h2>Your store,<br>fully in control.</h2>
      <p>One dashboard to manage orders, inventory, analytics, and your entire team.</p>
      <div class="feat-list">
        <div class="feat"><div class="feat-icon"><i class="ti ti-chart-bar" aria-hidden="true"></i></div><span>Real-time sales analytics</span></div>
        <div class="feat"><div class="feat-icon"><i class="ti ti-package" aria-hidden="true"></i></div><span>Smart inventory management</span></div>
        <div class="feat"><div class="feat-icon"><i class="ti ti-users" aria-hidden="true"></i></div><span>Multi-role team access</span></div>
        <div class="feat"><div class="feat-icon"><i class="ti ti-bell" aria-hidden="true"></i></div><span>Instant order notifications</span></div>
      </div>
    </div>

    <div class="stat-row">
      <div class="stat-item"><div class="stat-n">12k+</div><div class="stat-l">Orders</div></div>
      <div class="stat-item"><div class="stat-n">₹2.4M</div><div class="stat-l">Revenue</div></div>
      <div class="stat-item"><div class="stat-n">99.9%</div><div class="stat-l">Uptime</div></div>
    </div>
  </div>

  {{-- ── RIGHT PANEL ── --}}
  <div class="right">
    <div class="card">

      <div class="card-head">
        <div class="secure-badge">
          <i class="ti ti-lock" aria-hidden="true"></i> Secure login
        </div>
        <h2>Welcome back</h2>
        <p>Sign in to your admin panel</p>
      </div>

      {{-- Session error (e.g. wrong credentials) --}}
      @if (session('error'))
        <div class="alert alert-error">
          <i class="ti ti-alert-circle" aria-hidden="true"></i>
          {{ session('error') }}
        </div>
      @endif

      {{-- Session success (e.g. password reset complete) --}}
      @if (session('status'))
        <div class="alert alert-success">
          <i class="ti ti-circle-check" aria-hidden="true"></i>
          {{ session('status') }}
        </div>
      @endif

    <!-- page content -->

    {{ $slot }}

    </div>


    <p class="page-foot">&copy; {{ date('Y') }} ShopMe. All rights reserved.</p>
  </div>

</div>

<script>
  function togglePassword() {
    var input = document.getElementById('password');
    var icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'ti ti-eye-off';
    } else {
      input.type = 'password';
      icon.className = 'ti ti-eye';
    }
  }

  function updateStrength(val) {
    var wrap = document.getElementById('strength-wrap');
    var bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
    var txt  = document.getElementById('strength-txt');

    if (!val) { wrap.style.display = 'none'; bars.forEach(function(b){ b.className='sb'; }); return; }

    wrap.style.display = 'block';
    bars.forEach(function(b){ b.className='sb'; });

    var score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    var levels = [
      { cls:'weak',   label:'Weak',   color:'#ef4444' },
      { cls:'fair',   label:'Fair',   color:'#f59e0b' },
      { cls:'good',   label:'Good',   color:'#3b82f6' },
      { cls:'strong', label:'Strong', color:'#10b981' }
    ];
    var lvl = levels[score - 1] || levels[0];
    for (var i = 0; i < score; i++) bars[i].className = 'sb ' + lvl.cls;
    txt.textContent  = lvl.label;
    txt.style.color  = lvl.color;
  }

  document.getElementById('login-form').addEventListener('submit', function() {
    var btn = document.getElementById('login-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinning"><i class="ti ti-loader-2"></i></span>&nbsp; Signing in…';
  });
</script>

</body>
</html>
