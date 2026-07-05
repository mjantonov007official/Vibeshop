<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} | THREADLAB</title>
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

    <main class="product-page">
        <section class="content-wrap product-detail">
            <div class="product-gallery">
                <div class="product-gallery-main">
                    <img src="{{ $product->displayImageUrl() }}" alt="{{ $product->name }}" id="product-main-image">
                    <span>{{ $product->sku }}</span>
                </div>
                <div class="product-thumbs" aria-label="Product preview images">
                    @foreach (collect($product->gallery)->prepend($product->image_url)->filter()->unique()->take(3) as $image)
                        <button
                            type="button"
                            data-product-thumb
                            data-image-src="{{ $product->displayImageUrl($image) }}"
                            data-image-alt="{{ $product->name }}"
                            @class(['is-selected' => $loop->first])
                        >
                            <img src="{{ $product->displayImageUrl($image) }}" alt="{{ $product->name }} preview {{ $loop->iteration }}">
                        </button>
                    @endforeach
                </div>
            </div>

            <article class="product-info">
                <div class="product-kicker">
                    <span>{{ $product->category }}</span>
                    <i aria-hidden="true"></i>
                </div>
                <h1>{{ $product->name }}</h1>
                <div class="product-price">
                    <strong>PHP {{ number_format($product->price) }}</strong>
                    @if ($product->compare_at_price)
                        <span>PHP {{ number_format($product->compare_at_price) }}</span>
                    @endif
                </div>
                <p class="product-description">{{ $product->description }}</p>

                @if (session('success'))
                    <p class="form-success">{{ session('success') }}</p>
                @endif

                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <form method="POST" action="{{ route('cart.add') }}" class="product-order-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">

                    <div class="product-size-block">
                        <div class="product-size-head">
                            <span>Select Size</span>
                            <button type="button">Size Guide</button>
                        </div>
                        <div class="product-size-grid">
                            @foreach (($product->sizes ?: ['One Size']) as $size)
                                <label class="size-choice" for="size-{{ $loop->index }}">
                                    <input id="size-{{ $loop->index }}" type="radio" name="size" value="{{ $size }}" @checked($loop->first) required>
                                    <span>{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-actions">
                        <button type="submit" class="product-add">Add To Cart</button>
                        @auth
                            <button type="submit" class="product-buy" name="buy_now" value="1">Buy Now</button>
                        @else
                            <button type="button" class="product-buy" data-open-buy-now-prompt>Buy Now</button>
                        @endauth
                    </div>
                </form>

                <div class="product-copy-tabs">
                    <div class="product-tabs" aria-label="Product information">
                        <button type="button" class="is-selected">Description</button>
                        <button type="button">Materials</button>
                        <button type="button">Sizing Guide</button>
                    </div>
                    <div class="product-copy">
                        <p>{{ $product->description }}</p>
                        <ul>
                            <li>Available stock: {{ $product->stock }}</li>
                            <li>Category: {{ $product->category }}</li>
                        </ul>
                    </div>
                </div>
            </article>
        </section>

        <section class="content-wrap product-related">
            <div class="product-related-head">
                <div>
                    <h2>Complement the Look</h2>
                    <p>Curated pieces to complete your digital uniform.</p>
                </div>
                <a href="{{ route('shop') }}">View Full Collection</a>
            </div>
            <div class="related-grid">
                @foreach ($relatedProducts as $relatedProduct)
                    <article class="related-card">
                        <div class="related-media">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}">
                            <img src="{{ $relatedProduct->displayImageUrl() }}" alt="{{ $relatedProduct->name }}">
                            </a>
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                <input type="hidden" name="size" value="{{ ($relatedProduct->sizes ?: [null])[0] }}">
                                <button type="submit" aria-label="Add {{ $relatedProduct->name }}">+</button>
                            </form>
                        </div>
                        <h3>{{ $relatedProduct->name }}</h3>
                        <p>PHP {{ number_format($relatedProduct->price) }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        @guest
            <div class="checkout-prompt" id="buy-now-prompt" hidden>
                <div class="checkout-prompt-backdrop" data-close-buy-now-prompt></div>
                <div class="checkout-prompt-dialog" role="dialog" aria-modal="true" aria-labelledby="buy-now-prompt-title">
                    <button type="button" class="checkout-prompt-close" aria-label="Close prompt" data-close-buy-now-prompt>&times;</button>
                    <p>Secure Checkout</p>
                    <h2 id="buy-now-prompt-title">Please login/register to continue checkout.</h2>
                    <small>We will keep your selected size and send you straight to checkout after login or registration.</small>
                    <div class="checkout-prompt-actions">
                        <form method="GET" action="{{ route('customer.login') }}" class="checkout-prompt-form">
                            <input type="hidden" name="redirect_to" value="/checkout">
                            <input type="hidden" name="buy_now_product_id" value="{{ $product->id }}">
                            <input type="hidden" name="buy_now_size" value="">
                            <input type="hidden" name="buy_now_quantity" value="1">
                            <button type="submit">Login</button>
                        </form>
                        <form method="GET" action="{{ route('customer.register') }}" class="checkout-prompt-form">
                            <input type="hidden" name="redirect_to" value="/checkout">
                            <input type="hidden" name="buy_now_product_id" value="{{ $product->id }}">
                            <input type="hidden" name="buy_now_size" value="">
                            <input type="hidden" name="buy_now_quantity" value="1">
                            <button type="submit">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        @endguest
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
                <p class="footer-note">(c)2026 THREADLAB KINETIC EDITORIAL. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
    @guest
        <script>
            (() => {
                const prompt = document.getElementById('buy-now-prompt');
                const openButton = document.querySelector('[data-open-buy-now-prompt]');
                const productForm = document.querySelector('.product-order-form');

                if (! prompt || ! openButton || ! productForm) {
                    return;
                }

                const syncSize = () => {
                    const selectedSize = productForm.querySelector('input[name="size"]:checked')?.value ?? '';

                    prompt.querySelectorAll('input[name="buy_now_size"]').forEach((input) => {
                        input.value = selectedSize;
                    });
                };

                const closePrompt = () => {
                    prompt.hidden = true;
                    document.body.classList.remove('has-dialog-open');
                };

                const openPrompt = () => {
                    syncSize();
                    prompt.hidden = false;
                    document.body.classList.add('has-dialog-open');
                };

                openButton.addEventListener('click', openPrompt);
                productForm.addEventListener('change', syncSize);

                prompt.querySelectorAll('[data-close-buy-now-prompt]').forEach((element) => {
                    element.addEventListener('click', closePrompt);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && ! prompt.hidden) {
                        closePrompt();
                    }
                });

                syncSize();
            })();
        </script>
    @endguest
    <script>
        (() => {
            const mainImage = document.getElementById('product-main-image');
            const thumbButtons = Array.from(document.querySelectorAll('[data-product-thumb]'));

            if (! mainImage || ! thumbButtons.length) {
                return;
            }

            const selectThumb = (button) => {
                thumbButtons.forEach((thumbButton) => {
                    thumbButton.classList.toggle('is-selected', thumbButton === button);
                });

                mainImage.src = button.dataset.imageSrc ?? mainImage.src;
                mainImage.alt = button.dataset.imageAlt ?? mainImage.alt;
            };

            thumbButtons.forEach((button) => {
                button.addEventListener('click', () => selectThumb(button));
            });

            const defaultThumb = thumbButtons.find((button) => button.classList.contains('is-selected')) ?? thumbButtons[0];

            selectThumb(defaultThumb);
        })();
    </script>
    <script>
        document.querySelectorAll('.product-size-grid').forEach((grid) => {
            const choices = Array.from(grid.querySelectorAll('.size-choice'));

            const syncState = () => {
                choices.forEach((choice) => {
                    const input = choice.querySelector('input');
                    choice.classList.toggle('is-selected', Boolean(input && input.checked));
                });
            };

            grid.addEventListener('change', syncState);
            syncState();
        });
    </script>
</body>
</html>
