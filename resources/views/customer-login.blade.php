<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    <main class="customer-login-page">
        <section class="customer-login-visual">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqmBIkICqUgdwWrmESCz8QNvzs4jDK72JLsXZ6Qx863AQwthy7LN88m-FZGNzjS1wBBbR46-hgQKxF2IMrnNz3n_YiSREwPG8XfDNAdi8gLj8kkEz1YMClHueucmbcYXbQYAKN_EDJkQDxUPuQpJpW4RJ1seyrqZw7u4OOz6OPoc2jogFqprA2CIMkuA4qn1FwwBntLO4IZc00InG11VGwUqZqBp8YuU3BwPWSZ-J4_NBHp9jW9sS8BOliGY-HsmIrrwIXOmonoyk" alt="High-impact streetwear model">
            <div class="customer-login-badge">Est. 2026 / Authenticated</div>
            <div class="customer-login-copy">
                <h1>Kinetic Access</h1>
                <div>
                    <span aria-hidden="true"></span>
                    <p>The Digital Atelier</p>
                </div>
            </div>
        </section>

        <section class="customer-login-panel" aria-label="Customer login form">
            <div class="customer-login-card">
                <a href="{{ route('home') }}" class="customer-login-mark">THREADLAB</a>
                <header>
                    <h2>Welcome Back</h2>
                    <p>Login to access your kinetic registry.</p>
                </header>

                <a
                    href="{{ route('customer.google.redirect', request()->only(['redirect_to', 'buy_now_product_id', 'buy_now_size', 'buy_now_quantity'])) }}"
                    class="customer-social-button"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.26-.96 2.33-2.04 3.06l3.3 2.56c1.92-1.77 3.03-4.38 3.03-7.49 0-.73-.07-1.43-.19-2.11H12Z"/>
                        <path fill="#4285F4" d="M12 22c2.75 0 5.05-.91 6.74-2.48l-3.3-2.56c-.91.61-2.07.97-3.44.97-2.64 0-4.88-1.78-5.67-4.18H2.92v2.64A10.18 10.18 0 0 0 12 22Z"/>
                        <path fill="#FBBC05" d="M6.33 13.75A6.12 6.12 0 0 1 6 11.8c0-.68.12-1.34.33-1.95V7.21H2.92A10.18 10.18 0 0 0 1.8 11.8c0 1.64.39 3.18 1.12 4.59l3.41-2.64Z"/>
                        <path fill="#34A853" d="M12 5.67c1.5 0 2.84.52 3.9 1.53l2.92-2.92C17.04 2.63 14.74 1.6 12 1.6A10.18 10.18 0 0 0 2.92 7.21l3.41 2.64C7.12 7.45 9.36 5.67 12 5.67Z"/>
                    </svg>
                    <span>Continue With Google</span>
                </a>

                <div class="customer-auth-divider" aria-hidden="true">
                    <span>or</span>
                </div>

                <form action="{{ route('customer.login.store') }}" method="POST" class="customer-login-form">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                    <input type="hidden" name="buy_now_product_id" value="{{ request('buy_now_product_id') }}">
                    <input type="hidden" name="buy_now_size" value="{{ request('buy_now_size') }}">
                    <input type="hidden" name="buy_now_quantity" value="{{ request('buy_now_quantity', 1) }}">
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" placeholder="name@threadlab.com" value="{{ old('email') }}" required>
                    </label>
                    <label>
                        <span class="customer-login-label-row">
                            Password
                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                        </span>
                        <input type="password" name="password" placeholder="Password" required>
                    </label>
                    <label class="customer-login-remember">
                        <input type="checkbox" name="remember">
                        <span>Remember Me</span>
                    </label>
                    <button type="submit">Login</button>
                </form>
                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <footer class="customer-login-footer">
                    <p>New to THREADLAB? <a href="{{ route('customer.register') }}">Create Account</a></p>
                </footer>
            </div>
        </section>
    </main>

    <footer class="customer-login-site-footer">
        <a href="{{ route('home') }}">THREADLAB</a>
        <nav aria-label="Footer">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Accessibility</a>
        </nav>
        <p>(c)2026 THREADLAB KINETIC EDITORIAL. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>
