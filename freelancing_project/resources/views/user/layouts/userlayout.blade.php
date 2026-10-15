<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#2B211B">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'Sweet Crumbs Bakery')</title>

    <meta name="description"
          content="@yield('meta_description', 'Freshly baked cakes, pastries and delicious treats made with love.')">

    {{-- Emoji favicon (swap for a real /favicon.ico + apple-touch-icon when you have one) --}}
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E%F0%9F%A7%81%3C/text%3E%3C/svg%3E">

    {{-- Runs before paint: JS flag, saved/OS theme, dismissed announcement bar (no flash) --}}
    <script>
        (function () {
            var r = document.documentElement;
            r.classList.add('js');
            try {
                var t = localStorage.getItem('sc_theme');
                if (!t && window.matchMedia('(prefers-color-scheme: dark)').matches) t = 'dark';
                if (t) r.setAttribute('data-theme', t);
                if (localStorage.getItem('sc_bar_dismissed') === '1') r.classList.add('bar-dismissed');
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap"
          rel="stylesheet">

    {{-- Common User CSS (versioned so browsers pick up changes) --}}
    <link rel="stylesheet" href="{{ asset('css/user/userlayout.css') }}?v={{ @filemtime(public_path('css/user/userlayout.css')) }}">

    {{-- Common User Pages CSS --}}
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}?v={{ @filemtime(public_path('css/user/user.css')) }}">

    {{-- Home Page CSS --}}
    @if (request()->is('/'))
        <link rel="stylesheet" href="{{ asset('css/user/home.css') }}?v={{ @filemtime(public_path('css/user/home.css')) }}">
    @endif

    @stack('styles')
</head>

<body>

    <a href="#main" class="skip-link">Skip to content</a>
    <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

    {{-- Announcement Bar --}}
    <div class="announcement-bar" id="announcementBar" role="region" aria-label="Announcements">
        <div class="announcement-track">
            <p class="announcement-item is-active">
                Freshly baked with love, every day.
                <span>Make Every Celebration Sweeter!</span>
            </p>
            <p class="announcement-item">
                Cakes, pastries and treats, made fresh.
                <a href="{{ url('/all-products') }}">Shop now</a>
            </p>
            <p class="announcement-item">
                Planning a celebration?
                <a href="{{ url('/contact') }}">Get in touch</a>
            </p>
        </div>
        <button type="button" class="announcement-close" id="barClose" aria-label="Dismiss announcement">&times;</button>
    </div>

    {{-- Navbar --}}
    <header class="main-header" id="mainHeader">

        <div class="nav-overlay" id="navOverlay"></div>

        <div class="container">
            <nav class="navbar" aria-label="Primary">

                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark" aria-hidden="true">S</span>

                    <span class="brand-text">
                        <span class="brand-name">
                            {{ $settings['site_name'] ?? 'Sweet Crumbs' }}
                        </span>

                        <span class="brand-tagline">
                            Bakery &amp; Patisserie
                        </span>
                    </span>
                </a>

                <div class="nav-links" id="navLinks">

                    <div class="drawer-head">
                        <a href="{{ url('/') }}" class="brand">
                            <span class="brand-mark" aria-hidden="true">S</span>
                            <span class="brand-name">{{ $settings['site_name'] ?? 'Sweet Crumbs' }}</span>
                        </a>
                        <button type="button" class="drawer-close" id="drawerClose" aria-label="Close menu">&times;</button>
                    </div>

                    <span class="nav-indicator" aria-hidden="true"></span>

                    @php
                        $navItems = [
                            ['Home', '/', '/'],
                            ['Categories', '/all-categories', 'all-categories*'],
                            ['Shop', '/all-products', 'all-products*'],
                            ['About Us', '/about', 'about'],
                            ['Contact', '/contact', 'contact*'],
                        ];
                    @endphp

                    @foreach ($navItems as [$label, $path, $pattern])
                        @php $isActive = request()->is($pattern); @endphp
                        <a href="{{ url($path) }}"
                           class="nav-link {{ $isActive ? 'active' : '' }}"
                           @if ($isActive) aria-current="page" @endif>
                            {{ $label }}
                        </a>
                    @endforeach

                    <div class="drawer-foot">
                        @if (!empty($settings['phone']))
                            <div>{{ $settings['phone'] }}</div>
                        @endif
                        @if (!empty($settings['email']))
                            <div>{{ $settings['email'] }}</div>
                        @endif
                        <div class="footer-socials">
                            <a href="#" class="social-link" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".6"/></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2.5V4.5H14A4 4 0 0 0 10 8.5V11H7.5v3.5H10V21h3.5v-6.5h2.6l.5-3.5h-3.1V9c0-.6.4-1 1-1z"/></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="TikTok">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M15 3c.3 2.3 1.9 4 4.5 4.2"/></svg>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="nav-actions">

                    <button type="button" class="icon-btn theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                        <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5z"/></svg>
                        <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                    </button>

                    <a href="{{ url('/all-products') }}" class="nav-cart" aria-label="Shop">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l1 13H5L6 7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
                        <span>Shop</span>
                        <span class="cart-badge" id="cartBadge" hidden>0</span>
                    </a>

                    <button type="button"
                            class="menu-toggle"
                            id="menuToggle"
                            aria-label="Toggle navigation"
                            aria-controls="navLinks"
                            aria-expanded="false">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>

                </div>

            </nav>
        </div>
    </header>

    {{-- Flash Messages (toasts) --}}
    @if (session('success') || session('error') || $errors->any())
        <div class="toast-stack">

            @if (session('success'))
                <div class="toast toast-success" role="status">
                    <span class="toast-icon" aria-hidden="true">&#10003;</span>
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="toast-close" aria-label="Dismiss message">&times;</button>
                    <span class="toast-progress" aria-hidden="true"></span>
                </div>
            @endif

            @if (session('error'))
                <div class="toast toast-error" role="alert">
                    <span class="toast-icon" aria-hidden="true">!</span>
                    <div class="toast-body">{{ session('error') }}</div>
                    <button type="button" class="toast-close" aria-label="Dismiss message">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="toast toast-error" role="alert">
                    <span class="toast-icon" aria-hidden="true">!</span>
                    <div class="toast-body">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="toast-close" aria-label="Dismiss message">&times;</button>
                </div>
            @endif

        </div>
    @endif

    {{-- Page Content --}}
    <main id="main">
        @yield('content')
    </main>

    {{-- Newsletter --}}
    <section class="newsletter-section" aria-labelledby="newsletterTitle">
        <span class="deco deco-1" aria-hidden="true">&#129473;</span>
        <span class="deco deco-2" aria-hidden="true">&#127850;</span>
        <span class="deco deco-3" aria-hidden="true">&#129360;</span>

        <div class="container newsletter-inner">

            <div class="newsletter-content">
                <span class="eyebrow">A Little Something Sweet</span>

                <h2 id="newsletterTitle">Stay in the Sweet Loop</h2>

                <p>
                    Discover our latest creations, seasonal treats
                    and bakery news.
                </p>
            </div>

            <div class="newsletter-box">
                {{-- When the backend exists: method="POST", add @csrf and a real action --}}
                <form class="newsletter-form" id="newsletterForm" action="#" method="GET" novalidate>

                    <input type="email"
                           name="email"
                           placeholder="Enter your email address"
                           aria-label="Email address"
                           aria-describedby="newsletterNote"
                           autocomplete="email"
                           required>

                    <button type="submit" class="btn btn-primary">
                        Coming Soon
                    </button>

                </form>

                <p class="newsletter-note" id="newsletterNote" role="status">
                    We'll only email about new treats. Unsubscribe any time.
                </p>
            </div>

        </div>
    </section>

    <div class="wave-divider" aria-hidden="true">
        <svg viewBox="0 0 1440 64" preserveAspectRatio="none">
            <path d="M0,32 C240,0 480,64 720,32 C960,0 1200,64 1440,32 L1440,64 L0,64 Z"/>
        </svg>
    </div>

    {{-- Footer --}}
    <footer class="main-footer">
        <div class="container footer-main">

            <div class="footer-brand">
                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark" aria-hidden="true">S</span>

                    <span class="brand-text">
                        <span class="brand-name">
                            {{ $settings['site_name'] ?? 'Sweet Crumbs' }}
                        </span>

                        <span class="brand-tagline">
                            Bakery &amp; Patisserie
                        </span>
                    </span>
                </a>

                <p class="footer-description">
                    From everyday treats to unforgettable celebrations,
                    we bake every creation with care, quality ingredients
                    and a whole lot of love.
                </p>

                <div class="footer-socials">
                    <a href="#" class="social-link" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".6"/></svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2.5V4.5H14A4 4 0 0 0 10 8.5V11H7.5v3.5H10V21h3.5v-6.5h2.6l.5-3.5h-3.1V9c0-.6.4-1 1-1z"/></svg>
                    </a>
                    <a href="#" class="social-link" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M15 3c.3 2.3 1.9 4 4.5 4.2"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="footer-title">Quick Links</h3>

                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about') }}">Our Story</a></li>
                    <li><a href="{{ url('/all-categories') }}">Categories</a></li>
                    <li><a href="{{ url('/all-products') }}">Shop All</a></li>
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-title">Customer Care</h3>

                <ul class="footer-links">
                    <li><a href="{{ url('/contact') }}">Get in Touch</a></li>
                    <li><a href="{{ url('/all-products') }}">Browse Products</a></li>
                    <li><a href="{{ url('/all-categories') }}">Browse Categories</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-title">Get in Touch</h3>

                <div class="footer-contact-item">
                    <span class="contact-icon" aria-hidden="true">&#9906;</span>
                    <span>
                        {{ $settings['address'] ?? 'Freshly baked happiness, delivered with love.' }}
                    </span>
                </div>

                <div class="footer-contact-item">
                    <span class="contact-icon" aria-hidden="true">&#9993;</span>

                    @if (!empty($settings['email']))
                        <a href="mailto:{{ $settings['email'] }}">
                            {{ $settings['email'] }}
                        </a>
                    @else
                        <a href="{{ url('/contact') }}">Send us a message</a>
                    @endif
                </div>

                <div class="footer-contact-item">
                    <span class="contact-icon" aria-hidden="true">&#9742;</span>

                    @if (!empty($settings['phone']))
                        <a href="tel:{{ $settings['phone'] }}">
                            {{ $settings['phone'] }}
                        </a>
                    @else
                        <span>Fresh treats, made daily</span>
                    @endif
                </div>

                {{-- Optional: add a 'hours' value to your settings table to show it here --}}
                @if (!empty($settings['hours']))
                    <div class="footer-contact-item">
                        <span class="contact-icon" aria-hidden="true">&#9719;</span>
                        <span>{{ $settings['hours'] }}</span>
                    </div>
                @endif
            </div>

        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">

                <p>
                    &copy; {{ date('Y') }}
                    {{ $settings['site_name'] ?? 'Sweet Crumbs Bakery' }}.
                    All rights reserved.
                </p>

                <div class="footer-legal">
                    <a href="{{ url('/about') }}">About Us</a>
                    <a href="{{ url('/contact') }}">Contact</a>
                    <p>Baked with <span class="heart" aria-hidden="true">&hearts;</span><span class="sr-only">love</span></p>
                </div>

            </div>
        </div>
    </footer>

    {{-- Floating actions --}}
    @php $waNumber = preg_replace('/\D+/', '', $settings['phone'] ?? ''); @endphp
    @if ($waNumber)
        <a href="https://wa.me/{{ $waNumber }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20l1.3-4.2A8 8 0 1 1 8.4 18.8L4 20z"/><path d="M9 9.5c.5 2.5 2.5 4.5 5 5l1.2-1.2-2-1-.8.8c-.8-.4-1.5-1.1-1.9-1.9l.8-.8-1-2L9 9.5z"/></svg>
        </a>
    @endif

    <button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
    </button>

    {{-- Layout behaviour (move to public/js/user/userlayout.js with `defer` if you prefer) --}}
    <script>
        (function () {
            var d = document, root = d.documentElement;
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function $(s, c) { return (c || d).querySelector(s); }
            function $$(s, c) { return Array.prototype.slice.call((c || d).querySelectorAll(s)); }
            function store(k, v) {
                try { if (v === undefined) return localStorage.getItem(k); localStorage.setItem(k, v); } catch (e) { return null; }
            }

            var header = $('#mainHeader'), progress = $('#scrollProgress'), topBtn = $('#backToTop');
            var toggle = $('#menuToggle'), nav = $('#navLinks'), overlay = $('#navOverlay'), closeBtn = $('#drawerClose');
            var menuOpen = false, lastY = 0, ticking = false;

            /* ---------- Scroll: header state, progress, back-to-top, parallax ---------- */
            function onScroll() {
                var y = window.scrollY;
                var max = root.scrollHeight - window.innerHeight;

                header.classList.toggle('is-scrolled', y > 20);
                if (!menuOpen) {
                    if (y > 160 && y > lastY + 4) header.classList.add('is-hidden');
                    else if (y < lastY - 4 || y <= 160) header.classList.remove('is-hidden');
                }
                progress.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
                topBtn.classList.toggle('show', y > 400);

                if (!reduce) {
                    $$('[data-parallax]').forEach(function (el) {
                        el.style.transform = 'translate3d(0,' + (y * (parseFloat(el.dataset.parallax) || 0.15)) + 'px,0)';
                    });
                }
                lastY = y;
                ticking = false;
            }
            window.addEventListener('scroll', function () {
                if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
            }, { passive: true });
            onScroll();

            topBtn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
            });

            /* ---------- Mobile drawer ---------- */
            $$('.nav-link', nav).forEach(function (a, i) { a.style.setProperty('--i', i); });

            function setMenu(open) {
                menuOpen = open;
                nav.classList.toggle('show', open);
                header.classList.toggle('menu-open', open);
                d.body.classList.toggle('no-scroll', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) { header.classList.remove('is-hidden'); setTimeout(function () { closeBtn.focus(); }, 60); }
                else if (d.activeElement && nav.contains(d.activeElement)) { toggle.focus(); }
            }
            toggle.addEventListener('click', function () { setMenu(!menuOpen); });
            closeBtn.addEventListener('click', function () { setMenu(false); });
            overlay.addEventListener('click', function () { setMenu(false); });
            $$('.nav-link', nav).forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
            window.addEventListener('resize', function () { if (window.innerWidth > 850 && menuOpen) setMenu(false); });

            d.addEventListener('keydown', function (e) {
                if (!menuOpen) return;
                if (e.key === 'Escape') { setMenu(false); return; }
                if (e.key === 'Tab') {                       // focus trap
                    var f = $$('a[href], button', nav).filter(function (el) { return el.offsetParent !== null; });
                    if (!f.length) return;
                    var first = f[0], last = f[f.length - 1];
                    if (e.shiftKey && d.activeElement === first) { e.preventDefault(); last.focus(); }
                    else if (!e.shiftKey && d.activeElement === last) { e.preventDefault(); first.focus(); }
                }
            });

            var startX = null;                              // swipe right to close
            nav.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
            nav.addEventListener('touchend', function (e) {
                if (startX !== null && e.changedTouches[0].clientX - startX > 60) setMenu(false);
                startX = null;
            }, { passive: true });

            /* ---------- Sliding pill indicator (desktop) ---------- */
            var pill = $('.nav-indicator', nav), links = $$('.nav-link', nav);
            function movePill(el) {
                if (!pill || window.innerWidth <= 850) return;
                if (!el) { pill.style.opacity = 0; return; }
                pill.style.opacity = 1;
                pill.style.width = el.offsetWidth + 'px';
                pill.style.transform = 'translateX(' + el.offsetLeft + 'px)';
            }
            links.forEach(function (a) {
                a.addEventListener('mouseenter', function () { movePill(a); });
                a.addEventListener('focus', function () { movePill(a); });
            });
            nav.addEventListener('mouseleave', function () { movePill($('.nav-link.active', nav)); });
            window.addEventListener('resize', function () { movePill($('.nav-link.active', nav)); });
            movePill($('.nav-link.active', nav));
            setTimeout(function () { if (pill) pill.classList.add('ready'); }, 80);

            /* ---------- Theme toggle ---------- */
            var themeBtn = $('#themeToggle');
            themeBtn.setAttribute('aria-pressed', root.getAttribute('data-theme') === 'dark');
            themeBtn.addEventListener('click', function () {
                var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                store('sc_theme', next);
                themeBtn.setAttribute('aria-pressed', next === 'dark');
            });

            /* ---------- Announcement bar: rotate + dismiss ---------- */
            var items = $$('.announcement-item'), idx = 0;
            if (items.length > 1 && !reduce) {
                setInterval(function () {
                    items[idx].classList.remove('is-active');
                    idx = (idx + 1) % items.length;
                    items[idx].classList.add('is-active');
                }, 4500);
            }
            var bar = $('#announcementBar');
            $('#barClose').addEventListener('click', function () {
                bar.classList.add('is-closing');
                store('sc_bar_dismissed', '1');
                setTimeout(function () { root.classList.add('bar-dismissed'); }, 450);
            });

            /* ---------- Reveal on scroll (+ stat counters) ---------- */
            $$('main > section:not(:first-child), .newsletter-inner, .footer-main > *').forEach(function (el, i) {
                el.classList.add('reveal');
                if (el.parentNode.classList.contains('footer-main')) el.style.setProperty('--i', $$(':scope > *', el.parentNode).indexOf(el));
            });
            $$('[data-reveal]').forEach(function (el) { el.classList.add('reveal'); });
            $$('[data-stagger] > *').forEach(function (el, i) { el.classList.add('reveal'); el.style.setProperty('--i', i % 6); });

            function countUp(el) {
                var end = parseFloat(el.dataset.count) || 0, t0 = null, dur = 1400;
                function step(t) {
                    if (!t0) t0 = t;
                    var p = Math.min((t - t0) / dur, 1);
                    el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))).toLocaleString();
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }

            var revealEls = $$('.reveal'), counters = $$('[data-count]');
            if ('IntersectionObserver' in window && !reduce) {
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (!e.isIntersecting) return;
                        e.target.classList.add('in-view');
                        if (e.target.hasAttribute('data-count')) countUp(e.target);
                        io.unobserve(e.target);
                    });
                }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
                revealEls.concat(counters).forEach(function (el) { io.observe(el); });
            } else {
                revealEls.forEach(function (el) { el.classList.add('in-view'); });
                counters.forEach(function (el) { el.textContent = (parseFloat(el.dataset.count) || 0).toLocaleString(); });
            }

            /* ---------- Button ripple ---------- */
            d.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn');
                if (!btn || reduce) return;
                var r = btn.getBoundingClientRect(), size = Math.max(r.width, r.height);
                var s = d.createElement('span');
                s.className = 'ripple';
                s.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (e.clientX - r.left - size / 2) + 'px;top:' + (e.clientY - r.top - size / 2) + 'px';
                btn.appendChild(s);
                setTimeout(function () { s.remove(); }, 650);
            });

            /* ---------- Toasts ---------- */
            function closeToast(t) { t.classList.add('leaving'); setTimeout(function () { t.remove(); }, 350); }
            $$('.toast').forEach(function (t) {
                var c = $('.toast-close', t), p = $('.toast-progress', t);
                if (c) c.addEventListener('click', function () { closeToast(t); });
                if (p) p.addEventListener('animationend', function () { closeToast(t); });
            });

            /* ---------- Newsletter ---------- */
            var form = $('#newsletterForm'), note = $('#newsletterNote');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var input = form.elements.email;
                form.classList.remove('is-invalid', 'is-done');
                if (!input.checkValidity()) {
                    void form.offsetWidth;                  // restart the shake animation
                    form.classList.add('is-invalid');
                    note.textContent = 'Please enter a valid email address.';
                    note.className = 'newsletter-note error';
                    input.focus();
                    return;
                }
                form.classList.add('is-done');
                note.textContent = 'Thank you! Newsletter sign-ups are opening soon.';
                note.className = 'newsletter-note success';
                form.reset();
            });

            /* ---------- Cart badge helper: window.setCartCount(3) ---------- */
            var badge = $('#cartBadge');
            window.setCartCount = function (n) {
                badge.textContent = n;
                badge.hidden = !(n > 0);
                badge.classList.remove('pop'); void badge.offsetWidth; badge.classList.add('pop');
            };
        })();
    </script>

    @stack('scripts')

</body>
</html>