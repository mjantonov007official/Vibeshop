<aside class="cart-summary" aria-label="Order summary">
    <h2>Summary</h2>
    <dl>
        <div>
            <dt>Subtotal</dt>
            <dd>PHP {{ number_format($subtotal) }}</dd>
        </div>
        <div>
            <dt>Shipping</dt>
            <dd>PHP {{ number_format($shippingTotal) }}</dd>
        </div>
        <div class="cart-total">
            <dt>Total</dt>
            <dd>PHP {{ number_format($total) }}</dd>
        </div>
    </dl>
    @if ($cartItems->isEmpty())
        <a href="{{ route('shop') }}">Continue Shopping</a>
    @elseif (auth()->check())
        <a href="{{ route('checkout') }}">Proceed to Checkout</a>
    @else
        <button type="button" class="cart-checkout-trigger" data-open-checkout-prompt>Proceed to Checkout</button>
    @endif
    <p>Tax included. Shipping calculated at checkout.</p>
    <div class="cart-guarantee">
        <span aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <path d="M12 3l7 3v5c0 5-3.2 8.4-7 10-3.8-1.6-7-5-7-10V6l7-3Z"></path>
                <path d="m9 12 2 2 4-4"></path>
            </svg>
        </span>
        <p>Exclusive Atelier Guarantee. Limited production runs ensuring rarity.</p>
    </div>
</aside>
