<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
