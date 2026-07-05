<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Dashboard | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="customer-dashboard-body">
    <header class="customer-dashboard-topbar">
        <a href="{{ route('customer.dashboard') }}" class="customer-dashboard-logo">THREADLAB</a>
        <nav aria-label="Customer navigation">
            <a href="{{ route('customer.dashboard') }}" class="is-active">Dashboard</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('home') }}#collections">Collections</a>
            <a href="{{ route('home') }}#faq">Archive</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>
        <div class="customer-dashboard-actions">
            @include('partials.mini-cart')
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>

    <aside class="customer-dashboard-sidebar">
        <div class="customer-tier-block">
            <span>Vip Status</span>
            <strong>{{ $totalSpent >= 5000 ? 'Elite Tier Member' : 'Member' }}</strong>
        </div>
        <nav aria-label="Customer dashboard sections">
            <a href="#overview" class="is-active">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
            <a href="#orders">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7l8-4 8 4-8 4-8-4Z"></path><path d="M4 7v10l8 4 8-4V7"></path><path d="M12 11v10"></path></svg>
                Order History
            </a>
            <a href="#rewards">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.8 1-6.1-4.4-4.3 6.1-.9L12 3Z"></path></svg>
                Rewards
            </a>
            <a href="#account">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"></circle><path d="M6.5 19a5.5 5.5 0 0 1 11 0"></path></svg>
                Account
            </a>
        </nav>
        <a href="{{ route('shop') }}" class="customer-upgrade">Shop New Drop</a>
    </aside>

    <main class="customer-dashboard-main">
        <section id="overview" class="customer-dashboard-hero">
            <div>
                <p>Customer Portal</p>
                <h1>Welcome Back, <span>{{ \Illuminate\Support\Str::of($user->name)->before(' ') }}</span></h1>
                <small>Your orders, rewards, and curated THREADLAB edit are ready.</small>
            </div>
            <aside>
                <span>Member Since</span>
                <strong>{{ $user->created_at->format('M Y') }}</strong>
            </aside>
        </section>

        <section class="customer-dashboard-grid">
            <article id="rewards" class="customer-tier-card">
                <div>
                    <span>{{ $totalSpent >= 5000 ? 'Elite Status' : 'Member Status' }}</span>
                    <h2>{{ $totalSpent >= 5000 ? 'Neo-Street Vanguard' : 'Kinetic Starter' }}</h2>
                    <p>{{ $totalSpent >= 5000 ? 'You unlocked priority access for select drops.' : 'Complete more orders to unlock elite tier access and exclusive drops.' }}</p>
                </div>
                @php
                    $progress = min(100, (int) (($totalSpent / 5000) * 100));
                @endphp
                <div>
                    <div class="customer-progress-head">
                        <strong>{{ $progress }}% Complete</strong>
                        <span>PHP {{ number_format($totalSpent) }} / PHP 5,000</span>
                    </div>
                    <i style="--value: {{ $progress }}%"></i>
                </div>
            </article>

            <article class="customer-stat-card">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.8 1-6.1-4.4-4.3 6.1-.9L12 3Z"></path></svg>
                <h2>Rewards Balance</h2>
                <strong>{{ number_format($rewardPoints) }}</strong>
                <p>Redeemable kinetic points</p>
            </article>

            <article class="customer-stat-card">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7l8-4 8 4-8 4-8-4Z"></path><path d="M4 7v10l8 4 8-4V7"></path></svg>
                <h2>Total Orders</h2>
                <strong>{{ number_format($totalOrders) }}</strong>
                <p>{{ $latestOrder ? 'Latest: '.$latestOrder->reference : 'No orders yet' }}</p>
            </article>
        </section>

        <section id="orders" class="customer-orders-section">
            <div class="customer-section-head">
                <div>
                    <h2>Recent Acquisitions</h2>
                    <p>Your latest THREADLAB order activity.</p>
                </div>
                <a href="{{ route('shop') }}">Continue Shopping</a>
            </div>

            <div class="customer-order-grid">
                @forelse ($orders as $order)
                    @php
                        $firstItem = $order->items->first();
                        $image = $firstItem?->product?->displayImageUrl();
                    @endphp
                    <article class="customer-order-card">
                        <div class="customer-order-media">
                            @if ($image)
                                <img src="{{ $image }}" alt="{{ $firstItem->product_name }}">
                            @endif
                        </div>
                        <div>
                            <div class="customer-order-card-head">
                                <h3>{{ $firstItem?->product_name ?? 'THREADLAB Order' }}</h3>
                                <span class="{{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                            </div>
                            <p>Order #{{ $order->reference }}</p>
                            <p>Payment: <b class="{{ $order->paymentStatusClass() }}">{{ $order->paymentStatusLabel() }}</b></p>
                            <p>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('item', $order->items->count()) }} / Ordered {{ $order->created_at->format('M d, Y g:i A') }}</p>
                            <p>Status updated {{ ($order->status_updated_at ?? $order->updated_at)->format('M d, Y g:i A') }}</p>
                            <div>
                                <strong>PHP {{ number_format($order->total) }}</strong>
                                <a href="{{ route('order.success', $order) }}">View Order</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <h2>No orders yet</h2>
                        <p>Your completed checkout orders will appear here automatically.</p>
                        <a href="{{ route('shop') }}">Start Shopping</a>
                    </div>
                @endforelse
            </div>
        </section>

        <section id="account" class="customer-account-section">
            <article>
                <h2>Account Details</h2>
                <dl>
                    <div>
                        <dt>Name</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt>Total Spent</dt>
                        <dd>PHP {{ number_format($totalSpent) }}</dd>
                    </div>
                </dl>
            </article>
            <article>
                <h2>Personal Style Edit</h2>
                <div class="customer-recommendations">
                    @foreach ($recommendedProducts as $product)
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->displayImageUrl() }}" alt="{{ $product->name }}">
                            <span>{{ $product->name }}</span>
                        </a>
                    @endforeach
                </div>
            </article>
        </section>
    </main>
</body>
</html>
