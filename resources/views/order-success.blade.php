<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Success | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <main class="success-page">
        <div class="success-grid-bg" aria-hidden="true"></div>
        <div class="success-glow success-glow-primary" aria-hidden="true"></div>
        <div class="success-glow success-glow-secondary" aria-hidden="true"></div>
        <p class="success-ghost success-ghost-right" aria-hidden="true">THREAD</p>
        <p class="success-ghost success-ghost-left" aria-hidden="true">LAB</p>

        <section class="success-panel" aria-label="Order confirmation">
            <div class="success-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="m8 12 2.6 2.6L16.5 9"></path>
                </svg>
            </div>

            <header class="success-copy">
                <h1>Your order has been placed <span>successfully</span></h1>
                <p>Confirmation Stage Complete</p>
            </header>

            <div class="success-info-grid">
                <article>
                    <span>Reference</span>
                    <strong>{{ $order?->reference ?? 'TL-PENDING' }}</strong>
                </article>
                <article>
                    <span>Total Amount</span>
                    <strong>PHP {{ number_format($order?->total ?? 0) }}</strong>
                </article>
                <article>
                    <span>Payment</span>
                    <strong>{{ $order ? $order->paymentStatusLabel() : 'Not Available' }}</strong>
                </article>
                <article>
                    <span>Status</span>
                    <strong>{{ $order ? $order->statusLabel() : 'Not Available' }}</strong>
                </article>
                <article>
                    <span>Method</span>
                    <strong>{{ $order ? \Illuminate\Support\Str::headline($order->payment_method) : 'Not Available' }}</strong>
                </article>
            </div>

            <div class="success-actions">
                <a href="{{ route('shop') }}" class="success-primary">Keep Shopping</a>
                <a href="{{ route('cart') }}" class="success-secondary">Back to Cart</a>
            </div>

            <div class="success-tech">
                <span aria-hidden="true"></span>
                <p>Kinetic Editorial Syst v2.0</p>
                <span aria-hidden="true"></span>
            </div>
        </section>
    </main>

    <footer class="success-footer">
        <a href="{{ route('home') }}" class="success-footer-brand">THREADLAB</a>
        <nav aria-label="Footer">
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
            <a href="#">Shipping</a>
            <a href="#">Returns</a>
            <a href="#">Contact</a>
        </nav>
        <p>(c)2026 THREADLAB KINETIC EDITORIAL</p>
    </footer>
</body>
</html>
