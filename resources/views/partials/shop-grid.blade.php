@forelse ($products as $product)
    <article class="shop-card">
        <div class="shop-card-media">
            @if ($product->category === 'Oversized')
                <span class="shop-badge">Limited Drop</span>
            @endif
            <img src="{{ $product->displayImageUrl() }}" alt="{{ $product->name }}">
            <div class="shop-card-action">
                <a href="{{ route('product.show', $product->slug) }}">View Product</a>
            </div>
        </div>
        <div class="shop-card-body">
            <div class="shop-card-meta">
                <h2>{{ $product->name }}</h2>
                <strong>{{ $product->formattedPrice() }}</strong>
            </div>
            <p>SKU: {{ $product->sku }}</p>
        </div>
    </article>
@empty
    <p class="empty-state">No products found.</p>
@endforelse
