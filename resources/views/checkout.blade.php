<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    @php
        $initialShippingMethod = old('shipping_method', 'standard');
    @endphp
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

    <main class="checkout-page">
        <section class="content-wrap checkout-layout">
            <div class="checkout-main">
                <header class="checkout-hero">
                    <h1>Checkout</h1>
                    <p>Secure your selection from the Digital Atelier.</p>
                    @if ($errors->any())
                        <p class="form-error">{{ $errors->first() }}</p>
                    @endif
                </header>

                <form id="checkout-form" method="POST" action="{{ route('checkout.place') }}" class="checkout-form">
                    @csrf
                    <section class="checkout-section">
                        <div class="checkout-section-head">
                            <h2>01 / Contact & Shipping Address</h2>
                        </div>
                        <div class="checkout-field-grid">
                            <label class="checkout-field">
                                <span>First Name</span>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="ALEXANDER" required>
                            </label>
                            <label class="checkout-field">
                                <span>Last Name</span>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="MCQUEEN" required>
                            </label>
                            <label class="checkout-field checkout-field-wide">
                                <span>Email Address</span>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="you@email.com" required>
                            </label>
                            <label class="checkout-field checkout-field-wide">
                                <span>Street Address</span>
                                <input type="text" name="street_address" value="{{ old('street_address') }}" placeholder="128 STUDIO ALLEY, SUITE 4" required>
                            </label>
                            <label class="checkout-field">
                                <span>City</span>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="METRO MANILA" required>
                            </label>
                            <label class="checkout-field">
                                <span>Zip Code</span>
                                <input type="text" name="zip_code" value="{{ old('zip_code') }}" placeholder="1200" required>
                            </label>
                            <label class="checkout-field">
                                <span>Phone Number</span>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+63 000 000 0000" required>
                            </label>
                        </div>
                    </section>

                    <section class="checkout-section">
                        <div class="checkout-section-head">
                            <h2>02 / Shipping Method</h2>
                        </div>
                        <div class="checkout-option-grid">
                            <label @class(['checkout-option', 'is-selected' => $initialShippingMethod === 'standard'])>
                                <input type="radio" name="shipping_method" value="standard" @checked(old('shipping_method', 'standard') === 'standard')>
                                <span>
                                    <strong>Standard</strong>
                                    <small>3-5 Business Days</small>
                                </span>
                                <b>PHP 100</b>
                            </label>
                            <label @class(['checkout-option', 'is-selected' => $initialShippingMethod === 'express'])>
                                <input type="radio" name="shipping_method" value="express" @checked(old('shipping_method') === 'express')>
                                <span>
                                    <strong>Express</strong>
                                    <small>Overnight Delivery</small>
                                </span>
                                <b>PHP 300</b>
                            </label>
                        </div>
                    </section>

                    <section class="checkout-section">
                        <div class="checkout-section-head">
                            <h2>03 / Payment Method</h2>
                        </div>
                        <div class="checkout-payment">
                            <label class="checkout-payment-head">
                                <input type="radio" name="payment_method" value="card" @checked(old('payment_method', 'card') === 'card')>
                                <span>Credit / Debit Card</span>
                            </label>
                            <div class="checkout-card-fields">
                                <label class="checkout-field checkout-field-wide">
                                    <span>Card Number</span>
                                    <input type="text" placeholder="0000 0000 0000 0000">
                                </label>
                                <label class="checkout-field">
                                    <span>Expiry Date</span>
                                    <input type="text" placeholder="MM/YY">
                                </label>
                                <label class="checkout-field">
                                    <span>CVV</span>
                                    <input type="text" placeholder="***">
                                </label>
                            </div>
                        </div>
                        <label class="checkout-cod">
                            <input type="radio" name="payment_method" value="cod" @checked(old('payment_method') === 'cod')>
                            <span>
                                <strong>Cash on Delivery</strong>
                                <small>Pay upon receiving your pieces / Premium Service</small>
                            </span>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.8 1-6.1-4.4-4.3 6.1-.9L12 3Z"></path>
                            </svg>
                        </label>
                    </section>
                </form>
            </div>

            <aside class="checkout-summary" aria-label="Order summary">
                <h2>Summary</h2>
                @foreach ($cartItems as $item)
                    <div class="checkout-summary-item">
                        <a href="{{ route('product.show', $item['product']->slug) }}">
                            <img src="{{ $item['product']->displayImageUrl() }}" alt="{{ $item['product']->name }}">
                        </a>
                        <div>
                            <h3>{{ $item['product']->name }}</h3>
                            <p>Size: <b>{{ $item['size'] ?: 'One Size' }}</b></p>
                            <p>Qty: <b>{{ $item['quantity'] }}</b></p>
                            <strong>PHP {{ number_format($item['line_total']) }}</strong>
                        </div>
                    </div>
                @endforeach
                <dl>
                    <div>
                        <dt>Subtotal</dt>
                        <dd id="checkout-subtotal" data-subtotal="{{ $subtotal }}">PHP {{ number_format($subtotal) }}</dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd id="checkout-shipping">PHP {{ number_format($shippingTotal) }}</dd>
                    </div>
                    <div class="checkout-total">
                        <dt>Total</dt>
                        <dd id="checkout-total">PHP {{ number_format($total) }}</dd>
                    </div>
                </dl>
                <button type="submit" form="checkout-form" class="checkout-complete">
                    Complete Purchase
                    <span aria-hidden="true">-&gt;</span>
                </button>
                <p class="checkout-secure">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                    </svg>
                    SSL Secured Encryption
                </p>
            </aside>
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
                </div>
            </div>
            <div class="footer-actions">
                <p class="footer-note">(c)2026 THREADLAB DIGITAL ATELIER. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
    <script>
        (() => {
            const shippingInputs = Array.from(document.querySelectorAll('input[name="shipping_method"]'));
            const subtotalElement = document.getElementById('checkout-subtotal');
            const shippingElement = document.getElementById('checkout-shipping');
            const totalElement = document.getElementById('checkout-total');

            if (! shippingInputs.length || ! subtotalElement || ! shippingElement || ! totalElement) {
                return;
            }

            const formatPhp = (value) => `PHP ${new Intl.NumberFormat('en-PH').format(value)}`;
            const subtotal = Number(subtotalElement.dataset.subtotal ?? 0);
            const shippingMap = {
                standard: 100,
                express: 300,
            };

            const syncShippingState = () => {
                const selectedInput = shippingInputs.find((input) => input.checked) ?? shippingInputs[0];
                const shippingMethod = selectedInput?.value ?? 'standard';
                const shippingTotal = shippingMap[shippingMethod] ?? shippingMap.standard;

                shippingInputs.forEach((input) => {
                    input.closest('.checkout-option')?.classList.toggle('is-selected', input.checked);
                });

                shippingElement.textContent = formatPhp(shippingTotal);
                totalElement.textContent = formatPhp(subtotal + shippingTotal);
            };

            shippingInputs.forEach((input) => {
                input.addEventListener('change', syncShippingState);
            });

            syncShippingState();
        })();
    </script>
</body>
</html>
