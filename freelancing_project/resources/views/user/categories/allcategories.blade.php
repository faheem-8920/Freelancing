@extends('user.layouts.userlayout')

@section('content')

<main class="all-categories-page">

    {{-- HERO SECTION --}}
    <section class="ac-hero">
        <div class="ac-hero-decoration ac-decoration-one"></div>
        <div class="ac-hero-decoration ac-decoration-two"></div>

        <div class="ac-container ac-hero-inner">

            <span class="ac-eyebrow">
                <span class="ac-eyebrow-star">✦</span>
                SWEET CRUMBS BAKERY
            </span>

            <h1>
                A Little Something<br>
                <span>Sweet for Everyone</span>
            </h1>

            <p>
                Explore our delicious collections of freshly baked treats,
                handcrafted with love for every craving and special moment.
            </p>

            <div class="ac-breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span>/</span>
                <span>All Categories</span>
            </div>

        </div>
    </section>

    {{-- CATEGORIES SECTION --}}
    <section class="ac-section">
        <div class="ac-container">

            {{-- SECTION HEADING --}}
            <div class="ac-section-heading">
                <div class="ac-heading-copy">
                    <span class="ac-section-label">DISCOVER SOMETHING DELICIOUS</span>

                    <h2>Our <span>Collections</span></h2>

                    <p>
                        Find the perfect treat for your sweetest moments.
                    </p>
                </div>

                <div class="ac-category-total">
                    <span class="ac-total-number">{{ $categories->total() }}</span>
                    <span class="ac-total-label">
                        {{ $categories->total() === 1 ? 'Collection' : 'Collections' }}
                    </span>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="ac-toolbar">

                <form action="{{ url('/all-categories') }}"
                      method="GET"
                      class="ac-search-form">

                    <span class="ac-search-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="10.8" cy="10.8" r="6.8"></circle>
                            <path d="m16 16 4.5 4.5"></path>
                        </svg>
                    </span>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search your favourite category..."
                        aria-label="Search categories">

                    @if(!empty($search))
                        <a href="{{ url('/all-categories') }}"
                           class="ac-clear-search"
                           aria-label="Clear search">×</a>
                    @endif

                    <button type="submit" class="ac-search-button">
                        Search
                    </button>
                </form>

                <span class="ac-results-label">
                    @if(!empty($search))
                        Results for "{{ $search }}"
                    @else
                        Explore all categories
                    @endif
                </span>

            </div>

            {{-- CATEGORY GRID --}}
            <div class="ac-grid">

                @forelse($categories as $category)

                    <article class="ac-card">

                        <a href="{{ url('/all-products') . '?category=' . $category->id }}"
                           class="ac-card-image-link"
                           aria-label="Explore {{ $category->name }}">

                            <div class="ac-card-image">

                                @if($category->image)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}"
                                        alt="{{ $category->name }}"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <div class="ac-image-fallback" style="display:none;">
                                        <span>✦</span>
                                    </div>
                                @else
                                    <div class="ac-image-fallback">
                                        <span>✦</span>
                                    </div>
                                @endif

                                <div class="ac-image-overlay"></div>

                                <span class="ac-card-index">
                                    {{ sprintf('%02d', ($categories->firstItem() ?? 1) + $loop->index) }}
                                </span>

                                <span class="ac-image-action" aria-hidden="true">
                                    ↗
                                </span>

                            </div>
                        </a>

                        <div class="ac-card-content">

                            <div class="ac-card-topline">
                                <span class="ac-card-kicker">BAKED WITH LOVE</span>
                                <span class="ac-card-sparkle">✧</span>
                            </div>

                            <h3>
                                {{ $category->name }}
                            </h3>

                            <p class="ac-card-description">
                                {{ $category->description ?: 'Discover delicious treats, made fresh and prepared with care for every occasion.' }}
                            </p>

                            <div class="ac-card-footer">

                                <div class="ac-product-count">
                                    <span class="ac-count-number">
                                        {{ $category->products_count }}
                                    </span>

                                    <span>
                                        {{ $category->products_count === 1 ? 'Product' : 'Products' }}
                                    </span>
                                </div>

                                <a href="{{ url('/all-products') . '?category=' . $category->id }}"
                                   class="ac-explore-link">
                                    Explore Collection
                                    <span aria-hidden="true">→</span>
                                </a>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="ac-empty-state">

                        <div class="ac-empty-icon">🥐</div>

                        <span class="ac-section-label">NOTHING HERE JUST YET</span>

                        <h3>
                            @if(!empty($search))
                                No matching categories
                            @else
                                Our collections are on their way
                            @endif
                        </h3>

                        <p>
                            @if(!empty($search))
                                We couldn't find a category matching your search.
                                Try a different keyword or browse all categories.
                            @else
                                There are no active categories to display right now.
                                Please check back soon.
                            @endif
                        </p>

                        @if(!empty($search))
                            <a href="{{ url('/all-categories') }}"
                               class="ac-primary-button">
                                View All Categories <span>→</span>
                            </a>
                        @endif

                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            @if($categories->hasPages())
                <div class="ac-pagination">
                    {{ $categories->links() }}
                </div>
            @endif

        </div>
    </section>

    {{-- BOTTOM CALL TO ACTION --}}
    <section class="ac-cta-section">
        <div class="ac-container">

            <div class="ac-cta">
                <div class="ac-cta-decoration">✧</div>

                <div class="ac-cta-content">
                    <span class="ac-cta-label">YOUR NEXT FAVOURITE IS WAITING</span>

                    <h2>Make Every Moment<br><span>A Little Sweeter.</span></h2>

                    <p>
                        Browse our delicious bakery products and treat yourself
                        to something special today.
                    </p>
                </div>

                <a href="{{ url('/all-products') }}" class="ac-cta-button">
                    Shop All Products
                    <span aria-hidden="true">→</span>
                </a>
            </div>

        </div>
    </section>

</main>

@endsection
