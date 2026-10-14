@extends('admin.layouts.adminlayout')

@section('title', 'Edit Category')

@section('content')
<div class="category-page">

    <div class="category-page-header">
        <div>
            <span class="category-eyebrow">BAKERY MANAGEMENT</span>
            <h1>Edit Category</h1>
            <p>Update the information and visibility of your category.</p>
        </div>

        <a href="/admin/allcategories" class="category-btn category-btn-secondary">
            ← Back to Categories
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

    <form
        action="/admin/updatecategory/{{ $category->id }}"
        method="POST"
        enctype="multipart/form-data"
        class="category-form"
        data-require-complete
        data-require-change
        novalidate
    >
        @csrf
        @method('PUT')

        {{-- ================= MAIN COLUMN ================= --}}
        <div class="category-form-main">
            <div class="category-panel">

                <div class="category-panel-header">
                    <div>
                        <h2>Category Information</h2>
                        <p>Editing: {{ $category->name }}</p>
                    </div>
                </div>

                <div class="category-form-body">

                    {{-- Name --}}
                    <div class="category-form-group">
                        <label for="name">Category Name <span>*</span></label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            maxlength="255"
                            autocomplete="off"
                            data-rules="required|min:2|max:255"
                            data-label="Category name"
                            required
                        >

                        @error('name')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="category-form-group">
                        <label for="description">Description <span>*</span></label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            maxlength="500"
                            placeholder="Write a short category description..."
                            data-rules="required|min:10|max:500"
                            data-label="Description"
                            required
                        >{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="category-form-group">
                        <label for="image">
                            Change Category Image
                            @unless($category->image)
                                <span>*</span>
                            @endunless
                        </label>

                        @if($category->image)
                            <div class="current-category-image">
                                <img
                                    src="{{ asset('storage/' . $category->image) }}"
                                    alt="{{ $category->name }}"
                                >
                                <div>
                                    <strong>Current image</strong>
                                    <small>Choose another image below to replace it. Leave it empty to keep this one.</small>
                                </div>
                            </div>
                        @endif

                        <div class="category-upload-box">
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                data-rules="{{ $category->image ? '' : 'required' }}"
                                data-label="Category image"
                            >

                            <label for="image" class="category-upload-label">
                                <span class="upload-symbol">↑</span>
                                <strong>Choose a replacement image or drag it here</strong>
                                <small>JPG, PNG or WEBP · Maximum 2 MB</small>
                            </label>

                            <div id="imagePreview" class="category-image-preview">
                                <img src="" alt="New category image preview">
                            </div>
                        </div>

                        @error('image')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= SIDEBAR ================= --}}
        <div class="category-form-sidebar">

            {{-- Live preview --}}
            <div class="category-panel">
                <div class="category-panel-header">
                    <div>
                        <h2>Live Preview</h2>
                        <p>How this category will look after saving.</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div
                        class="category-live-preview"
                        @if($category->image)
                            data-image="{{ asset('storage/' . $category->image) }}"
                        @endif
                    >
                        <div class="category-live-thumb">
                            <img src="" alt="" style="display:none;">
                            <span class="category-live-letter">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                        </div>

                        <div class="category-live-info">
                            <strong data-live-name>{{ $category->name }}</strong>
                            <small data-live-desc>{{ \Illuminate\Support\Str::limit($category->description, 70) }}</small>
                            <span class="category-status status-active" data-live-status>
                                <span></span> Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visibility --}}
            <div class="category-panel">
                <div class="category-panel-header">
                    <div>
                        <h2>Visibility</h2>
                        <p>Manage category status.</p>
                    </div>
                </div>

                <div class="category-form-body">
                    <div class="category-form-group">
                        <span class="category-label-text" id="statusLabel">Category Status</span>

                        <div class="status-options" role="radiogroup" aria-labelledby="statusLabel">
                            <label class="status-option">
                                <input
                                    type="radio"
                                    name="is_active"
                                    value="1"
                                    {{ (string) old('is_active', (int) $category->is_active) === '1' ? 'checked' : '' }}
                                >
                                <span class="status-option-body status-option-active">
                                    <span class="status-option-dot"></span>
                                    <span>
                                        <strong>Active</strong>
                                        <small>Visible on website</small>
                                    </span>
                                </span>
                            </label>

                            <label class="status-option">
                                <input
                                    type="radio"
                                    name="is_active"
                                    value="0"
                                    {{ (string) old('is_active', (int) $category->is_active) === '0' ? 'checked' : '' }}
                                >
                                <span class="status-option-body status-option-inactive">
                                    <span class="status-option-dot"></span>
                                    <span>
                                        <strong>Inactive</strong>
                                        <small>Hidden from customers</small>
                                    </span>
                                </span>
                            </label>
                        </div>

                        @error('is_active')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="category-tip">
                        <strong>Category ID</strong>
                        <p>#{{ $category->id }}</p>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="category-panel category-progress-panel">
                <div class="category-form-body">
                    <div class="category-progress-head">
                        <strong>Form progress</strong>
                        <span class="category-progress-text">0 of 3 completed</span>
                    </div>

                    <div class="category-progress-bar"><span></span></div>

                    <ul class="category-progress-list">
                        <li data-progress-for="name"><i></i> Category name</li>
                        <li data-progress-for="description"><i></i> Description</li>
                        <li data-progress-for="image"><i></i> Category image{{ $category->image ? ' (current kept)' : '' }}</li>
                    </ul>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="category-form-buttons">
                <button type="submit" class="category-btn category-btn-primary" disabled>
                    Update Category
                </button>

                <p class="category-submit-hint">Make a change and fill all fields to enable updating.</p>

                <a href="/admin/allcategories"
                   class="category-btn category-btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

{{-- JavaScript is loaded from public/js/admin/admin.js via the layout --}}
@endsection