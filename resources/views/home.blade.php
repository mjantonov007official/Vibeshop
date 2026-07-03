<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>THREADLAB | Modern Streetwear Essentials</title>
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
                <a href="{{ route('home') }}" class="is-active">Home</a>
                <a href="{{ route('shop') }}">Shop</a>
                <a href="#collections">Collections</a>
                <a href="#faq">Archive</a>
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

    @php
        $featuredProducts = $products->take(4);
        $collectionHighlights = [
            [
                'label' => 'Basic Line',
                'title' => 'Daily Uniform',
                'copy' => 'Clean silhouettes, premium cotton, and easy layering built for every day wear.',
                'category' => 'Basic',
            ],
            [
                'label' => 'Oversized Edit',
                'title' => 'Relaxed Volume',
                'copy' => 'Dropped shoulders and wider cuts with a sharper streetwear attitude.',
                'category' => 'Oversized',
            ],
            [
                'label' => 'Accessories',
                'title' => 'Finish The Fit',
                'copy' => 'Caps, totes, and utility pieces that pull the whole look together.',
                'category' => 'Accessories',
            ],
        ];
    @endphp

    <main class="home-page">
        <section class="hero home-hero" id="editorial">
            <div class="hero-media">
                <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1600&q=80" alt="THREADLAB fashion editorial collection">
            </div>
            <div class="hero-overlay"></div>
            <div class="content-wrap hero-content home-hero-content">
                <p class="kicker">Streetwear Essentials</p>
                <h1>Built For <br>Everyday <br><span>Movement.</span></h1>
                <p class="home-hero-copy">Premium basics, oversized staples, and modern layers designed for the city. Clean cuts, strong fabrics, and easy styling from day to night.</p>
                <div class="hero-actions">
                    <a href="{{ route('shop') }}" class="button button-primary">
                        Shop Now
                        <span aria-hidden="true">&#8594;</span>
                    </a>
                    <a href="#featured-products" class="button button-secondary">Featured Drops</a>
                </div>
            </div>
        </section>

        <section class="section home-featured" id="featured-products">
            <div class="content-wrap">
                <div class="section-heading home-section-heading">
                    <div>
                        <p class="section-label">Featured Products</p>
                        <h2>Core Pieces For <span>The Rotation</span></h2>
                    </div>
                    <p>We are using dummy products for now, but the layout is ready for your real catalog anytime.</p>
                </div>

                <div class="home-product-grid">
                    @foreach ($featuredProducts as $product)
                        <article class="home-product-card">
                            <a href="{{ route('product.show', $product->slug) }}" class="home-product-media">
                                <img src="{{ $product->displayImageUrl() }}" alt="{{ $product->name }}">
                                <span class="home-product-pill">{{ $product->category }}</span>
                            </a>
                            <div class="home-product-body">
                                <div class="home-product-top">
                                    <div>
                                        <h3>{{ $product->name }}</h3>
                                        <p>{{ $product->description }}</p>
                                    </div>
                                    <strong>{{ $product->formattedPrice() }}</strong>
                                </div>
                                <div class="home-product-actions">
                                    <a href="{{ route('product.show', $product->slug) }}" class="button button-primary">View Product</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section home-why" id="why-threadlab">
            <div class="content-wrap">
                <div class="section-heading home-section-heading">
                    <div>
                        <p class="section-label">Why Choose Us</p>
                        <h2>Style, Comfort, And <span>Consistency</span></h2>
                    </div>
                    <p>Everything stays aligned with the THREADLAB theme: clean, modern, sharp, and easy to wear.</p>
                </div>

                <div class="home-why-grid">
                    <article class="home-info-card">
                        <span>01</span>
                        <h3>Premium Fabric Feel</h3>
                        <p>Soft but structured materials that keep their shape and wear comfortably all day.</p>
                    </article>
                    <article class="home-info-card">
                        <span>02</span>
                        <h3>Easy To Style</h3>
                        <p>Minimal color stories and strong silhouettes make every piece simple to pair.</p>
                    </article>
                    <article class="home-info-card">
                        <span>03</span>
                        <h3>Made For Daily Use</h3>
                        <p>From essentials to statement oversized fits, each drop is built for repeat wear.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section home-collections" id="collections">
            <div class="content-wrap">
                <div class="section-heading home-section-heading">
                    <div>
                        <p class="section-label">Collections</p>
                        <h2>Pick The Mood. <span>Wear The Line.</span></h2>
                    </div>
                    <p>Browse the different directions of the brand, from clean daily basics to louder oversized fits.</p>
                </div>

                <div class="home-collection-grid">
                    @foreach ($collectionHighlights as $highlight)
                        <article class="home-collection-card">
                            <p class="home-collection-label">{{ $highlight['label'] }}</p>
                            <h3>{{ $highlight['title'] }}</h3>
                            <p>{{ $highlight['copy'] }}</p>
                            <a href="{{ route('shop', ['category' => $highlight['category']]) }}">
                                Explore Collection
                                <span aria-hidden="true">&#8594;</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section home-faq" id="faq">
            <div class="content-wrap home-faq-wrap">
                <div class="home-faq-head">
                    <p class="section-label">FAQ</p>
                    <h2>Quick Answers <span>Before You Shop</span></h2>
                    <p>Helpful storefront questions for now, ready to be replaced later with your real policies.</p>
                </div>

                <div class="home-faq-list">
                    <details class="home-faq-item" open>
                        <summary>Are these products real or dummy products for now?</summary>
                        <p>For the current stage, the homepage is using dummy product content so we can focus on UI and shopping flow first.</p>
                    </details>
                    <details class="home-faq-item">
                        <summary>Can I already use this layout for real clothing products later?</summary>
                        <p>Yes. The sections are already connected to the product pages and shop flow, so we can swap in real product data anytime.</p>
                    </details>
                    <details class="home-faq-item">
                        <summary>Will this work well for basics, oversized fits, and accessories?</summary>
                        <p>Yes. The homepage structure was shaped around apparel browsing, featured drops, collections, and product discovery.</p>
                    </details>
                    <details class="home-faq-item">
                        <summary>Can we add more homepage modules later?</summary>
                        <p>Definitely. We can add testimonials, Instagram-style galleries, new arrivals, best sellers, or promotional bands next.</p>
                    </details>
                </div>
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
                <div class="footer-icons">
                    <button type="button" aria-label="Language">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M3 12h18"></path>
                            <path d="M12 3c2.2 2.5 3.2 5.5 3.2 9s-1 6.5-3.2 9"></path>
                            <path d="M12 3C9.8 5.5 8.8 8.5 8.8 12s1 6.5 3.2 9"></path>
                        </svg>
                    </button>
                    <button type="button" aria-label="Share">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 12v7h16v-7"></path>
                            <path d="M12 15V4"></path>
                            <path d="m8 8 4-4 4 4"></path>
                        </svg>
                    </button>
                </div>
                <p class="footer-note">(c)2026 THREADLAB. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
</body>
</html>
