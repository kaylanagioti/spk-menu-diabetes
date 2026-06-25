{{-- resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — SPK Diabetes</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #1a4a1a; color: white; padding: 12px 24px; display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .navbar a { color: #cfc; text-decoration: none; font-size: 14px; }
        .navbar a:hover { color: white; }
        .navbar .brand { font-weight: bold; color: white; margin-right: 16px; white-space: nowrap; }
        .navbar .nav-right { margin-left: auto; display: flex; gap: 16px; align-items: center; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background: #f9f9f9; font-weight: bold; }
        .btn { display: inline-block; padding: 7px 14px; border-radius: 5px; text-decoration: none; font-size: 13px; cursor: pointer; border: none; }
        .btn-green  { background: #2c7a2c; color: white; }
        .btn-blue   { background: #1a56db; color: white; }
        .btn-red    { background: #c0392b; color: white; }
        .btn-gray   { background: #888; color: white; }
        .btn-outline { background: transparent; border: 1px solid #cfc; color: #cfc; font-size: 13px; padding: 5px 12px; border-radius: 5px; cursor: pointer; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 16px; border-radius: 5px; margin-bottom: 16px; }
        .alert-error   { background: #f8d7da; color: #721c24; padding: 10px 16px; border-radius: 5px; margin-bottom: 16px; }
        label { display: block; margin-bottom: 4px; font-size: 13px; font-weight: bold; }
        input[type=text], input[type=number], input[type=url], select, textarea {
            width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 5px;
            font-size: 14px; margin-bottom: 14px;
        }
        .error-msg { color: red; font-size: 12px; margin-top: -10px; margin-bottom: 10px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .section-divider { border: none; border-top: 1px solid #e8e8e8; margin: 20px 0; }
        .section-title { font-size: 13px; font-weight: bold; color: #2c7a2c; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px; }
    </style>
</head>
<body>

<div class="navbar">
    <span class="brand">🛠️ Admin Panel</span>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.menu.index') }}">Kelola Menu</a>
    <a href="{{ route('admin.gizi.index') }}">Kandungan Gizi</a>
    <a href="{{ route('admin.riwayat.index') }}">Riwayat Pemilihan</a>
    <div class="nav-right">
        <a href="{{ route('rekomendasi.index') }}">← Sisi User</a>
        <form action="{{ route('admin.logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-outline">Keluar</button>
        </form>
    </div>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

</body>
</html>
