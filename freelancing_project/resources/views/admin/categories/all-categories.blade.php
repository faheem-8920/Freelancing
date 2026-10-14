@extends('admin.layouts.adminlayout')

@section('title', 'All Categories')

@section('content')
<div class="category-page">

    <div class="category-page-header">
        <div>
            <span class="category-eyebrow">BAKERY MANAGEMENT</span>
            <h1>All Categories</h1>
            <p>Manage the categories displayed on your bakery website.</p>
        </div>

        <a href="/admin/addcategory" class="category-btn category-btn-primary">
            <span class="btn-plus">+</span>
            Add Category
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

    <div class="category-stats-grid">
        <div class="category-stat-card">
            <div class="category-stat-icon">C</div>
            <div>
                <span>Total Categories</span>
                <h3>{{ $categories->total() }}</h3>
            </div>
        </div>

        <div class="category-stat-card">
            <div class="category-stat-icon active-icon">✓</div>
            <div>
                <span>Active on Website</span>
                <h3>{{ $categories->getCollection()->where('is_active', true)->count() }}</h3>
            </div>
        </div>

        <div class="category-stat-card">
            <div class="category-stat-icon inactive-icon">○</div>
            <div>
                <span>Inactive on This Page</span>
                <h3>{{ $categories->getCollection()->where('is_active', false)->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="category-panel">

        <div class="category-panel-header">
            <div>
                <h2>Category Directory</h2>
                <p>View and manage all your product categories.</p>
            </div>

            <span class="category-count">
                {{ $categories->total() }} categories
            </span>
        </div>

        <div class="category-table-wrapper">
            <table class="category-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="category-info">
                                    <div class="category-image">
                                        @if($category->image)
                                            <img
                                                src="{{ asset('storage/' . $category->image) }}"
                                                alt="{{ $category->name }}"
                                            >
                                        @else
                                            <span>
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="category-name-wrapper">
                                        <strong>{{ $category->name }}</strong>

                                        <small>
                                            {{ $category->products()->count() }}
                                            {{ $category->products()->count() === 1 ? 'product' : 'products' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="category-slug">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td>
                                @if($category->is_active)
                                    <span class="category-status status-active">
                                        <span></span> Active
                                    </span>
                                @else
                                    <span class="category-status status-inactive">
                                        <span></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="category-date">
                                {{ $category->created_at->format('d M, Y') }}
                            </td>

                            <td>
                                <div class="category-actions">
                                    <a
                                        href="/admin/editecategry/{{ $category->id }}"
                                        class="category-action-btn edit-action"
                                        title="Edit category"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="/admin/deletecategory/{{ $category->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="category-action-btn delete-action"
                                            title="Delete category"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="category-empty-state">
                                    <div class="empty-state-icon">C</div>
                                    <h3>No categories found</h3>
                                    <p>Create your first bakery category to get started.</p>

                                    <a href="/admin/addcategory"
                                       class="category-btn category-btn-primary">
                                        Add Your First Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="category-pagination">
                {{ $categories->links() }}
            </div>
        @endif

    </div>
</div>
@endsection 