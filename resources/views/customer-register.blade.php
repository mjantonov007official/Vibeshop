<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join The Registry | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="content-wrap header-bar">
            <a href="{{ route('home') }}" class="brand">THREADLAB</a>
            <nav class="main-nav" aria-label="Primary">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop') }}">Shop</a>
                <a href="{{ route('home') }}#collections">Collections</a>
                <a href="{{ route('home') }}#faq">Archive</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>
            <div class="header-tools" aria-label="Quick actions">
                                <form method="GET" action="{{ route('shop') }}" class="header-search" role="search">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search products">
                    <button type="submit" aria-label="Search products">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="6"></circle>
                            <path d="M20 20l-4.2-4.2"></path>
                        </svg>
                    </button>
                </form>
                @include('partials.mini-cart')
                <a href="{{ route('customer.register') }}" aria-label="Account" class="is-active">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.2"></circle>
                        <path d="M6.5 19a5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="customer-register-page">
        <section class="customer-register-visual">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfW8aakj1ITa020I4Wo6OfjLAD1M08JZOtbMNmSJJjZ9js9pS39TeE7wQhipMstE4WjpSTbb4UCJSEKM70bbdagvCwU_cNg-D0gtEgqJtGOVkeWwpfaruGMcKXZJcIYLR-6-5H-2FztLNIDtuo1attZ7u7PYLb9A4BRRW671tZh_XuFjE1a5OIe-R52kBsr0caRPsqKeFaM-VfLG6FKb0yKUXx2JpMR1c-jn2YxZamY1omIDCLKMV2oSrHdxMpHHVIuffWqi5EEtU" alt="Editorial streetwear model">
            <div class="customer-register-copy">
                <p>V-01 Access Granted</p>
                <h1>Join The<br>Registry</h1>
                <div>
                    <span aria-hidden="true"></span>
                    <strong>Est. 2026 / Core Unit</strong>
                </div>
            </div>
        </section>

        <section class="customer-register-panel" aria-label="Customer registration form">
            <div class="customer-register-card">
                <header>
                    <h2>Create Account</h2>
                    <p>Start your journey with THREADLAB.</p>
                </header>

                <form action="{{ route('customer.register.store') }}" method="POST" class="customer-register-form">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                    <input type="hidden" name="buy_now_product_id" value="{{ request('buy_now_product_id') }}">
                    <input type="hidden" name="buy_now_size" value="{{ request('buy_now_size') }}">
                    <input type="hidden" name="buy_now_quantity" value="{{ request('buy_now_quantity', 1) }}">
                    <label>
                        <span>Full Name</span>
                        <input type="text" name="name" placeholder="ALEXANDER VOGUE" value="{{ old('name') }}" required>
                    </label>
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" placeholder="IDENTITY@THREADLAB.COM" value="{{ old('email') }}" required>
                    </label>
                    <div class="customer-register-grid">
                        <label>
                            <span>Password</span>
                            <input type="password" name="password" placeholder="Password" required>
                        </label>
                        <label>
                            <span>Confirm</span>
                            <input type="password" name="password_confirmation" placeholder="Password" required>
                        </label>
                    </div>
                    <label class="customer-register-terms">
                        <input type="checkbox" name="terms" required>
                        <span>I agree to the <a href="#">Terms & Privacy Policy</a></span>
                    </label>
                    <button type="submit">Register</button>
                </form>
                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <footer class="customer-register-footer">
                    <p>Already a member? <a href="{{ route('customer.login') }}">Login</a></p>
                    <div>
                        <span>Encrypted</span>
                        <span>Global Access</span>
                    </div>
                </footer>
            </div>
        </section>
    </main>

    <footer class="customer-register-site-footer">
        <p>(c)2026 THREADLAB KINETIC EDITORIAL. ALL RIGHTS RESERVED.</p>
        <nav aria-label="Footer">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Accessibility</a>
        </nav>
    </footer>
</body>
</html>
