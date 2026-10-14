@extends('admin.layouts.adminlayout')

@section('title', 'Add Product')

@section('content')
<div class="category-page product-page">

    <div class="category-page-header">
        <div>
            <span class="category-eyebrow">BAKERY MANAGEMENT</span>
            <h1>Add New Product</h1>
            <p>Add a product to your bakery's menu.</p>
        </div>

        <a href="/admin/allproducts" class="category-btn category-btn-secondary">
            ← Back to Products
        </a>
    </div>

    @if($errors->any())
        <div class="category-alert category-alert-error">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/admin/addproduct" method="POST" enctype="multipart/form-data" class="category-form">
        @csrf

        <div class="category-form-main">
            <div class="category-panel">
                <div class="category-panel-header">
                    <div>
                        <h2>Product Information</h2>
                        <p>Enter the product details shown to customers.</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div class="category-form-group">
                        <label for="name">Product Name <span>*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Chocolate Fudge Cake"
                            maxlength="255"
                            required
                        >
                        @error('name') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label for="category_id">Category <span>*</span></label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <small class="field-error">{{ $message }}</small> @enderror

                        @if($categories->isEmpty())
                            <small class="field-error">
                                No active categories found. Add and activate a category first.
                            </small>
                        @endif
                    </div>

                    <div class="category-form-group">
                        <label for="price">Price (PKR) <span>*</span></label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="{{ old('price') }}"
                            placeholder="e.g. 1500"
                            min="0"
                            max="99999999.99"
                            step="0.01"
                            required
                        >
                        @error('price') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label for="description">Product Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Describe the taste, ingredients or size..."
                        >{{ old('description') }}</textarea>
                        @error('description') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label for="image">Main Product Image <span>*</span></label>
                        <div class="category-upload-box">
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                required
                            >
                            <label for="image" class="category-upload-label">
                                <span class="upload-symbol">↑</span>
                                <strong>Choose main product image</strong>
                                <small>JPG, PNG or WEBP · Maximum 2 MB</small>
                            </label>
                            <div id="mainImagePreview" class="category-image-preview">
                                <img src="" alt="Main product preview">
                            </div>
                        </div>
                        @error('image') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label for="additional_images">Additional Images (Optional)</label>
                        <div class="category-upload-box">
                            <input
                                type="file"
                                id="additional_images"
                                name="additional_images[]"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                multiple
                            >
                            <label for="additional_images" class="category-upload-label">
                                <span class="upload-symbol">＋</span>
                                <strong>Choose additional images</strong>
                                <small>Up to 8 images · Maximum 2 MB each</small>
                            </label>
                            <div id="galleryPreview" class="product-gallery-preview"></div>
                        </div>
                        @error('additional_images') <small class="field-error">{{ $message }}</small> @enderror
                        @error('additional_images.*') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="category-form-sidebar">
            <div class="category-panel">
                <div class="category-panel-header">
                    <div>
                        <h2>Product Settings</h2>
                        <p>Set the product's visibility.</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div class="category-form-group">
                        <label for="is_available">Availability</label>
                        <select id="is_available" name="is_available" required>
                            <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>
                                Available
                            </option>
                            <option value="0" {{ old('is_available') === '0' ? 'selected' : '' }}>
                                Unavailable
                            </option>
                        </select>
                    </div>

                    <div class="category-form-group">
                        <label for="is_featured">Featured Product</label>
                        <select id="is_featured" name="is_featured" required>
                            <option value="0" {{ old('is_featured', '0') == '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1" {{ old('is_featured') === '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>

                    <div class="category-tip">
                        <strong>Featured products</strong>
                        <p>Mark popular items as featured so your storefront can highlight them on the homepage.</p>
                    </div>
                </div>
            </div>

            <div class="category-form-buttons">
                <button
                    type="submit"
                    class="category-btn category-btn-primary"
                    {{ $categories->isEmpty() ? 'disabled' : '' }}
                >
                    Save Product
                </button>

                <a href="/admin/allproducts" class="category-btn category-btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    const maxSize = 2 * 1024 * 1024;

    function setupPreview(inputId, previewId, multiple = false) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener('change', function () {
            preview.innerHTML = '';
            preview.style.display = 'none';

            const files = Array.from(this.files || []);

            if (multiple && files.length > 8) {
                alert('You can select a maximum of 8 additional images.');
                this.value = '';
                return;
            }

            const invalidFile = files.find(file =>
                !allowedTypes.includes(file.type) || file.size > maxSize
            );

            if (invalidFile) {
                alert('Use JPG, PNG or WEBP images under 2 MB each.');
                this.value = '';
                return;
            }

            if (!files.length) return;

            preview.style.display = 'grid';

            files.forEach(file => {
                const img = document.createElement('img');
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
            });
        });
    }

    setupPreview('image', 'mainImagePreview');
    setupPreview('additional_images', 'galleryPreview', true);
});
</script>
@endsection