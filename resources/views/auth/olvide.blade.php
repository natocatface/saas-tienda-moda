<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Recuperar contraseña — SaaS Tienda Moda</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing:border-box; margin:0; padding:0; font-family:'Poppins',sans-serif; }
    body { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px;
           background:linear-gradient(135deg,#7b1fa2,#e8398c); }
    .card { background:#fff; width:100%; max-width:420px; border-radius:18px; padding:38px 34px;
            box-shadow:0 20px 60px rgba(0,0,0,.25); }
    .icon { width:62px; height:62px; border-radius:50%; margin:0 auto 16px; display:flex; align-items:center;
            justify-content:center; font-size:26px; color:#fff; background:linear-gradient(135deg,#e8398c,#7b1fa2); }
    h2 { text-align:center; font-size:21px; color:#1f2937; margin-bottom:6px; }
    p.sub { text-align:center; color:#6b7280; font-size:13px; margin-bottom:22px; }
    label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
    .input-wrap { position:relative; margin-bottom:18px; }
    .input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#aaa; }
    .input-wrap input { width:100%; padding:12px 14px 12px 42px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; }
    .input-wrap input:focus { outline:none; border-color:#e8398c; }
    .btn { width:100%; padding:13px; border:none; border-radius:10px; font-size:15px; font-weight:600; color:#fff;
           cursor:pointer; background:linear-gradient(135deg,#e8398c,#7b1fa2); }
    .alert { padding:11px 14px; border-radius:10px; font-size:13px; margin-bottom:18px; }
    .alert-ok { background:#dcfce7; color:#166534; }
    .alert-info { background:#fdf2f8; color:#9d174d; border:1px solid #fbcfe8; word-break:break-all; }
    .back { display:block; text-align:center; margin-top:20px; font-size:13px; color:#e8398c; font-weight:600; text-decoration:none; }
    .err { color:#e8398c; font-size:12px; display:block; margin:-12px 0 14px; }
</style>
</head>
<body>
    <div class="card">
        <div class="icon"><i class="fa-solid fa-key"></i></div>
        <h2>¿Olvidaste tu contraseña?</h2>
        <p class="sub">Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>

        @if(session('status'))
            <div class="alert alert-ok"><i class="fa-solid fa-circle-check"></i> {{ session('status') }}</div>
        @endif

        @if(session('reset_url'))
            <div class="alert alert-info">
                <b>Modo desarrollo:</b> usa este enlace para continuar (sin servidor de correo):<br>
                <a href="{{ session('reset_url') }}" style="color:#7b1fa2;font-weight:600;">{{ session('reset_url') }}</a>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email">Correo electrónico</label>
            <div class="input-wrap">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@tiendamoda.com" required autofocus>
            </div>
            @error('email')<small class="err">{{ $message }}</small>@enderror
            <button type="submit" class="btn"><i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i>Enviar enlace</button>
        </form>

        <a class="back" href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Volver al inicio de sesión</a>
    </div>
</body>
</html>
