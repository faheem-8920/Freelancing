@extends('admin.layouts.adminlayout')

@section('title', 'Edit Product')

@section('content')
<div class="category-page product-page">

    <div class="category-page-header">
        <div>
            <span class="category-eyebrow">BAKERY MANAGEMENT</span>
            <h1>Edit Product</h1>
            <p>Update product details, pricing and images.</p>
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

    <form action="/admin/updateproduct/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="category-form">
        @csrf
        @method('PUT')

        <div class="category-form-main">
            <div class="category-panel">
                <div class="category-panel-header">
                    <div>
                        <h2>Product Information</h2>
                        <p>Editing: {{ $product->name }}</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div class="category-form-group">
                        <label for="name">Product Name <span>*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $product->name) }}"
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
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label for="price">Price (PKR) <span>*</span></label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="{{ old('price', $product->price) }}"
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
                            placeholder="Describe the product..."
                        >{{ old('description', $product->description) }}</textarea>
                        @error('description') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label>Current Main Image</label>
                        <div class="current-category-image">
                            @if($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                >
                            @else
                                <span>No image uploaded</span>
                            @endif
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <small>Upload a new image below to replace the current one.</small>
                            </div>
                        </div>
                    </div>

                    <div class="category-form-group">
                        <label for="image">Replace Main Image (Optional)</label>
                        <div class="category-upload-box">
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >
                            <label for="image" class="category-upload-label">
                                <span class="upload-symbol">↑</span>
                                <strong>Choose replacement image</strong>
                                <small>JPG, PNG or WEBP · Maximum 2 MB</small>
                            </label>
                            <div id="mainImagePreview" class="category-image-preview">
                                <img src="" alt="New main image preview">
                            </div>
                        </div>
                        @error('image') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="category-form-group">
                        <label>Additional Images</label>

                        @if($product->images->isNotEmpty())
                            <div class="existing-product-gallery">
                                @foreach($product->images as $galleryImage)
                                    <div class="existing-gallery-item">
                                        <img
                                            src="{{ asset('storage/' . $galleryImage->image) }}"
                                            alt="Additional image for {{ $product->name }}"
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="product-muted-text">No additional images uploaded yet.</p>
                        @endif

                        <label for="additional_images">Add more images</label>
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
                                <small>Up to 8 new images · Maximum 2 MB each</small>
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
                        <p>Update product visibility.</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div class="category-form-group">
                        <label for="is_available">Availability</label>
                        <select id="is_available" name="is_available" required>
                            <option value="1" {{ (string) old('is_available', (int) $product->is_available) === '1' ? 'selected' : '' }}>
                                Available
                            </option>
                            <option value="0" {{ (string) old('is_available', (int) $product->is_available) === '0' ? 'selected' : '' }}>
                                Unavailable
                            </option>
                        </select>
                    </div>

                    <div class="category-form-group">
                        <label for="is_featured">Featured Product</label>
                        <select id="is_featured" name="is_featured" required>
                            <option value="0" {{ (string) old('is_featured', (int) $product->is_featured) === '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1" {{ (string) old('is_featured', (int) $product->is_featured) === '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>

                    <div class="category-tip">
                        <strong>Product ID</strong>
                        <p>#{{ $product->id }}</p>
                    </div>
                </div>
            </div>

            <div class="category-form-buttons">
                <button type="submit" class="category-btn category-btn-primary">
                    Update Product
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
                alert('You can select a maximum of 8 additional images at a time.');
                this.value = '';
                return;
            }

            if (files.some(file => !allowedTypes.includes(file.type) || file.size > maxSize)) {
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