<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>@yield('title') — ACLC Clinic</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0; padding: 0;
            min-height: 100vh;
            display: flex;
        }
        /* LEFT — background photo */
        .auth-bg {
            flex: 1;
            background: url('/485808979_1058217346342201_5140050072900723268_n.jpg') center/cover no-repeat;
            position: relative;
            display: none;
        }
        .auth-bg::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(15,27,45,.75) 0%, rgba(29,78,216,.45) 100%);
        }
        .auth-bg-content {
            position: relative; z-index: 1;
            padding: 3rem;
            display: flex; flex-direction: column; justify-content: flex-end; height: 100%;
        }
        .auth-bg-content h2 { color: #fff; font-weight: 700; font-size: 1.8rem; margin-bottom: .5rem; }
        .auth-bg-content p  { color: rgba(255,255,255,.7); font-size: .9rem; }

        /* RIGHT — form panel */
        .auth-panel {
            width: 100%; max-width: 460px;
            background: #fff;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 3rem 2.5rem;
            min-height: 100vh;
        }
        .auth-logo {
            display: flex; align-items: center; gap: .75rem;
            margin-bottom: 2.5rem;
        }
        .auth-logo .logo-icon {
            width: 42px; height: 42px;
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; color: #fff; font-size: 1.2rem;
        }
        .auth-logo .logo-name { font-weight: 700; font-size: 1rem; color: #0f1b2d; line-height: 1.2; }
        .auth-logo .logo-sub  { font-size: .72rem; color: #94a3b8; }

        .auth-title { font-size: 1.5rem; font-weight: 700; color: #0f1b2d; margin-bottom: .35rem; }
        .auth-sub   { font-size: .85rem; color: #64748b; margin-bottom: 2rem; }

        .form-label { font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
        .form-control {
            font-size: .875rem; border-color: #d1d5db;
            border-radius: 8px; padding: .6rem .85rem;
        }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
        .btn-auth {
            background: #1d4ed8; color: #fff; border: none;
            border-radius: 8px; padding: .7rem; font-size: .9rem;
            font-weight: 600; width: 100%; cursor: pointer;
            transition: background .2s;
        }
        .btn-auth:hover { background: #1e40af; }

        @media(min-width: 768px) {
            .auth-bg { display: flex; }
            .auth-panel { min-height: 100vh; }
        }

        /* ANIMATIONS */
        @keyframes panelIn {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .auth-panel { animation: panelIn .45s cubic-bezier(.22,.68,0,1.2) both; }

        @keyframes bgIn {
            from { opacity: 0; transform: scale(1.04); }
            to   { opacity: 1; transform: scale(1); }
        }
        .auth-bg { animation: bgIn .6s ease both; }

        @keyframes logoIn {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .auth-logo { animation: logoIn .4s ease .15s both; }

        @keyframes formIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .auth-title { animation: formIn .4s ease .2s both; }
        .auth-sub   { animation: formIn .4s ease .25s both; }
        form        { animation: formIn .4s ease .3s both; }

        .form-control {
            transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
        }
        .form-control:focus { transform: translateY(-1px); }

        .btn-auth {
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            position: relative; overflow: hidden;
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(29,78,216,.35); }
        .btn-auth:active { transform: translateY(0); box-shadow: none; }

        .logo-icon { transition: transform .2s ease; }
        .auth-logo:hover .logo-icon { transform: scale(1.1) rotate(-5deg); }
    </style>
</head>
<body>
    <div class="auth-bg">
        <div class="auth-bg-content">
            <h2>ACLC Clinic<br>Information & Inventory</h2>
            <p>Manage clinic medications, track inventory,<br>and process student requests efficiently.</p>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-logo">
            <div class="logo-icon"><img src="/newAclcLogo-BQdiVkLw-removebg-preview.png" alt="logo" class="img-fluid"></div>
            <div>
                <div class="logo-name">ACLC Clinic</div>
                <div class="logo-sub">Information & Inventory System</div>
            </div>
        </div>
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
