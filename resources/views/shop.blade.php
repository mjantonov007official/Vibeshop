<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop Collection | THREADLAB</title>
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

    <main class="shop-page">
        <section class="shop-hero">
            <div class="content-wrap">
                <div class="shop-hero-title">
                    <h1>Shop <span>Collection</span></h1>
                </div>
                <p class="shop-hero-copy">Explore our curated line of premium t-shirts. Engineered for the high-velocity urban lifestyle.</p>
            </div>
        </section>

        <section class="shop-toolbar">
            <div class="content-wrap shop-toolbar-inner">
                @include('partials.shop-toolbar')
            </div>
        </section>

        <section class="shop-products">
            <div class="content-wrap shop-grid">
                @include('partials.shop-grid')
            </div>
        </section>

        <section class="shop-benefits">
            <div class="content-wrap shop-benefit-grid">
                <article>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 8h12"></path>
                            <path d="M3 12h10"></path>
                            <path d="M3 16h8"></path>
                            <path d="M14 8h4l3 4v4h-7z"></path>
                            <circle cx="8" cy="18" r="2"></circle>
                            <circle cx="18" cy="18" r="2"></circle>
                        </svg>
                    </span>
                    <div>
                        <h3>Free Shipping</h3>
                        <p>Nationwide Delivery</p>
                    </div>
                </article>
                <article>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3l7 3v5c0 5-3.2 8.4-7 10-3.8-1.6-7-5-7-10V6l7-3Z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    </span>
                    <div>
                        <h3>Premium Quality</h3>
                        <p>Ethically Sourced Fabric</p>
                    </div>
                </article>
                <article>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 5H5v4"></path>
                            <path d="M5 9a7 7 0 1 1-1 3.6"></path>
                            <path d="M15 19h4v-4"></path>
                        </svg>
                    </span>
                    <div>
                        <h3>Easy Returns</h3>
                        <p>30-Day Policy</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="shop-newsletter">
            <div class="content-wrap shop-newsletter-inner">
                <h2>Stay Updated with <span>THREADLAB</span></h2>
                <p>Get exclusive drops and offers straight to your inbox.</p>
                @if (session('newsletter_success'))
                    <p class="form-success">{{ session('newsletter_success') }}</p>
                @endif
                <form method="POST" action="{{ route('newsletter') }}">
                    @csrf
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
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
                    <a href="#">Contact</a>
                </div>
            </div>
            <div class="footer-actions">
                <p class="footer-note">(c)2026 THREADLAB KINETIC EDITORIAL. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const shopPage = document.querySelector('.shop-page');

            if (!shopPage) {
                return;
            }

            let activeRequestId = 0;

            const buildUrlFromForm = (form) => {
                const url = new URL(form.action, window.location.origin);
                const params = new URLSearchParams(new FormData(form));

                url.search = params.toString();

                return url.toString();
            };

            const replaceShopContent = (payload, url, shouldPushState = true) => {
                const toolbarInner = document.querySelector('.shop-toolbar-inner');
                const shopGrid = document.querySelector('.shop-grid');

                if (toolbarInner && payload.toolbarHtml) {
                    toolbarInner.innerHTML = payload.toolbarHtml;
                }

                if (shopGrid && payload.gridHtml) {
                    shopGrid.innerHTML = payload.gridHtml;
                }

                if (shouldPushState) {
                    window.history.pushState({}, '', url);
                }
            };

            const fetchShopContent = async (url, shouldPushState = true) => {
                const requestId = ++activeRequestId;

                shopPage.classList.add('is-loading');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Unable to refresh the shop view.');
                    }

                    const payload = await response.json();

                    if (requestId !== activeRequestId) {
                        return;
                    }

                    replaceShopContent(payload, url, shouldPushState);
                } catch (error) {
                    window.location.assign(url);
                } finally {
                    if (requestId === activeRequestId) {
                        shopPage.classList.remove('is-loading');
                    }
                }
            };

            document.addEventListener('click', (event) => {
                const filterLink = event.target.closest('.shop-filter');

                if (!filterLink || !shopPage.contains(filterLink)) {
                    return;
                }

                event.preventDefault();
                fetchShopContent(filterLink.href);
            });

            document.addEventListener('change', (event) => {
                const sortSelect = event.target.closest('.shop-sort select');

                if (!sortSelect || !shopPage.contains(sortSelect)) {
                    return;
                }

                fetchShopContent(buildUrlFromForm(sortSelect.form));
            });

            document.addEventListener('submit', (event) => {
                const form = event.target.closest('.shop-search');

                if (!form || !shopPage.contains(form)) {
                    return;
                }

                event.preventDefault();
                fetchShopContent(buildUrlFromForm(form));
            });

            window.addEventListener('popstate', () => {
                fetchShopContent(window.location.href, false);
            });
        })();
    </script>
</body>
</html>
