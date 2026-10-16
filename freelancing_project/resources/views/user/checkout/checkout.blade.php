
@extends('user.layouts.userlayout')

@section('title', 'Checkout | Sweet Crumbs Bakery')
@section('meta_description', 'Complete your Sweet Crumbs Bakery order.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/checkout.css') }}?v={{ @filemtime(public_path('css/user/checkout.css')) }}">
@endpush

@section('content')
@php
    $deliveryFee = 200;
    $grandTotal = $subtotal + $deliveryFee;
@endphp

<section class="checkout-page">
    <div class="checkout-container">

        <div class="checkout-heading">
            <a href="{{ url('/cart') }}" class="checkout-back">
                <span>←</span> Back to cart
            </a>

            <span class="checkout-eyebrow">ALMOST SOMETHING SWEET</span>
            <h1>Complete your <em>order.</em></h1>
            <p>Just a few details and your favourite treats will be on their way.</p>

            <div class="checkout-steps">
                <span class="checkout-step completed"><b>✓</b> Cart</span>
                <span class="checkout-step-line"></span>
                <span class="checkout-step active"><b>2</b> Checkout</span>
                <span class="checkout-step-line"></span>
                <span class="checkout-step"><b>3</b> Confirmation</span>
            </div>
        </div>

        @if (session('error'))
            <div class="checkout-alert checkout-alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="checkout-alert checkout-alert-error">
                <strong>Please check the details below.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($items))
            <form action="{{ url('/checkout') }}" method="POST" id="checkoutForm">
                @csrf

                <div class="checkout-layout">

                    {{-- CUSTOMER INFORMATION --}}
                    <div class="checkout-main">

                        <div class="checkout-card">
                            <div class="checkout-card-heading">
                                <span class="checkout-section-number">01</span>
                                <div>
                                    <h2>Contact information</h2>
                                    <p>How can we reach you about your order?</p>
                                </div>
                            </div>

                            <div class="checkout-form-grid">
                                <div class="checkout-field checkout-field-full">
                                    <label for="customer_name">Full name <span>*</span></label>
                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                        placeholder="Enter your full name"
                                        maxlength="100"
                                        autocomplete="name"
                                        required
                                    >
                                    @error('customer_name')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="phone">Phone number <span>*</span></label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="03XX XXXXXXX"
                                        maxlength="20"
                                        autocomplete="tel"
                                        required
                                    >
                                    @error('phone')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="email">Email address</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        placeholder="you@example.com"
                                        maxlength="150"
                                        autocomplete="email"
                                    >
                                    @error('email')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DELIVERY DETAILS --}}
                        <div class="checkout-card">
                            <div class="checkout-card-heading">
                                <span class="checkout-section-number">02</span>
                                <div>
                                    <h2>Delivery details</h2>
                                    <p>Where should we deliver your goodies?</p>
                                </div>
                            </div>

                            <div class="checkout-form-grid">
                                <div class="checkout-field checkout-field-full">
                                    <label for="address">Complete address <span>*</span></label>
                                    <textarea
                                        id="address"
                                        name="address"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="House / flat number, street, area..."
                                        autocomplete="street-address"
                                        required
                                    >{{ old('address') }}</textarea>
                                    @error('address')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="city">City <span>*</span></label>
                                    <input
                                        type="text"
                                        id="city"
                                        name="city"
                                        value="{{ old('city') }}"
                                        placeholder="Your city"
                                        maxlength="100"
                                        autocomplete="address-level2"
                                        required
                                    >
                                    @error('city')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="postal_code">Postal code</label>
                                    <input
                                        type="text"
                                        id="postal_code"
                                        name="postal_code"
                                        value="{{ old('postal_code') }}"
                                        placeholder="Optional"
                                        maxlength="20"
                                        autocomplete="postal-code"
                                    >
                                    @error('postal_code')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="checkout-field checkout-field-full">
                                    <label for="notes">Order notes <span class="checkout-optional">(optional)</span></label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="2"
                                        maxlength="1000"
                                        placeholder="Delivery instructions or special requests..."
                                    >{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <small class="checkout-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- PAYMENT --}}
                        <div class="checkout-card">
                            <div class="checkout-card-heading">
                                <span class="checkout-section-number">03</span>
                                <div>
                                    <h2>Payment method</h2>
                                    <p>Pay when your order arrives.</p>
                                </div>
                            </div>

                            <label class="checkout-payment-option">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="cod"
                                    {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}
                                    required
                                >

                                <span class="checkout-payment-icon">💵</span>

                                <span class="checkout-payment-copy">
                                    <strong>Cash on Delivery</strong>
                                    <small>Pay in cash when you receive your order.</small>
                                </span>

                                <span class="checkout-payment-check">✓</span>
                            </label>

                            @error('payment_method')
                                <small class="checkout-field-error">{{ $message }}</small>
                            @enderror

                            <div class="checkout-secure-note">
                                <span>♡</span>
                                Your order is prepared with care and confirmed by our bakery.
                            </div>
                        </div>

                    </div>

                    {{-- ORDER SUMMARY --}}
                    <aside class="checkout-summary">
                        <div class="checkout-summary-heading">
                            <div>
                                <span class="checkout-eyebrow">YOUR SELECTION</span>
                                <h2>Order summary</h2>
                            </div>

                            <span class="checkout-item-count">
                                {{ count($items) }} {{ count($items) === 1 ? 'item' : 'items' }}
                            </span>
                        </div>

                        <div class="checkout-items">
                            @foreach ($items as $item)
                                @php
                                    $product = $item['product'];
                                    $quantity = (int) $item['quantity'];
                                    $lineTotal = (float) $item['line_total'];

                                    $image = $product->image
                                        ? \Illuminate\Support\Facades\Storage::url($product->image)
                                        : 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=160&q=80';
                                @endphp

                                <div class="checkout-product">
                                    <div class="checkout-product-image">
                                        <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
                                        <span>{{ $quantity }}</span>
                                    </div>

                                    <div class="checkout-product-info">
                                        <strong>{{ $product->name }}</strong>
                                        <small>Rs. {{ number_format((float) $product->price, 0) }} each</small>
                                    </div>

                                    <div class="checkout-product-price">
                                        Rs. {{ number_format($lineTotal, 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="checkout-summary-lines">
                            <div>
                                <span>Subtotal</span>
                                <strong>Rs. {{ number_format($subtotal, 0) }}</strong>
                            </div>

                            <div>
                                <span>Delivery</span>
                                <strong>Rs. {{ number_format($deliveryFee, 0) }}</strong>
                            </div>
                        </div>

                        <div class="checkout-grand-total">
                            <div>
                                <span>Total to pay</span>
                                <small>Including delivery</small>
                            </div>
                            <strong>Rs. {{ number_format($grandTotal, 0) }}</strong>
                        </div>

                        <button type="submit" class="checkout-place-order" id="placeOrderButton">
                            <span>Place my order</span>
                            <span>↗</span>
                        </button>

                        <p class="checkout-terms">
                            By placing your order, you confirm that your contact and delivery details are correct.
                        </p>

                        <a href="{{ url('/cart') }}" class="checkout-edit-cart">
                            ← Review your cart
                        </a>
                    </aside>

                </div>
            </form>
        @else
            <div class="checkout-empty">
                <span>🧁</span>
                <h2>Your basket is waiting for something sweet.</h2>
                <p>Add some bakery favourites before heading to checkout.</p>
                <a href="{{ url('/all-products') }}" class="checkout-place-order">
                    Explore our treats ↗
                </a>
            </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('checkoutForm');
    const button = document.getElementById('placeOrderButton');

    if (form && button) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            button.disabled = true;
            button.classList.add('is-loading');
            button.querySelector('span:first-child').textContent = 'Placing your order...';
        });
    }
});
</script>
@endpush
