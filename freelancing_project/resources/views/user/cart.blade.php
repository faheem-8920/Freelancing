
@extends('user.layouts.userlayout')

@section('content')

<main class="cart-page">

    <section class="cart-hero">
        <div class="cart-hero-content">
            <span class="cart-eyebrow">YOUR BAKERY BAG</span>
            <h1>My Shopping Cart</h1>
            <p>Your favourite bakery treats, all in one place.</p>
        </div>

        <div class="cart-hero-icon">🛍️</div>
    </section>

    <div class="cart-container">

        @if(session('cart_success'))
            <div class="cart-alert cart-alert-success">
                <span>✓</span>
                {{ session('cart_success') }}
            </div>
        @endif

        @if(session('cart_error'))
            <div class="cart-alert cart-alert-error">
                <span>!</span>
                {{ session('cart_error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="cart-alert cart-alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(count($items) > 0)

            <div class="cart-layout">

                <section class="cart-items-panel">

                    <div class="cart-panel-heading">
                        <div>
                            <h2>Your Items</h2>
                            <p>{{ count($items) }} different product(s)</p>
                        </div>

                        <form action="{{ url('/cart/clear') }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            <button type="submit" class="cart-clear-btn">
                                Clear Cart
                            </button>
                        </form>
                    </div>

                    @foreach($items as $item)
                        @php
                            $product = $item['product'];
                            $imagePath = $product->image
                                ? \Illuminate\Support\Facades\Storage::url($product->image)
                                : null;
                        @endphp

                        <article class="cart-item">

                            <div class="cart-item-image">
                                @if($imagePath)
                                    <img src="{{ $imagePath }}"
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="cart-no-image">🧁</div>
                                @endif
                            </div>

                            <div class="cart-item-details">
                                <span class="cart-item-category">
                                    {{ $product->category->name ?? 'Bakery Favourite' }}
                                </span>

                                <h3>{{ $product->name }}</h3>

                                <p class="cart-item-price">
                                    Rs. {{ number_format((float)$product->price, 2) }}
                                    <span>per item</span>
                                </p>

                                <div class="cart-item-actions">

                                    <form action="{{ url('/cart/update/' . $product->id) }}"
                                          method="POST"
                                          class="cart-quantity-form">
                                        @csrf
                                        @method('PATCH')

                                        <label for="quantity-{{ $product->id }}">
                                            Qty
                                        </label>

                                        <input
                                            type="number"
                                            id="quantity-{{ $product->id }}"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="99"
                                            required>

                                        <button type="submit" class="cart-update-btn">
                                            Update
                                        </button>
                                    </form>

                                    <form action="{{ url('/cart/remove/' . $product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remove this product from your cart?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="cart-remove-btn">
                                            Remove
                                        </button>
                                    </form>

                                </div>
                            </div>

                            <div class="cart-item-total">
                                <span>Item Total</span>
                                <strong>
                                    Rs. {{ number_format((float)$item['line_total'], 2) }}
                                </strong>
                            </div>

                        </article>
                    @endforeach

                    <a href="{{ url('/all-products') }}" class="cart-continue-link">
                        <span>←</span> Continue Shopping
                    </a>

                </section>

                <aside class="cart-summary">

                    <div class="cart-summary-heading">
                        <span>ORDER SUMMARY</span>
                        <h2>Your Total</h2>
                    </div>

                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <strong>Rs. {{ number_format((float)$subtotal, 2) }}</strong>
                    </div>

                    <div class="cart-summary-row">
                        <span>Delivery</span>
                        <span class="cart-delivery-note">Calculated at checkout</span>
                    </div>

                    <div class="cart-summary-divider"></div>

                    <div class="cart-summary-total">
                        <span>Estimated Total</span>
                        <strong>Rs. {{ number_format((float)$subtotal, 2) }}</strong>
                    </div>

                    <p class="cart-summary-note">
                        Delivery charges, if applicable, will be confirmed before
                        your order is placed.
                    </p>
              <a href="{{ url('/checkout') }}" class="cart-checkout-btn">
    Proceed to Checkout <span>→</span>
</a>

<a href="{{ url('/all-products') }}" class="cart-continue-shopping-btn">
    ← Continue Shopping
</a>

                    <div class="cart-trust-note">
                        <span>♡</span>
                        Freshly baked with love, just for you.
                    </div>

                </aside>

            </div>

        @else

            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>

                <span class="cart-eyebrow">NOTHING HERE YET</span>

                <h2>Your cart is looking a little empty!</h2>

                <p>
                    Explore our freshly baked cakes, pastries and sweet treats.
                    Your next favourite is waiting for you.
                </p>

                <a href="{{ url('/all-products') }}" class="cart-empty-btn">
                    Explore Our Products <span>→</span>
                </a>
            </div>

        @endif

    </div>
</main>

@endsection
