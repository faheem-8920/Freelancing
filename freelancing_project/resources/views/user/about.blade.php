
@extends('user.layouts.userlayout')

@section('title', 'About Us | Sweet Crumbs Bakery')

@section('content')
<main class="about-page">

    {{-- HERO SECTION --}}
    <section class="about-hero">
        <div class="about-hero-overlay"></div>

        <div class="about-hero-content">
            <span class="about-eyebrow">A LITTLE ABOUT US</span>
            <h1>Made with Love.<br><span>Baked with Passion.</span></h1>
            <p>
                Every delicious moment begins with something freshly baked.
                Discover the passion, care, and sweetness behind Sweet Crumbs Bakery.
            </p>

            <a href="{{ url('/all-products') }}" class="about-primary-btn">
                Explore Our Bakery <span>→</span>
            </a>
        </div>

        <div class="about-hero-note">
            <span class="about-note-icon">✦</span>
            <div>
                <strong>Freshness in Every Bite</strong>
                <small>Little moments, sweeter memories.</small>
            </div>
        </div>
    </section>

    {{-- OUR STORY --}}
    <section class="about-story section-padding">
        <div class="about-story-image">
            <img
                src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1000&q=85"
                alt="Freshly baked artisan bread"
                loading="lazy"
            >
            <div class="about-image-stamp">
                <span>BAKED</span>
                <strong>with love</strong>
                <span>EVERY DAY</span>
            </div>
        </div>

        <div class="about-story-content">
            <span class="about-eyebrow">OUR STORY</span>
            <h2>A Sweet Story, <span>Fresh from the Oven.</span></h2>

            <p>
                Welcome to Sweet Crumbs Bakery, where the simple joy of freshly
                baked treats brings people closer together.
            </p>

            <p>
                We believe that great bakery products start with thoughtful
                preparation, quality ingredients, and a genuine love for what
                we do. From everyday favourites to treats for special occasions,
                every bite should feel like a little celebration.
            </p>

            <p>
                Whether you are sharing dessert with family, surprising someone
                special, or simply treating yourself, we are here to make your
                moments a little sweeter.
            </p>

            <a href="{{ url('/all-categories') }}" class="about-text-link">
                Discover Our Categories <span>↗</span>
            </a>
        </div>
    </section>

    {{-- VALUES --}}
    <section class="about-values section-padding">
        <div class="about-section-heading">
            <span class="about-eyebrow">WHAT MAKES US SPECIAL</span>
            <h2>More Than Just <span>Something Sweet</span></h2>
            <p>Thoughtful baking, delightful flavours, and a little sweetness in every day.</p>
        </div>

        <div class="about-values-grid">
            <article class="about-value-card">
                <div class="about-value-icon">✿</div>
                <h3>Quality First</h3>
                <p>We believe every good treat begins with carefully selected ingredients and attention to detail.</p>
            </article>

            <article class="about-value-card">
                <div class="about-value-icon">♡</div>
                <h3>Made with Care</h3>
                <p>We put care into our bakery experience so every order feels special from start to finish.</p>
            </article>

            <article class="about-value-card">
                <div class="about-value-icon">✦</div>
                <h3>Moments Worth Sharing</h3>
                <p>From everyday cravings to celebrations, we love being part of your sweetest memories.</p>
            </article>
        </div>
    </section>

    {{-- FEATURE BANNER --}}
    <section class="about-feature">
        <div class="about-feature-image">
            <img
                src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=1200&q=85"
                alt="Delicious freshly baked pastries"
                loading="lazy"
            >
        </div>

        <div class="about-feature-content">
            <span class="about-eyebrow">YOUR NEXT FAVOURITE IS WAITING</span>
            <h2>Something Delicious<br>Is Just Around the Corner.</h2>
            <p>
                Explore our selection of bakery favourites and find something
                wonderful for your next sweet moment.
            </p>
            <a href="{{ url('/all-products') }}" class="about-primary-btn">
                Browse All Products <span>→</span>
            </a>
        </div>
    </section>

    {{-- CLOSING MESSAGE --}}
    <section class="about-closing">
        <span class="about-closing-sparkle">✦</span>
        <h2>Thank You for Being Part of Our Story.</h2>
        <p>Here is to more smiles, more celebrations, and many more sweet crumbs.</p>
        <a href="{{ url('/all-products') }}" class="about-text-link">
            Find Your Favourite Treat <span>→</span>
        </a>
    </section>

</main>
@endsection
