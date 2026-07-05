<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    <main class="success-page">
        <div class="success-grid-bg" aria-hidden="true"></div>
        <div class="success-glow success-glow-primary" aria-hidden="true"></div>
        <div class="success-glow success-glow-secondary" aria-hidden="true"></div>

        <section class="success-panel" aria-label="Email verification">
            <div class="success-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M4 6h16v12H4z"></path>
                    <path d="m4 7 8 6 8-6"></path>
                </svg>
            </div>

            <header class="success-copy">
                <h1>Verify your <span>email</span></h1>
                <p>We sent a verification link to your inbox.</p>
            </header>

            @if (session('status'))
                <p class="form-success">
                    {{ session('status') === 'verification-link-sent' ? 'A fresh verification link has been sent.' : 'Your email has been verified.' }}
                </p>
            @endif

            <div class="success-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="success-primary">Resend Email</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="success-secondary">Logout</button>
                </form>
            </div>

            <div class="success-tech">
                <span aria-hidden="true"></span>
                <p>Mail driver: {{ config('mail.default') }}</p>
                <span aria-hidden="true"></span>
            </div>
        </section>
    </main>
</body>
</html>
