<div class="shop-toolbar-actions">
    <form class="shop-sort" method="GET" action="{{ route('shop') }}">
        <input type="hidden" name="category" value="{{ request('category', 'All') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <span>Sort By:</span>
        <select name="sort">
            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest First</option>
            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
        </select>
    </form>
    <form class="shop-search" method="GET" action="{{ route('shop') }}">
        <input type="hidden" name="category" value="{{ request('category', 'All') }}">
        <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products">
        <button type="submit">Search</button>
    </form>
</div>
<div class="shop-filters" aria-label="Product categories">
    @foreach ($categories as $category)
        <a href="{{ route('shop', ['category' => $category, 'sort' => request('sort'), 'search' => request('search')]) }}" class="shop-filter {{ request('category', 'All') === $category ? 'is-selected' : '' }}">{{ $category }}</a>
    @endforeach
</div>
