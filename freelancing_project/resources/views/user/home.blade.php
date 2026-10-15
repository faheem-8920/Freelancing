@extends('user.layouts.userlayout')

@section('title', 'Home | ' . ($settings['site_name'] ?? 'Sweet Crumbs Bakery'))
@section('meta_description', $settings['hero_description'] ?? 'Freshly baked cakes, pastries and delicious treats made with love.')

@section('content')

@php
    $bakeryName = $settings['site_name'] ?? 'Sweet Crumbs';
    $heroTitle = $settings['hero_title'] ?? 'A little sweetness, made with love.';
    $heroDescription = $settings['hero_description'] ?? 'Discover handcrafted cakes, buttery pastries, and delightful treats baked fresh to make your everyday moments special.';
@endphp

{{-- HERO SECTION --}}
<section class="sc-hero">
    <div class="sc-hero-noise"></div>
    <div class="sc-hero-orb sc-orb-one"></div>
    <div class="sc-hero-orb sc-orb-two"></div>

    <div class="sc-container sc-hero-grid">

        <div class="sc-hero-content reveal">
            <div class="sc-eyebrow">
                <span class="sc-eyebrow-line"></span>
                <span>FRESH FROM OUR OVEN</span>
                <span class="sc-eyebrow-line"></span>
            </div>

            <h1 class="sc-hero-title">
                {!! nl2br(e($heroTitle)) !!}
                <span class="sc-title-decoration">Baked with love.</span>
            </h1>

            <p class="sc-hero-description">
                {{ $heroDescription }}
            </p>

            <div class="sc-hero-actions">
                <a href="{{ url('/all-products') }}" class="sc-btn sc-btn-primary">
                    Explore our treats
                    <span class="sc-btn-arrow">↗</span>
                </a>

                <a href="{{ url('/all-categories') }}" class="sc-btn sc-btn-outline">
                    Browse categories
                </a>
            </div>

            <div class="sc-hero-social-proof">
                <div class="sc-avatar-stack">
                    <span>🥐</span>
                    <span>🍰</span>
                    <span>🍪</span>
                </div>

                <div>
                    <div class="sc-stars">★★★★★</div>
                    <p>Made fresh. Shared with love.</p>
                </div>
            </div>
        </div>

        <div class="sc-hero-visual reveal reveal-delay-2">
            <div class="sc-hero-image-frame">
                <div class="sc-hero-image-glow"></div>

                <img
                    class="sc-hero-image"
                    src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1000&q=85"
                    alt="Beautiful freshly baked celebration cake"
                >

                <div class="sc-image-label">
                    <span class="sc-label-icon">✳</span>
                    <div>
                        <strong>Made with care</strong>
                        <small>Freshly baked happiness</small>
                    </div>
                </div>
            </div>

            <div class="sc-floating-card sc-floating-card-top">
                <span class="sc-floating-icon">🧁</span>
                <div>
                    <strong>Little treats</strong>
                    <small>Big happiness</small>
                </div>
            </div>

            <div class="sc-floating-card sc-floating-card-bottom">
                <span class="sc-floating-sparkle">✦</span>
                <div>
                    <strong>Sweet moments</strong>
                    <small>Start right here</small>
                </div>
            </div>

            <div class="sc-hero-stamp">
                <svg viewBox="0 0 120 120" aria-label="Baked with love">
                    <defs>
                        <path id="sc-circle-path"
                              d="M60,60 m-42,0 a42,42 0 1,1 84,0 a42,42 0 1,1 -84,0"/>
                    </defs>
                    <text>
                        <textPath href="#sc-circle-path">
                            FRESHLY BAKED • MADE WITH LOVE •
                        </textPath>
                    </text>
                    <text x="60" y="68" text-anchor="middle" class="sc-stamp-heart">♥</text>
                </svg>
            </div>
        </div>

    </div>

    <div class="sc-hero-bottom">
        <span>GOOD THINGS ARE BAKING</span>
        <span class="sc-bottom-line"></span>
        <a href="#sc-categories">SCROLL TO DISCOVER ↓</a>
    </div>
</section>


{{-- MARQUEE --}}
<div class="sc-marquee" aria-label="Freshly baked with love">
    <div class="sc-marquee-track">
        @for ($i = 0; $i < 4; $i++)
            <span>BAKED FRESH DAILY</span>
            <span class="sc-marquee-star">✳</span>
            <span>A LITTLE JOY IN EVERY BITE</span>
            <span class="sc-marquee-star">✳</span>
            <span>MADE WITH LOVE</span>
            <span class="sc-marquee-star">✳</span>
        @endfor
    </div>
</div>


{{-- CATEGORIES --}}
<section class="sc-section sc-categories-section" id="sc-categories">
    <div class="sc-container">

        <div class="sc-section-heading reveal">
            <div>
                <div class="sc-section-kicker">
                    <span>01 / THE COLLECTION</span>
                </div>
                <h2 class="sc-heading">
                    Find your kind<br>
                    <em>of sweetness.</em>
                </h2>
            </div>

            <div class="sc-heading-side">
                <p>From celebration-worthy cakes to everyday little indulgences, there's something waiting to brighten your day.</p>
                <a href="{{ url('/all-categories') }}" class="sc-text-link">
                    Explore all categories <span>↗</span>
                </a>
            </div>
        </div>

        <div class="sc-category-grid">
            @forelse ($categories as $index => $category)
                @php
                    $categoryFallbacks = [
                        'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1550617931-e17a7b70dce2?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=700&q=80',
                    ];
                    $categoryImage = $category->image
                        ? \Illuminate\Support\Facades\Storage::url($category->image)
                        : $categoryFallbacks[$index % count($categoryFallbacks)];
                @endphp

                <a href="{{ url('/all-products') }}?category={{ $category->id }}"
                   class="sc-category-card reveal"
                   style="--card-index: {{ $index }}">

                    <div class="sc-category-image-wrap">
                        <img src="{{ $categoryImage }}"
                             alt="{{ $category->name }}"
                             class="sc-category-image"
                             loading="lazy">

                        <span class="sc-category-number">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <span class="sc-category-arrow">↗</span>
                    </div>

                    <div class="sc-category-info">
                        <div>
                            <h3>{{ $category->name }}</h3>
                            <p>
                                {{ $category->products_count ?? $category->products()->count() }}
                                delicious creations
                            </p>
                        </div>
                        <span class="sc-category-link-icon">↗</span>
                    </div>
                </a>
            @empty
                <div class="sc-empty-state">
                    <span>🧁</span>
                    <h3>Something sweet is coming</h3>
                    <p>Our categories will be available here soon.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>


{{-- EDITOR'S PICKS / FEATURED PRODUCTS --}}
<section class="sc-section sc-featured-section">
    <div class="sc-container">

        <div class="sc-section-heading reveal">
            <div>
                <div class="sc-section-kicker">
                    <span>02 / CUSTOMER FAVOURITES</span>
                </div>
                <h2 class="sc-heading">
                    A few things<br>
                    <em>we love.</em>
                </h2>
            </div>

            <div class="sc-heading-side">
                <p>Carefully crafted, beautifully presented, and made to turn ordinary moments into something special.</p>
                <a href="{{ url('/all-products') }}" class="sc-text-link">
                    View all treats <span>↗</span>
                </a>
            </div>
        </div>

        <div class="sc-product-grid">
            @forelse ($featuredProducts as $index => $product)
                @php
                    $productFallbacks = [
                        'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1550617931-e17a7b70dce2?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=700&q=80',
                        'https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=700&q=80',
                    ];
                    $productImage = $product->image
                        ? \Illuminate\Support\Facades\Storage::url($product->image)
                        : $productFallbacks[$index % count($productFallbacks)];
                @endphp

                <article class="sc-product-card reveal" style="--card-index: {{ $index }}">
                    <a href="{{ url('/all-products') }}?search={{ urlencode($product->name) }}"
                       class="sc-product-image-wrap">

                        <img src="{{ $productImage }}"
                             alt="{{ $product->name }}"
                             class="sc-product-image"
                             loading="lazy">

                        <span class="sc-product-tag">OUR PICK</span>

                        <span class="sc-product-quick-view">
                            Discover product <span>↗</span>
                        </span>
                    </a>

                    <div class="sc-product-details">
                        <div class="sc-product-category">
                            {{ $product->category->name ?? 'Freshly baked' }}
                        </div>

                        <h3>
                            <a href="{{ url('/all-products') }}?search={{ urlencode($product->name) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="sc-product-bottom">
                            <div class="sc-product-price">
                                <span class="sc-price-currency">Rs.</span>
                                {{ number_format((float) $product->price, 0) }}
                            </div>

                            <a href="{{ url('/all-products') }}?search={{ urlencode($product->name) }}"
                               class="sc-product-add"
                               aria-label="View {{ $product->name }}">
                                ↗
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="sc-empty-state">
                    <span>🍰</span>
                    <h3>Our favourites are on their way</h3>
                    <p>Add available featured products from your admin panel to show them here.</p>
                    <a href="{{ url('/all-products') }}" class="sc-btn sc-btn-primary">
                        Browse all products ↗
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</section>


{{-- BRAND STORY --}}
<section class="sc-story-section">
    <div class="sc-container sc-story-grid">

        <div class="sc-story-visual reveal">
            <div class="sc-story-main-image">
                <img
                    src="https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=1000&q=85"
                    alt="Beautiful cupcakes freshly prepared"
                    loading="lazy">
            </div>

            <div class="sc-story-side-image">
                <img
                    src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=600&q=85"
                    alt="Fresh homemade cookies"
                    loading="lazy">
            </div>

            <div class="sc-story-note">
                <span>✳</span>
                <p>Every bite tells<br> a sweeter story.</p>
            </div>
        </div>

        <div class="sc-story-content reveal reveal-delay-2">
            <div class="sc-section-kicker">
                <span>03 / OUR LITTLE WORLD</span>
            </div>

            <h2 class="sc-heading">
                Not just baked.<br>
                <em>Made memorable.</em>
            </h2>

            <p>
                We believe the best memories often begin with something sweet.
                That's why every treat at {{ $bakeryName }} is all about flavour,
                care, and those little moments worth celebrating.
            </p>

            <p>
                Whether it's a birthday, a family gathering, or simply a Tuesday
                that needs cheering up, we're here to make it a little sweeter.
            </p>

            <div class="sc-story-signature">
                <span class="sc-signature-heart">♥</span>
                <div>
                    <strong>Baked with love, always.</strong>
                    <small>Your neighbourhood bakery</small>
                </div>
            </div>

            <a href="{{ url('/about') }}" class="sc-btn sc-btn-primary">
                Get to know us <span class="sc-btn-arrow">↗</span>
            </a>
        </div>

    </div>
</section>


{{-- LATEST PRODUCTS --}}
@if(isset($products) && $products->count())
<section class="sc-section sc-latest-section">
    <div class="sc-container">

        <div class="sc-section-heading reveal">
            <div>
                <div class="sc-section-kicker">
                    <span>04 / FRESHLY ADDED</span>
                </div>
                <h2 class="sc-heading">
                    New to the<br>
                    <em>sweet side.</em>
                </h2>
            </div>

            <div class="sc-heading-side">
                <p>There's always something new to fall in love with.</p>
                <a href="{{ url('/all-products') }}" class="sc-text-link">
                    Shop the collection <span>↗</span>
                </a>
            </div>
        </div>

        <div class="sc-latest-list">
            @foreach ($products->take(4) as $index => $product)
                <a href="{{ url('/all-products') }}?search={{ urlencode($product->name) }}"
                   class="sc-latest-item reveal">

                    <span class="sc-latest-index">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <span class="sc-latest-name">{{ $product->name }}</span>

                    <span class="sc-latest-category">
                        {{ $product->category->name ?? 'Bakery favourite' }}
                    </span>

                    <span class="sc-latest-price">
                        Rs. {{ number_format((float) $product->price, 0) }}
                    </span>

                    <span class="sc-latest-arrow">↗</span>
                </a>
            @endforeach
        </div>

    </div>
</section>
@endif


{{-- FINAL CTA --}}
<section class="sc-cta-section">
    <div class="sc-cta-decoration sc-cta-decoration-one">✳</div>
    <div class="sc-cta-decoration sc-cta-decoration-two">✦</div>
    <div class="sc-cta-decoration sc-cta-decoration-three">✧</div>

    <div class="sc-container sc-cta-content reveal">
        <span class="sc-cta-kicker">YOUR NEXT HAPPY MOMENT STARTS HERE</span>

        <h2>
            Life is short.<br>
            <em>Eat the cake.</em>
        </h2>

        <p>Go on, treat yourself. You deserve a little sweetness today.</p>

        <a href="{{ url('/all-products') }}" class="sc-btn sc-btn-light">
            Find your favourite <span class="sc-btn-arrow">↗</span>
        </a>

        <div class="sc-cta-footer-note">
            <span>♥</span> Made fresh for your sweetest moments
        </div>
    </div>
</section>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const revealItems = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -35px 0px'
        });

        revealItems.forEach(function (item) {
            revealObserver.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
    }

    const hero = document.querySelector('.sc-hero');
    const heroVisual = document.querySelector('.sc-hero-visual');

    if (hero && heroVisual && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        hero.addEventListener('mousemove', function (event) {
            const rect = hero.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            heroVisual.style.setProperty('--mouse-x', (x * 10) + 'px');
            heroVisual.style.setProperty('--mouse-y', (y * 10) + 'px');
        });

        hero.addEventListener('mouseleave', function () {
            heroVisual.style.setProperty('--mouse-x', '0px');
            heroVisual.style.setProperty('--mouse-y', '0px');
        });
    }
});
</script>
@endpush