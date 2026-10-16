
@extends('user.layouts.userlayout')

@section('content')

<main class="all-products-page">

    <section class="ap-hero">
        <div class="ap-hero-decoration ap-hero-decoration-one"></div>
        <div class="ap-hero-decoration ap-hero-decoration-two"></div>

        <div class="ap-container ap-hero-inner">
            <span class="ap-eyebrow">
                <span>✦</span> SWEET CRUMBS BAKERY
            </span>

            <h1>
                Freshly Baked,<br>
                <span>Lovingly Made.</span>
            </h1>

            <p>
                Explore our delicious collection of cakes, pastries and bakery
                favourites, made to bring a little sweetness to your day.
            </p>

            <div class="ap-breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span>/</span>
                <span>All Products</span>
            </div>
        </div>
    </section>

    <section class="ap-section">
        <div class="ap-container">

            {{-- CART NOTIFICATIONS --}}
            @if(session('cart_success'))
                <div class="cart-alert cart-alert-success">
                    <span>✓</span>
                    <span>{{ session('cart_success') }}</span>
                    <a href="{{ url('/cart') }}">View Cart →</a>
                </div>
            @endif

            @if(session('cart_error'))
                <div class="cart-alert cart-alert-error">
                    <span>!</span>
                    <span>{{ session('cart_error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="cart-alert cart-alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="ap-heading-row">
                <div>
                    <span class="ap-section-label">MADE FRESH WITH LOVE</span>
                    <h2>Our <span>Bakery Favourites</span></h2>
                    <p class="ap-heading-description">
                        Discover something delicious for every occasion.
                    </p>
                </div>

                <div class="ap-product-total">
                    <strong>{{ $products->total() }}</strong>
                    <span>{{ $products->total() === 1 ? 'Product' : 'Products' }}</span>
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <div class="ap-toolbar">
                <form action="{{ url('/all-products') }}"
                      method="GET"
                      class="ap-filter-form">

                    <div class="ap-search-box">
                        <span class="ap-search-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="10.8" cy="10.8" r="6.8"></circle>
                                <path d="m16 16 4.5 4.5"></path>
                            </svg>
                        </span>

                        <input
                            type="search"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Search cakes, pastries..."
                            aria-label="Search products">
                    </div>

                    <select name="category"
                            class="ap-category-select"
                            aria-label="Filter by category">

                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ (string)($categoryId ?? request('category')) === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="ap-filter-button">
                        Search Products
                    </button>

                    @if(!empty($search) || !empty($categoryId))
                        <a href="{{ url('/all-products') }}"
                           class="ap-reset-button">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="ap-results-info">
                    @if(!empty($search))
                        Search: <strong>{{ $search }}</strong>
                    @elseif(!empty($categoryId))
                        Showing selected category
                    @else
                        Explore our complete collection
                    @endif
                </div>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="ap-grid">

                @forelse($products as $product)

                    <article class="ap-card">

                        <a href="{{ url('/all-products') . '?search=' . urlencode($product->name) }}"
                           class="ap-card-image-link"
                           aria-label="View {{ $product->name }}">

                            <div class="ap-card-image">

                                @if($product->image)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <div class="ap-image-placeholder" style="display:none;">
                                        <span>✧</span>
                                    </div>
                                @else
                                    <div class="ap-image-placeholder">
                                        <span>✧</span>
                                    </div>
                                @endif

                                <div class="ap-image-overlay"></div>

                                @if($product->is_featured)
                                    <span class="ap-featured-badge">
                                        ✦ Featured
                                    </span>
                                @endif

                                <span class="ap-image-arrow" aria-hidden="true">↗</span>
                            </div>
                        </a>

                        <div class="ap-card-content">

                            <span class="ap-product-category">
                                {{ $product->category->name ?? 'Bakery Favourite' }}
                            </span>

                            <h3>{{ $product->name }}</h3>

                            <p class="ap-product-description">
                                {{ \Illuminate\Support\Str::limit(
                                    $product->description ?: 'A delicious bakery favourite, prepared fresh with care.',
                                    95
                                ) }}
                            </p>

                            <div class="ap-card-footer">

                                <div class="ap-product-price">
                                    <span class="ap-price-currency">Rs.</span>
                                    <strong>{{ number_format((float)$product->price, 0) }}</strong>
                                </div>

                                <a href="{{ url('/all-products') . '?search=' . urlencode($product->name) }}"
                                   class="ap-view-product">
                                    View Product <span>→</span>
                                </a>
                            </div>

                            {{-- ADD TO CART --}}
                            <form action="{{ url('/cart/add/' . $product->id) }}"
                                  method="POST"
                                  class="ap-add-cart-form">
                                @csrf

                                <input type="hidden" name="quantity" value="1">

                                <button type="submit" class="ap-add-cart-btn">
                                    <span>＋</span> Add to Cart
                                </button>
                            </form>

                        </div>
                    </article>

                @empty

                    <div class="ap-empty-state">
                        <div class="ap-empty-icon">🥐</div>
                        <span class="ap-section-label">A LITTLE QUIET IN OUR KITCHEN</span>

                        <h3>
                            @if(!empty($search) || !empty($categoryId))
                                No matching products found
                            @else
                                Our products are coming soon
                            @endif
                        </h3>

                        <p>
                            @if(!empty($search) || !empty($categoryId))
                                Try another search or choose a different category
                                to discover more bakery favourites.
                            @else
                                There are no available products to display right now.
                                Please check back soon.
                            @endif
                        </p>

                        @if(!empty($search) || !empty($categoryId))
                            <a href="{{ url('/all-products') }}"
                               class="ap-primary-button">
                                Browse All Products <span>→</span>
                            </a>
                        @endif
                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            @if($products->hasPages())
                <div class="ap-pagination">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </section>

    <section class="ap-cta-section">
        <div class="ap-container">
            <div class="ap-cta">

                <div class="ap-cta-decoration">✧</div>

                <div class="ap-cta-copy">
                    <span>EVERY BITE, A LITTLE HAPPINESS</span>
                    <h2>Life Is Sweeter<br><em>With Something Fresh.</em></h2>
                    <p>
                        Find your next favourite treat in our bakery collection.
                    </p>
                </div>

                <a href="{{ url('/all-categories') }}" class="ap-cta-button">
                    Explore Categories <span>→</span>
                </a>

            </div>
        </div>
    </section>

</main>

@endsection
