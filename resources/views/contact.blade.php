<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact | THREADLAB</title>
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
                <a href="{{ route('contact') }}" class="is-active">Contact</a>
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

    <main class="contact-page">
        <section class="content-wrap contact-hero">
            <div>
                <p class="section-label">Connect</p>
                <h1>Contact <span>The Studio</span></h1>
            </div>
        </section>

        <section class="content-wrap contact-layout">
            <div class="contact-meta">
                <article class="contact-info-block">
                    <p>Direct Channel</p>
                    <h2>studio@threadlab.com</h2>
                    <span>General inquiries and wholesale partnerships.</span>
                </article>

                <article class="contact-info-block">
                    <p>Social Grid</p>
                    <div class="contact-links">
                        <a href="#">Instagram <span aria-hidden="true">&#8599;</span></a>
                        <a href="#">X <span aria-hidden="true">&#8599;</span></a>
                        <a href="#">Discord <span aria-hidden="true">&#8599;</span></a>
                    </div>
                </article>

                <article class="contact-info-block">
                    <p>Physical Node</p>
                    <div class="contact-location">
                        <div class="contact-location-media">
                            <img src="https://images.unsplash.com/photo-1511818966892-d7d671e672a2?auto=format&fit=crop&w=900&q=80" alt="THREADLAB studio location">
                        </div>
                        <div>
                            <strong>Manila, PH</strong>
                            <span>Central Business District,<br>Taguig City 1634</span>
                        </div>
                    </div>
                </article>

            </div>

            <div class="contact-form-panel">
                <form class="contact-form" action="#" method="GET">
                    <div class="contact-form-grid">
                        <label>
                            <span>Full Name</span>
                            <input type="text" placeholder="ALEX MERCER">
                        </label>
                        <label>
                            <span>Email Address</span>
                            <input type="email" placeholder="ALEX@THREADLAB.COM">
                        </label>
                    </div>
                    <label class="contact-form-full">
                        <span>Message</span>
                        <textarea rows="7" placeholder="TELL US ABOUT THE PROJECT..."></textarea>
                    </label>
                    <button type="button" class="contact-submit">
                        Send Message
                        <span aria-hidden="true">&#8594;</span>
                    </button>
                </form>
            </div>
        </section>

        <section class="content-wrap contact-faq">
            <div class="contact-faq-head">
                <p class="section-label">FAQ</p>
                <h2>Frequently <span>Queried</span></h2>
                <p>Immediate answers for the rapid-response collector.</p>
            </div>
            <div class="contact-faq-list">
                <details class="contact-faq-item" open>
                    <summary>Shipping Times</summary>
                    <p>Domestic orders ship within 48 hours. International delivery ranges from 5 to 9 business days depending on customs processing in your region.</p>
                </details>
                <details class="contact-faq-item">
                    <summary>Drop Notifications</summary>
                    <p>Follow the brand channels and mailing list for early alerts, private access links, and new release timing.</p>
                </details>
                <details class="contact-faq-item">
                    <summary>Return Policy</summary>
                    <p>Returns are accepted within 7 days of delivery for store credit, provided the product remains in original condition.</p>
                </details>
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
                    <a href="{{ route('contact') }}">Contact</a>
                </div>
            </div>
            <div class="footer-actions">
                <p class="footer-note">(c)2026 THREADLAB. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
</body>
</html>
