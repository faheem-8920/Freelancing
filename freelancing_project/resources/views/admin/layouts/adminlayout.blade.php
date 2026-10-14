
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard') | Crumb & Co.</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('css/admin/adminlayout.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}"></head>
<body>

<div class="admin-wrapper">

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="brand-symbol">✦</i>
            </div>

            <div class="brand-details">
                <h2>Crumb & Co.</h2>
                <span>BAKERY ADMIN</span>
            </div>

            <button type="button"
                    class="sidebar-close"
                    id="sidebarClose"
                    aria-label="Close sidebar">
                &times;
            </button>
        </div>

        <div class="sidebar-divider"></div>

        <div class="sidebar-section-label">MAIN MENU</div>

        <nav class="sidebar-navigation">

            <a href="/admin/dashboard"
               class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="/admin/products"
               class="sidebar-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                <span class="nav-icon">▤</span>
                <span>Products</span>
            </a>

       <a href="/admin/allcategories"
   class="{{ request()->is('admin/allcategories') ? 'active' : '' }}">
    <span>Categories</span>
</a>

            <a href="/admin/orders"
               class="sidebar-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <span class="nav-icon">▣</span>
                <span>Orders</span>
            </a>

            <a href="/admin/customers"
               class="sidebar-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                <span class="nav-icon">♙</span>
                <span>Customers</span>
            </a>

        </nav>

        <div class="sidebar-section-label management-label">
            STORE MANAGEMENT
        </div>

        <nav class="sidebar-navigation">
          <a href="/admin/settings"
   class="sidebar-link {{ request()->is('admin/settings') ? 'active' : '' }}">
   
    <span>Settings</span>
</a>
            <a href="/admin/settings/appearance"
               class="sidebar-link {{ request()->is('admin/settings/appearance*') ? 'active' : '' }}">
                <span class="nav-icon">◉</span>
                <span>Appearance</span>
            </a>

            <a href="/admin/settings/contact"
               class="sidebar-link {{ request()->is('admin/settings/contact*') ? 'active' : '' }}">
                <span class="nav-icon">☏</span>
                <span>Contact Details</span>
            </a>

        </nav>

        <!-- Sidebar Bottom Card -->
        <div class="sidebar-bottom-card">
            <div class="bottom-card-icon">♨</div>
            <h4>Freshly baked.</h4>
            <p>Make your bakery stand out with every detail.</p>
        </div>

        <div class="sidebar-footer">
            <span class="status-dot"></span>
            <span>Store management</span>
            <span class="version-label">v1.0</span>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="admin-main">

        <!-- Header -->
        <header class="admin-header">

            <div class="header-left">
                <button type="button"
                        class="menu-toggle"
                        id="menuToggle"
                        aria-label="Open sidebar">
                    ☰
                </button>

                <div class="header-heading">
                    <span class="header-eyebrow">BAKERY MANAGEMENT</span>
                    <h1>@yield('page-heading', 'Dashboard')</h1>
                </div>
            </div>

            <div class="header-right">

                <a href="/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="view-store-link">
                    <span>↗</span>
                    <span>View Store</span>
                </a>

                <div class="header-divider"></div>

                <div class="admin-profile">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>

                    <div class="profile-info">
                        <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                        <span>Administrator</span>
                    </div>
                </div>

                @auth
                    <form action="/logout" method="POST">
                        @csrf

                        <button type="submit"
                                class="logout-button"
                                title="Logout"
                                aria-label="Logout">
                            ⇥
                        </button>
                    </form>
                @endauth

            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content">

            <div class="content-top">
                <div>
                    <div class="content-breadcrumb">
                        Admin Panel <span>/</span> @yield('breadcrumb', 'Dashboard')
                    </div>

                    <h2 class="content-title">
                        @yield('content-title', 'Welcome back!')
                    </h2>

                    <p class="content-subtitle">
                        @yield('content-description', 'Here is what is happening with your bakery today.')
                    </p>
                </div>

                <div class="content-date">
                    <span>◷</span>
                    {{ now()->format('d M Y') }}
                </div>
            </div>

            @if (session('success'))
                <div class="admin-alert alert-success">
                    <span>✓</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="admin-alert alert-error">
                    <span>!</span>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

        <!-- Footer -->
        <footer class="admin-footer">
            <p>
                © {{ date('Y') }}
                <strong>Crumb & Co.</strong>
                All rights reserved.
            </p>

            <div class="footer-right">
                <span>Made with care</span>
                <span class="footer-heart">♥</span>
                <span>for your bakery</span>
            </div>
        </footer>

    </div>
</div>
<script src="{{ asset('js/admin/admin.js') }}?v={{ filemtime(public_path('js/admin/admin.js')) }}" defer></script>
<script>
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const menuToggle = document.getElementById('menuToggle');
    const sidebarClose = document.getElementById('sidebarClose');

    function openSidebar() {
        adminSidebar.classList.add('sidebar-open');
        sidebarOverlay.classList.add('overlay-visible');
        document.body.classList.add('sidebar-active');
    }

    function closeSidebar() {
        adminSidebar.classList.remove('sidebar-open');
        sidebarOverlay.classList.remove('overlay-visible');
        document.body.classList.remove('sidebar-active');
    }

    menuToggle.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar-link').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 900) {
                closeSidebar();
            }
        });
    });
</script>

</body>
</html>
