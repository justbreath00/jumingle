<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jumingle')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f4f6f9;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* Nav */
        .nav {
            background: #fff;
            border-bottom: 1px solid #e2e6ea;
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .nav-icon {
            width: 32px;
            height: 32px;
            background: #1a73e8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-icon svg { width: 18px; height: 18px; fill: #fff; }
        .nav-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            letter-spacing: -0.3px;
        }

        /* Page wrapper */
        .page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 56px);
            padding: 2rem;
        }

        /* Card */
        .card {
            background: #fff;
            border: 1px solid #e2e6ea;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
        }
        .card-wide { max-width: 560px; }

        /* Typography */
        h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 6px; }
        h2 { font-size: 20px; font-weight: 600; margin-bottom: 4px; }
        .sub { color: #6b7280; font-size: 14px; line-height: 1.5; margin-bottom: 2rem; }

        /* Form */
        label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        input[type="text"], input[type="url"] {
            width: 100%;
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            color: #1a1a2e;
            background: #fff;
            outline: none;
            transition: border-color 0.15s;
        }
        input[type="text"]:focus, input[type="url"]:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
        }
        .form-group { margin-bottom: 1.25rem; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 42px;
            padding: 0 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: #1a73e8; color: #fff; }
        .btn-primary:hover { background: #1557b0; }
        .btn-outline { background: #fff; color: #1a73e8; border: 1px solid #1a73e8; }
        .btn-outline:hover { background: #f0f5ff; }
        .btn-gray { background: #f3f4f6; color: #374151; }
        .btn-gray:hover { background: #e5e7eb; }
        .btn-full { width: 100%; }
        .btn-sm { height: 34px; padding: 0 14px; font-size: 13px; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }

        /* Link box */
        .link-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f5ff;
            border: 1px solid #c7d9f8;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 1.5rem;
        }
        .link-text {
            flex: 1;
            font-size: 13px;
            color: #1557b0;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }
        .copy-btn {
            flex-shrink: 0;
            height: 32px;
            padding: 0 12px;
            border-radius: 6px;
            background: #1a73e8;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .copy-btn:hover { background: #1557b0; }
        .copy-btn.copied { background: #16a34a; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            color: #9ca3af;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { color: #1a73e8; }
        .back-link svg { width: 14px; height: 14px; }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #dcfce7; color: #15803d; }

        /* Alert */
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 1rem;
        }
        .alert-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="nav">
    <a href="{{ route('dashboard') }}" class="nav-logo">
        <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        </div>
        <span class="nav-title">Jumingle</span>
    </a>
</nav>

<div class="page">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>