<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
     // Display all categories
    public function allCategories()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.all-categories', compact('categories'));
    }

    // Show add category form
    public function showAddCategory()
    {
        return view('admin.categories.add-category');
    }

    // Save new category
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['slug'] = $this->generateCategorySlug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        Category::create($validated);

        return redirect('/admin/allcategories')
            ->with('success', 'Category added successfully.');
    }

    // Show edit category form
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit-category', compact('category'));
    }

    // Update existing category
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($category->name !== $validated['name']) {
            $validated['slug'] = $this->generateCategorySlug(
                $validated['name'],
                $category->id
            );
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        $category->update($validated);

        return redirect('/admin/allcategories')
            ->with('success', 'Category updated successfully.');
    }

    // Delete category
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        // Products reference this category, so don't delete it while in use.
        if ($category->products()->exists()) {
            return redirect('/admin/allcategories')
                ->with('error', 'This category has products. Move or remove those products first.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect('/admin/allcategories')
            ->with('success', 'Category deleted successfully.');
    }

    // Generate a unique URL-friendly slug
    private function generateCategorySlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug !== '' ? $baseSlug : 'category';
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = ($baseSlug !== '' ? $baseSlug : 'category') . '-' . $counter;
            $counter++;
        }

        return $slug;
    }


public function allProducts()
{
    $products = Product::with('category')
        ->latest()
        ->paginate(10);

    return view('admin.products.all-products', compact('products'));
}

public function showAddProduct()
{
    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('admin.products.add-product', compact('categories'));
}

public function storeProduct(Request $request)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255|unique:products,name',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0|max:99999999.99',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'additional_images' => 'nullable|array|max:8',
        'additional_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_available' => 'required|boolean',
        'is_featured' => 'required|boolean',
    ]);

    $validated['slug'] = $this->generateProductSlug($validated['name']);
    $validated['image'] = $request->file('image')->store('products', 'public');

    $product = Product::create(collect($validated)->except('additional_images')->all());

    foreach ($request->file('additional_images', []) as $index => $image) {
        $path = $image->store('products/gallery', 'public');

        $product->images()->create([
            'image' => $path,
            'sort_order' => $index,
        ]);
    }

    return redirect('/admin/allproducts')
        ->with('success', 'Product added successfully.');
}

public function editProduct($id)
{
    $product = Product::with('images')->findOrFail($id);

    $categories = Category::orderBy('name')->get();

    return view('admin.products.edit-product', compact('product', 'categories'));
}

public function updateProduct(Request $request, $id)
{
    $product = Product::with('images')->findOrFail($id);

    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255|unique:products,name,' . $product->id,
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0|max:99999999.99',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'additional_images' => 'nullable|array|max:8',
        'additional_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_available' => 'required|boolean',
        'is_featured' => 'required|boolean',
    ]);

    if ($product->name !== $validated['name']) {
        $validated['slug'] = $this->generateProductSlug(
            $validated['name'],
            $product->id
        );
    }

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update(collect($validated)->except('additional_images')->all());

    foreach ($request->file('additional_images', []) as $image) {
        $path = $image->store('products/gallery', 'public');

        $product->images()->create([
            'image' => $path,
            'sort_order' => $product->images()->count(),
        ]);
    }

    return redirect('/admin/allproducts')
        ->with('success', 'Product updated successfully.');
}

public function deleteProduct($id)
{
    $product = Product::with('images')->findOrFail($id);

    foreach ($product->images as $image) {
        Storage::disk('public')->delete($image->image);
        $image->delete();
    }

    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }

    $product->delete();

    return redirect('/admin/allproducts')
        ->with('success', 'Product deleted successfully.');
}

private function generateProductSlug(string $name, ?int $ignoreId = null): string
{
    $baseSlug = Str::slug($name);
    $slug = $baseSlug ?: 'product';
    $counter = 1;

    while (
        Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
    ) {
        $slug = ($baseSlug ?: 'product') . '-' . $counter;
        $counter++;
    }

    return $slug;
}

public function showSettings()
{
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('admin.settings.settings', compact('settings'));
}

public function updateSettings(Request $request)
{
    $colorRule = ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'];

    $validated = $request->validate([
        'site_name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:30',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:1000',
        'hero_title' => 'nullable|string|max:255',
        'hero_description' => 'nullable|string|max:2000',

        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:1024',

        'primary_color' => $colorRule,
        'secondary_color' => $colorRule,
        'accent_color' => $colorRule,
        'background_color' => $colorRule,
        'text_color' => $colorRule,
        'heading_color' => $colorRule,
        'button_color' => $colorRule,
        'button_text_color' => $colorRule,
        'button_hover_color' => $colorRule,
        'button_hover_text_color' => $colorRule,
        'link_color' => $colorRule,
        'link_hover_color' => $colorRule,
        'border_color' => $colorRule,
        'footer_bg_color' => $colorRule,
        'footer_text_color' => $colorRule,
        'animation_color' => $colorRule,

        'font_family' => 'nullable|in:Poppins,Roboto,Open Sans,Lato,Montserrat,Arial,Georgia',
        'heading_font_family' => 'nullable|in:Poppins,Roboto,Open Sans,Lato,Montserrat,Arial,Georgia',
        'font_size' => 'nullable|integer|min:12|max:20',
        'heading_size' => 'nullable|integer|min:24|max:48',
        'border_radius' => 'nullable|integer|in:0,4,8,10,16,24',
        'animation_speed' => 'nullable|integer|in:150,300,500,800',
        'button_hover_effect' => 'nullable|in:none,lift,scale,glow',
        'animations_enabled' => 'required|boolean',
    ]);

    foreach ([
        'logo' => 'site_logo',
        'favicon' => 'site_favicon',
    ] as $field => $key) {
        if ($request->hasFile($field)) {
            $oldPath = Setting::where('key', $key)->value('value');

            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->file($field)->store('settings', 'public')]
            );
        }
    }

    unset($validated['logo'], $validated['favicon']);

    foreach ($validated as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value === null ? '' : (string) $value]
        );
    }

    return redirect('/admin/settings')
        ->with('success', 'All website settings saved successfully.');
}


public function resetSettings()
{
    $defaults = [
        'site_name' => 'Sweet Crumbs Bakery',
        'phone' => '',
        'email' => '',
        'address' => '',
        'hero_title' => 'Freshly Baked, Made with Love',
        'hero_description' => 'Delicious cakes, pastries and treats made fresh every day.',

        'primary_color' => '#59392b',
        'secondary_color' => '#f7efe3',
        'accent_color' => '#b96f4c',
        'background_color' => '#fffdf9',
        'text_color' => '#493a32',
        'heading_color' => '#38271f',
        'button_color' => '#59392b',
        'button_text_color' => '#ffffff',
        'button_hover_color' => '#b96f4c',
        'button_hover_text_color' => '#ffffff',
        'link_color' => '#754832',
        'link_hover_color' => '#b96f4c',
        'border_color' => '#eadfd3',
        'footer_bg_color' => '#38271f',
        'footer_text_color' => '#fffdf9',
        'animation_color' => '#b96f4c',

        'font_family' => 'Poppins',
        'heading_font_family' => 'Poppins',
        'font_size' => '16',
        'heading_size' => '32',
        'border_radius' => '10',
        'animation_speed' => '300',
        'button_hover_effect' => 'lift',
        'animations_enabled' => '1',
    ];

    foreach ($defaults as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    // Remove custom branding so the frontend can use its built-in defaults.
    foreach (['site_logo', 'site_favicon'] as $key) {
        $path = Setting::where('key', $key)->value('value');

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        Setting::where('key', $key)->delete();
    }

    return redirect('/admin/settings')
        ->with('success', 'Original theme defaults have been restored.');
}





}