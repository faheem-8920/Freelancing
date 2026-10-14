@extends('admin.layouts.adminlayout')

@section('title', 'All Products')

@section('content')
<div class="category-page product-page">

    <div class="category-page-header">
        <div>
            <span class="category-eyebrow">BAKERY MANAGEMENT</span>
            <h1>All Products</h1>
            <p>Manage cakes, pastries, breads and other bakery products.</p>
        </div>

        <a href="/admin/addproduct" class="category-btn category-btn-primary">
            + Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="category-alert category-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="category-alert category-alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="category-panel">
        <div class="category-panel-header">
            <div>
                <h2>Product Directory</h2>
                <p>Review product details and availability.</p>
            </div>

            <span class="category-count">{{ $products->total() }} products</span>
        </div>

        <div class="category-table-wrapper">
            <table class="category-table product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Availability</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="category-info">
                                    <div class="category-image">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                            >
                                        @else
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        @endif
                                    </div>

                                    <div class="category-name-wrapper">
                                        <strong>{{ $product->name }}</strong>
                                        <small>{{ $product->slug }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $product->category?->name ?? 'Uncategorized' }}</td>

                            <td class="product-price">
                                Rs. {{ number_format((float) $product->price, 2) }}
                            </td>

                            <td>
                                <span class="category-status {{ $product->is_available ? 'status-active' : 'status-inactive' }}">
                                    <span></span>
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>

                            <td>
                                @if($product->is_featured)
                                    <span class="product-featured">Featured</span>
                                @else
                                    <span class="product-not-featured">Standard</span>
                                @endif
                            </td>

                            <td>
                                <div class="category-actions">
                                    <a
                                        href="/admin/editproduct/{{ $product->id }}"
                                        class="category-action-btn edit-action"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="/admin/deleteproduct/{{ $product->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this product and its images?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="category-action-btn delete-action">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="category-empty-state">
                                    <div class="empty-state-icon">P</div>
                                    <h3>No products yet</h3>
                                    <p>Add your first bakery product to get started.</p>
                                    <a href="/admin/addproduct" class="category-btn category-btn-primary">
                                        Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="category-pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection