
@extends('user.layouts.userlayout')

@section('title', 'Contact Us | Sweet Crumbs Bakery')

@section('content')
<main class="contact-page">

    {{-- HERO --}}
    <section class="contact-hero">
        <div class="contact-hero-overlay"></div>

        <div class="contact-hero-content">
            <span class="contact-eyebrow">WE WOULD LOVE TO HEAR FROM YOU</span>
            <h1>Let's Make Something <span>Sweet Together.</span></h1>
            <p>
                Have a question about our bakery, products, or an upcoming
                celebration? Send us a message — we'd be happy to help.
            </p>

            <a href="#contact-form" class="contact-hero-btn">
                Get in Touch <span>↓</span>
            </a>
        </div>
    </section>

    {{-- CONTACT INFO CARDS --}}
    <section class="contact-info-section">
        <div class="contact-info-grid">

            <article class="contact-info-card">
                <div class="contact-info-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 3.18 2 2 0 0 1 4.11 1h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.1 8.89a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.58 2.8.7A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="contact-card-label">CALL US</span>
                <h3>Let's Talk</h3>
                <p>Have a quick question? Give us a call.</p>

                @if(!empty($settings['phone']))
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['phone']) }}">
                        {{ $settings['phone'] }}
                    </a>
                @else
                    <span class="contact-info-unavailable">Phone number not available</span>
                @endif
            </article>

            <article class="contact-info-card">
                <div class="contact-info-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <path d="m22 7-10 7L2 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="contact-card-label">EMAIL US</span>
                <h3>Send a Note</h3>
                <p>Write to us with your questions or feedback.</p>

                @if(!empty($settings['email']))
                    <a href="mailto:{{ $settings['email'] }}">
                        {{ $settings['email'] }}
                    </a>
                @else
                    <span class="contact-info-unavailable">Email address not available</span>
                @endif
            </article>

            <article class="contact-info-card">
                <div class="contact-info-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                </div>
                <span class="contact-card-label">FIND US</span>
                <h3>Our Location</h3>
                <p>We'd love to welcome you.</p>

                @if(!empty($settings['address']))
                    <span class="contact-info-address">{{ $settings['address'] }}</span>
                @else
                    <span class="contact-info-unavailable">Address not available</span>
                @endif
            </article>

        </div>
    </section>

    {{-- CONTACT FORM + SIDE PANEL --}}
    <section class="contact-main-section" id="contact-form">
        <div class="contact-main-grid">

            <div class="contact-form-wrapper">
                <span class="contact-eyebrow">DROP US A MESSAGE</span>
                <h2>How Can We <span>Help You?</span></h2>
                <p class="contact-form-intro">
                    Fill in the details below and tell us what's on your mind.
                    Please provide your correct email so we can get back to you.
                </p>

                @if(session('success'))
                    <div class="contact-alert contact-alert-success" role="status">
                        <strong>Message sent!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="contact-alert contact-alert-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="contact-alert contact-alert-error" role="alert">
                        <strong>Please check the following:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/contact') }}" method="POST" class="contact-form">
                    @csrf

                    <div class="contact-form-row">
                        <div class="contact-field">
                            <label for="contact-name">Your Name <span>*</span></label>
                            <input
                                type="text"
                                id="contact-name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                maxlength="100"
                                autocomplete="name"
                                required
                            >
                            @error('name')
                                <small class="contact-field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="contact-field">
                            <label for="contact-email">Email Address <span>*</span></label>
                            <input
                                type="email"
                                id="contact-email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                maxlength="150"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <small class="contact-field-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="contact-form-row">
                        <div class="contact-field">
                            <label for="contact-phone">Phone Number</label>
                            <input
                                type="tel"
                                id="contact-phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Your contact number"
                                maxlength="25"
                                autocomplete="tel"
                            >
                            @error('phone')
                                <small class="contact-field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="contact-field">
                            <label for="contact-subject">Subject <span>*</span></label>
                            <select id="contact-subject" name="subject" required>
                                <option value="">Choose a subject</option>
                                <option value="Product Inquiry" {{ old('subject') === 'Product Inquiry' ? 'selected' : '' }}>Product Inquiry</option>
                                <option value="Custom Order" {{ old('subject') === 'Custom Order' ? 'selected' : '' }}>Custom Order</option>
                                <option value="Order Support" {{ old('subject') === 'Order Support' ? 'selected' : '' }}>Order Support</option>
                                <option value="Feedback" {{ old('subject') === 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                <option value="Other" {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <small class="contact-field-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="contact-field">
                        <label for="contact-message">Your Message <span>*</span></label>
                        <textarea
                            id="contact-message"
                            name="message"
                            rows="6"
                            maxlength="5000"
                            placeholder="Tell us how we can help..."
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <small class="contact-field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="contact-submit-btn">
                        Send Message <span>→</span>
                    </button>
                </form>
            </div>

            <aside class="contact-side-panel">
                <div class="contact-side-image">
                    <img
                        src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=900&q=85"
                        alt="Freshly baked bakery treats"
                        loading="lazy"
                    >
                </div>

                <div class="contact-side-content">
                    <span class="contact-side-kicker">A LITTLE SWEETNESS GOES A LONG WAY</span>
                    <h3>Every Great Treat Starts with a Conversation.</h3>
                    <p>
                        Planning a celebration or looking for something special?
                        Get in touch and tell us what you have in mind.
                    </p>

                    @if(!empty($settings['phone']))
                        @php
                            $whatsappNumber = preg_replace('/\D/', '', $settings['phone']);
                            if (str_starts_with($whatsappNumber, '0')) {
                                $whatsappNumber = '92' . substr($whatsappNumber, 1);
                            }
                        @endphp

                        <a
                            href="https://wa.me/{{ $whatsappNumber }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="contact-whatsapp-btn"
                        >
                            Chat on WhatsApp <span>↗</span>
                        </a>
                    @endif
                </div>

                <div class="contact-side-bottom">
                    <span class="contact-side-star">✦</span>
                    <p>Thank you for choosing Sweet Crumbs Bakery.</p>
                </div>
            </aside>

        </div>
    </section>

    {{-- BOTTOM CTA --}}
    <section class="contact-bottom-cta">
        <span class="contact-eyebrow">A LITTLE SOMETHING DELICIOUS</span>
        <h2>While You're Here, <span>Explore Our Bakery.</span></h2>
        <p>Discover bakery favourites made for your everyday cravings and special moments.</p>
        <a href="{{ url('/all-products') }}" class="contact-hero-btn">
            Explore All Products <span>→</span>
        </a>
    </section>

</main>
@endsection
