<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    <main class="customer-login-page">
        <section class="customer-login-visual">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqmBIkICqUgdwWrmESCz8QNvzs4jDK72JLsXZ6Qx863AQwthy7LN88m-FZGNzjS1wBBbR46-hgQKxF2IMrnNz3n_YiSREwPG8XfDNAdi8gLj8kkEz1YMClHueucmbcYXbQYAKN_EDJkQDxUPuQpJpW4RJ1seyrqZw7u4OOz6OPoc2jogFqprA2CIMkuA4qn1FwwBntLO4IZc00InG11VGwUqZqBp8YuU3BwPWSZ-J4_NBHp9jW9sS8BOliGY-HsmIrrwIXOmonoyk" alt="High-impact streetwear model">
            <div class="customer-login-badge">Account Recovery</div>
            <div class="customer-login-copy">
                <h1>Reset Access</h1>
                <div>
                    <span aria-hidden="true"></span>
                    <p>Password recovery portal</p>
                </div>
            </div>
        </section>

        <section class="customer-login-panel" aria-label="Forgot password form">
            <div class="customer-login-card">
                <a href="{{ route('home') }}" class="customer-login-mark">THREADLAB</a>
                <header>
                    <h2>Forgot Password</h2>
                    <p>We will send a reset link to your email address.</p>
                </header>

                @if (session('status'))
                    <p class="form-success">{{ session('status') }}</p>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="customer-login-form">
                    @csrf
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" placeholder="name@threadlab.com" value="{{ old('email') }}" required>
                    </label>
                    <button type="submit">Send Reset Link</button>
                </form>
                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <footer class="customer-login-footer">
                    <p>Remembered it? <a href="{{ route('customer.login') }}">Back to Login</a></p>
                </footer>
            </div>
        </section>
    </main>
</body>
</html>
