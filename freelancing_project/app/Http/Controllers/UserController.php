<?php

namespace App\Http\Controllers;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    private function getSettings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function home()
    {
        $settings = $this->getSettings();

        $categories = Category::where('is_active', true)
            ->withCount([
                'products' => fn ($q) => $q->where('is_available', true)
            ])
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->where('is_featured', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        $products = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        return view('user.home', compact(
            'settings',
            'categories',
            'featuredProducts',
            'products'
        ));
    }

    public function allCategories(Request $request)
    {
        $settings = $this->getSettings();

        $query = Category::where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query
            ->withCount([
                'products' => fn ($q) => $q->where('is_available', true)
            ])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user.categories.allcategories', compact('settings', 'categories'));
    }

    public function allProducts(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'min:1'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'sort' => ['nullable', 'in:latest,oldest,price_low,price_high,name'],
        ]);

        $settings = $this->getSettings();

        $query = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true));

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        switch ($request->input('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.products.allproducts', compact(
            'settings',
            'products',
            'categories'
        ));
    }

public function about()
{
    return view('user.about');
}


public function contact()
{
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('user.contact', compact('settings'));
}




public function sendContact(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'phone' => ['nullable', 'string', 'max:25'],
        'subject' => [
            'required',
            'string',
            'in:Product Inquiry,Custom Order,Order Support,Feedback,Other',
        ],
        'message' => ['required', 'string', 'max:5000'],
    ]);

    // Pehle message database mein save hoga.
    $contactMessage = ContactMessage::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'subject' => $validated['subject'],
        'message' => $validated['message'],
    ]);

    // Email notification optional hai.
    $settings = Setting::pluck('value', 'key')->toArray();
    $recipient = $settings['email'] ?? config('mail.from.address');

    if (!empty($recipient)) {
        try {
            $emailBody =
                "New contact message from Sweet Crumbs Bakery\n\n" .
                "Name: {$contactMessage->name}\n" .
                "Email: {$contactMessage->email}\n" .
                "Phone: " . ($contactMessage->phone ?? 'Not provided') . "\n" .
                "Subject: {$contactMessage->subject}\n\n" .
                "Message:\n{$contactMessage->message}";

            Mail::raw($emailBody, function ($mail) use ($recipient, $contactMessage) {
                $mail->to($recipient)
                    ->subject('Website Contact: ' . $contactMessage->subject)
                    ->replyTo($contactMessage->email, $contactMessage->name);
            });
        } catch (\Throwable $exception) {
            // Message database mein save ho chuka hai.
            // Email failure ko log karenge, message lose nahi hoga.
            report($exception);
        }
    }

    return redirect(url('/contact') . '#contact-form')
        ->with('success', 'Thank you! Your message has been received. We will get back to you soon.');
}

/**
 * Get or create a persistent cart for the current visitor.
 */
private function getPersistentCart(Request $request): Cart
{
    $token = $request->cookie('cart_token');

    $guestCart = null;

    if ($token) {
        $guestCart = Cart::where('token', $token)
            ->whereNull('user_id')
            ->with('items')
            ->first();
    }

    // Logged-in user: use their account cart.
    if (auth()->check()) {
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            ['token' => (string) Str::uuid()]
        );

        // Merge guest cart into the account cart.
        if ($guestCart && $guestCart->id !== $cart->id) {
            foreach ($guestCart->items as $guestItem) {
                $existingItem = $cart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->quantity = min(
                        99,
                        (int) $existingItem->quantity + (int) $guestItem->quantity
                    );

                    $existingItem->save();
                } else {
                    $cart->items()->create([
                        'product_id' => $guestItem->product_id,
                        'quantity' => min(99, (int) $guestItem->quantity),
                    ]);
                }
            }

            $guestCart->delete();
        }

        Cookie::queue(
            cookie('cart_token', $cart->token, 60 * 24 * 90)
        );

        return $cart;
    }

    // Guest: reuse cart from cookie when possible.
    if ($token) {
        $cart = Cart::where('token', $token)
            ->whereNull('user_id')
            ->first();

        if ($cart) {
            Cookie::queue(
                cookie('cart_token', $cart->token, 60 * 24 * 90)
            );

            return $cart;
        }
    }

    // No existing cart: create one.
    $newToken = (string) Str::uuid();

    $cart = Cart::create([
        'user_id' => null,
        'token' => $newToken,
    ]);

    Cookie::queue(
        cookie('cart_token', $newToken, 60 * 24 * 90)
    );

    return $cart;
}


/**
 * Display cart page.
 */
public function cart(Request $request)
{
    $cart = $this->getPersistentCart($request);

    $cart->load('items.product.category');

    $items = [];
    $subtotal = 0;

    foreach ($cart->items as $cartItem) {
        $product = $cartItem->product;

        if (!$product || !$product->is_available) {
            continue;
        }

        $quantity = (int) $cartItem->quantity;
        $lineTotal = (float) $product->price * $quantity;

        $subtotal += $lineTotal;

        $items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'line_total' => $lineTotal,
        ];
    }

    return view('user.cart', compact('items', 'subtotal'));
}


/**
 * Add product to cart.
 */
public function addToCart(Request $request, $id)
{
    $validated = $request->validate([
        'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
    ]);

    $product = Product::where('is_available', true)
        ->findOrFail($id);

    $quantity = (int) ($validated['quantity'] ?? 1);

    $cart = $this->getPersistentCart($request);

    $cartItem = $cart->items()
        ->where('product_id', $product->id)
        ->first();

    $currentQuantity = $cartItem
        ? (int) $cartItem->quantity
        : 0;

    if (($currentQuantity + $quantity) > 99) {
        return back()->with(
            'cart_error',
            'Maximum 99 units of a product can be added.'
        );
    }

    if ($cartItem) {
        $cartItem->quantity = $currentQuantity + $quantity;
        $cartItem->save();
    } else {
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    return back()->with(
        'cart_success',
        $product->name . ' added to your cart.'
    );
}


/**
 * Update product quantity.
 */
public function updateCart(Request $request, $id)
{
    $validated = $request->validate([
        'quantity' => ['required', 'integer', 'min:1', 'max:99'],
    ]);

    $cart = $this->getPersistentCart($request);

    $cartItem = $cart->items()
        ->where('product_id', $id)
        ->first();

    $product = Product::where('is_available', true)
        ->find($id);

    if (!$cartItem || !$product) {
        return redirect(url('/cart'))->with(
            'cart_error',
            'This product is no longer available.'
        );
    }

    $cartItem->quantity = (int) $validated['quantity'];
    $cartItem->save();

    return redirect(url('/cart'))->with(
        'cart_success',
        'Cart quantity updated successfully.'
    );
}


/**
 * Remove one product from cart.
 */
public function removeFromCart(Request $request, $id)
{
    $cart = $this->getPersistentCart($request);

    $cart->items()
        ->where('product_id', $id)
        ->delete();

    return redirect(url('/cart'))->with(
        'cart_success',
        'Product removed from your cart.'
    );
}


/**
 * Empty the entire cart.
 */
public function clearCart(Request $request)
{
    $cart = $this->getPersistentCart($request);

    $cart->items()->delete();

    return redirect(url('/cart'))->with(
        'cart_success',
        'Your cart has been emptied.'
    );
}


public function checkout(Request $request)
{
    $cart = $this->getPersistentCart($request);
    $cart->load('items.product');

    $items = $cart->items
        ->filter(function ($item) {
            return $item->product && $item->product->is_available;
        })
        ->map(function ($item) {
            return [
                'product' => $item->product,
                'quantity' => (int) $item->quantity,
                'line_total' => (float) $item->product->price * (int) $item->quantity,
            ];
        })
        ->values()
        ->all();

    if (empty($items)) {
        return redirect('/cart')
            ->with('error', 'Your cart is empty or contains unavailable products.');
    }

    $subtotal = collect($items)->sum('line_total');

    return view('user.checkout.checkout', compact('items', 'subtotal'));
}

public function placeOrder(Request $request)
{
    $validated = $request->validate([
        'customer_name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'string', 'max:20'],
        'email' => ['nullable', 'email', 'max:150'],
        'address' => ['required', 'string', 'max:1000'],
        'city' => ['required', 'string', 'max:100'],
        'postal_code' => ['nullable', 'string', 'max:20'],
        'notes' => ['nullable', 'string', 'max:1000'],
        'payment_method' => ['required', Rule::in(['cod'])],
    ]);

    $cart = $this->getPersistentCart($request);
    $cart->load('items.product');

    $cartItems = $cart->items->filter(function ($item) {
        return $item->product && $item->product->is_available;
    });

    if ($cartItems->isEmpty()) {
        return redirect('/cart')
            ->with('error', 'Your cart is empty or your products are unavailable.');
    }

    $deliveryFee = 200;

    $order = DB::transaction(function () use ($validated, $cart, $cartItems, $deliveryFee) {
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += (float) $item->product->price * (int) $item->quantity;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
                'order_number'  => 'ORD-' . strtoupper(uniqid()),

            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'payment_method' => 'cod',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'unit_price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => (float) $item->product->price * (int) $item->quantity,
            ]);
        }

        $cart->items()->delete();

        return $order;
    });

    return redirect('/order-success/' . $order->id);
}


}