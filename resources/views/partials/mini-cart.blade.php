@php
    $cartService = app(\App\Services\CartService::class);
    $miniCartItems = $cartService->items();
    $miniCartCount = $cartService->count();
@endphp

<div class="header-cart">
    <a href="{{ route('cart') }}" aria-label="Bag" class="{{ request()->routeIs('cart', 'checkout') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M7 9h10l-1 10H8L7 9Z"></path>
            <path d="M9 9a3 3 0 0 1 6 0"></path>
        </svg>
        @if ($miniCartCount > 0)
            <span class="cart-count">{{ $miniCartCount }}</span>
        @endif
    </a>
    <div class="mini-cart" role="dialog" aria-label="Cart preview">
        <div class="mini-cart-head">
            <strong>Cart</strong>
            <span>{{ $miniCartCount }} {{ \Illuminate\Support\Str::plural('item', $miniCartCount) }}</span>
        </div>
        @if ($miniCartItems->isNotEmpty())
            <div class="mini-cart-list">
                @foreach ($miniCartItems as $item)
                    <div class="mini-cart-item">
                        <img src="{{ $item['product']->displayImageUrl() }}" alt="{{ $item['product']->name }}">
                        <div class="mini-cart-item-copy">
                            <div class="mini-cart-item-top">
                                <h3>{{ $item['product']->name }}</h3>
                                <form method="POST" action="{{ route('cart.remove', $item['key']) }}" class="mini-cart-remove-form" data-cart-ajax="true">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mini-cart-remove" aria-label="Remove {{ $item['product']->name }} from cart">&times;</button>
                                </form>
                            </div>
                            <p>{{ $item['size'] ? 'Size '.$item['size'].' / ' : '' }}Qty {{ $item['quantity'] }}</p>
                            <strong>PHP {{ number_format($item['line_total']) }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mini-cart-empty">Your cart is empty.</p>
        @endif
        <div class="mini-cart-actions">
            <a href="{{ route('cart') }}">View Cart</a>
        </div>
    </div>
</div>
