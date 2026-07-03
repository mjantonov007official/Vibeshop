@forelse ($cartItems as $item)
    <article class="cart-item">
        <a href="{{ route('product.show', $item['product']->slug) }}" class="cart-item-media">
            <img src="{{ $item['product']->displayImageUrl() }}" alt="{{ $item['product']->name }}">
        </a>
        <div class="cart-item-body">
            <div class="cart-item-top">
                <div>
                    <h2>{{ $item['product']->name }}</h2>
                    <p>{{ $item['size'] ? 'Size: '.$item['size'] : 'One Size' }}</p>
                </div>
                <strong>PHP {{ number_format($item['line_total']) }}</strong>
            </div>
            <div class="cart-item-bottom">
                <div class="cart-quantity" aria-label="Quantity selector">
                    <form method="POST" action="{{ route('cart.update', $item['key']) }}" data-cart-ajax="true">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                        <button type="submit" aria-label="Decrease quantity">-</button>
                    </form>
                    <span>{{ $item['quantity'] }}</span>
                    <form method="POST" action="{{ route('cart.update', $item['key']) }}" data-cart-ajax="true">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                        <button type="submit" aria-label="Increase quantity">+</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('cart.remove', $item['key']) }}" data-cart-ajax="true">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cart-remove">Remove Item</button>
                </form>
            </div>
        </div>
    </article>
@empty
    <div class="empty-state">
        <h2>Your cart is empty</h2>
        <p>Add a product from the shop and it will show here with real quantity controls.</p>
        <a href="{{ route('shop') }}">Continue Shopping</a>
    </div>
@endforelse
