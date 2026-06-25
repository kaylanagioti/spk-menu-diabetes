{{-- resources/views/admin/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SPK Diabetes</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            padding: 40px 36px;
            width: 360px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }
        .login-card h1 {
            font-size: 20px;
            color: #1a4a1a;
            margin-bottom: 4px;
        }
        .login-card .subtitle {
            color: #666;
            font-size: 13px;
            margin-bottom: 28px;
        }
        label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; }
        input[type=password] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 6px;
        }
        input[type=password]:focus { outline: none; border-color: #2c7a2c; }
        .error-msg { color: #c0392b; font-size: 12px; margin-bottom: 14px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 18px; }
        .btn-login {
            width: 100%;
            background: #1a4a1a;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 11px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 6px;
        }
        .btn-login:hover { background: #2c7a2c; }
        .back-link { display: block; text-align: center; margin-top: 16px; font-size: 12px; color: #888; text-decoration: none; }
        .back-link:hover { color: #333; }
    </style>
</head>
<body>
<div class="login-card">
    <h1>🛠️ Admin Panel</h1>
    <p class="subtitle">SPK Rekomendasi Menu Anak DM Tipe 1</p>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <label for="password">Password Admin</label>
        <input type="password" id="password" name="password" autofocus required
               placeholder="Masukkan password admin">
        @error('password')
            <div class="error-msg">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn-login">Masuk</button>
    </form>

    <a class="back-link" href="{{ route('rekomendasi.index') }}">← Kembali ke halaman utama</a>
</div>
</body>
</html>
