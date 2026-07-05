<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Cart | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    <header class="site-header">
        <div class="content-wrap header-bar">
            <a href="{{ route('home') }}" class="brand">THREADLAB</a>
            <nav class="main-nav" aria-label="Primary">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop') }}" class="is-active">Shop</a>
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
                <a href="{{ route('customer.dashboard') }}" aria-label="Account">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.2"></circle>
                        <path d="M6.5 19a5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="cart-page">
        <section class="content-wrap cart-hero">
            <h1>Your Cart</h1>
            <p>{{ $cartItems->count() }} selected {{ \Illuminate\Support\Str::plural('piece', $cartItems->count()) }}</p>
            @if ($errors->any())
                <p class="form-error">{{ $errors->first() }}</p>
            @endif
        </section>

        <section class="content-wrap cart-layout">
            <div class="cart-items">
                @include('partials.cart-items', ['cartItems' => $cartItems])
            </div>

            @include('partials.cart-summary', [
                'cartItems' => $cartItems,
                'subtotal' => $subtotal,
                'shippingTotal' => $shippingTotal,
                'total' => $total,
            ])
        </section>

        @guest
            @if ($cartItems->isNotEmpty())
                <div class="checkout-prompt" id="checkout-prompt" hidden>
                    <div class="checkout-prompt-backdrop" data-close-checkout-prompt></div>
                    <div class="checkout-prompt-dialog" role="dialog" aria-modal="true" aria-labelledby="checkout-prompt-title">
                        <button type="button" class="checkout-prompt-close" aria-label="Close prompt" data-close-checkout-prompt>&times;</button>
                        <p>Secure Checkout</p>
                        <h2 id="checkout-prompt-title">Please login/register to continue checkout.</h2>
                        <small>Your cart is saved. Sign in or create an account to complete your order.</small>
                        <div class="checkout-prompt-actions">
                            <a href="{{ route('customer.login', ['redirect_to' => '/checkout']) }}">Login</a>
                            <a href="{{ route('customer.register', ['redirect_to' => '/checkout']) }}">Register</a>
                        </div>
                    </div>
                </div>
            @endif
        @endguest

        <section class="cart-marquee" aria-label="Collection highlights">
            <div>
                <span>New Archive Drop 2026 / Worldwide Shipping / ThreadLab Atelier / Hand-Finished Details / Kinetic Editorial /</span>
                <span>New Archive Drop 2026 / Worldwide Shipping / ThreadLab Atelier / Hand-Finished Details / Kinetic Editorial /</span>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="content-wrap footer-bar">
            <div>
                <div class="footer-brand">THREADLAB</div>
                <div class="footer-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Shipping</a>
                    <a href="#">Returns</a>
                    <a href="#">Contact</a>
                </div>
            </div>
            <div class="footer-actions">
                <p class="footer-note">(c)2026 THREADLAB ATELIER. PRODUCED IN LIMITED QUANTITIES.</p>
            </div>
        </div>
    </footer>
    @guest
        @if ($cartItems->isNotEmpty())
            <script>
                (() => {
                    const prompt = document.getElementById('checkout-prompt');

                    if (! prompt) {
                        return;
                    }

                    const closePrompt = () => {
                        prompt.hidden = true;
                        document.body.classList.remove('has-dialog-open');
                    };

                    const openPrompt = () => {
                        prompt.hidden = false;
                        document.body.classList.add('has-dialog-open');
                    };

                    document.addEventListener('click', (event) => {
                        if (event.target.closest('[data-open-checkout-prompt]')) {
                            openPrompt();
                        }

                        if (event.target.closest('[data-close-checkout-prompt]')) {
                            closePrompt();
                        }
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape' && ! prompt.hidden) {
                            closePrompt();
                        }
                    });
                })();
            </script>
        @endif
    @endguest
    <script>
        (() => {
            const cartItemsContainer = document.querySelector('.cart-items');
            const cartHeroCount = document.querySelector('.cart-hero p');

            if (! cartItemsContainer || ! cartHeroCount) {
                return;
            }

            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('form[data-cart-ajax="true"]');

                if (! form) {
                    return;
                }

                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"], button:not([type])');
                const formData = new FormData(form);

                if (! formData.has('_token')) {
                    return;
                }

                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    if (! response.ok) {
                        throw new Error('Cart update failed.');
                    }

                    const payload = await response.json();

                    cartItemsContainer.innerHTML = payload.cartItemsHtml;

                    const nextSummary = document.createRange().createContextualFragment(payload.cartSummaryHtml);
                    document.querySelector('.cart-summary')?.replaceWith(nextSummary);

                    const nextHeaderCart = document.createRange().createContextualFragment(payload.headerCartHtml);
                    document.querySelector('.header-cart')?.replaceWith(nextHeaderCart);

                    cartHeroCount.textContent = payload.itemTypesCount + ' selected ' + payload.itemLabel;
                } catch (error) {
                    window.location.reload();
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        })();
    </script>
</body>
</html>
