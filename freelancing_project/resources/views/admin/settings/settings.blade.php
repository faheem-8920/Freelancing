@extends('admin.layouts.adminlayout')

@section('content')

@php
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

    $settings = array_merge($defaults, $settings ?? []);

    $colorSettings = [
        'primary_color' => 'Primary Color',
        'secondary_color' => 'Secondary Color',
        'accent_color' => 'Accent Color',
        'background_color' => 'Website Background',
        'text_color' => 'Body Text Color',
        'heading_color' => 'Heading Color',

        'button_color' => 'Button Background',
        'button_text_color' => 'Button Text',
        'button_hover_color' => 'Button Hover Background',
        'button_hover_text_color' => 'Button Hover Text',

        'link_color' => 'Link Color',
        'link_hover_color' => 'Link Hover Color',

        'border_color' => 'Border Color',
        'footer_bg_color' => 'Footer Background',
        'footer_text_color' => 'Footer Text',
        'animation_color' => 'Animation Color',
    ];
@endphp

<style>
    .settings-page {
        color: #493a32;
    }

    .settings-heading {
        margin-bottom: 24px;
    }

    .settings-heading h2 {
        font-size: 27px;
        font-weight: 700;
        color: #38271f;
        margin-bottom: 6px;
    }

    .settings-heading p {
        color: #84766d;
        margin: 0;
    }

    .settings-card {
        background: #fff;
        border: 1px solid #eadfd3;
        border-radius: 12px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .settings-card-header {
        padding: 18px 22px;
        background: #fbf7f1;
        border-bottom: 1px solid #eadfd3;
    }

    .settings-card-header h5 {
        margin: 0;
        color: #59392b;
        font-size: 17px;
        font-weight: 700;
    }

    .settings-card-header p {
        margin: 5px 0 0;
        color: #84766d;
        font-size: 13px;
    }

    .settings-card-body {
        padding: 24px;
    }

    .settings-page .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #493a32;
        margin-bottom: 8px;
    }

    .settings-page .form-control,
    .settings-page .form-select {
        border-color: #e5d8cb;
        border-radius: 8px;
        min-height: 43px;
        color: #493a32;
    }

    .settings-page textarea.form-control {
        min-height: 100px;
    }

    .settings-page .form-control:focus,
    .settings-page .form-select:focus {
        border-color: #b96f4c;
        box-shadow: 0 0 0 3px rgba(185, 111, 76, 0.12);
    }

    .settings-page .form-text {
        font-size: 12px;
        color: #91837a;
    }

    .color-setting {
        padding: 13px;
        border: 1px solid #eadfd3;
        border-radius: 10px;
        height: 100%;
    }

    .color-setting .form-control-color {
        width: 100%;
        height: 43px;
        padding: 4px;
        cursor: pointer;
    }

    .image-preview {
        display: block;
        max-width: 180px;
        max-height: 90px;
        object-fit: contain;
        border: 1px solid #eadfd3;
        border-radius: 8px;
        padding: 8px;
        margin-top: 12px;
        background: #fffdf9;
    }

    .settings-preview {
        border: 1px solid #eadfd3;
        border-radius: 12px;
        overflow: hidden;
        background: var(--preview-bg, #fffdf9);
        color: var(--preview-text, #493a32);
        transition: background 0.3s, color 0.3s;
    }

    .preview-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 18px 22px;
        background: var(--preview-secondary, #f7efe3);
        border-bottom: 1px solid var(--preview-border, #eadfd3);
    }

    .preview-brand {
        font-weight: 800;
        font-size: 18px;
        color: var(--preview-primary, #59392b);
        overflow-wrap: anywhere;
    }

    .preview-nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 13px;
    }

    .preview-nav-links a {
        color: var(--preview-link, #754832);
        text-decoration: none;
    }

    .preview-hero {
        padding: 34px 22px;
        text-align: center;
        background: var(--preview-bg, #fffdf9);
    }

    .preview-hero h3 {
        font-family: var(--preview-heading-font, Poppins), sans-serif;
        font-size: var(--preview-heading-size, 32px);
        color: var(--preview-heading, #38271f);
        font-weight: 800;
        margin-bottom: 14px;
        overflow-wrap: anywhere;
    }

    .preview-hero p {
        font-family: var(--preview-font, Poppins), sans-serif;
        font-size: var(--preview-font-size, 16px);
        color: var(--preview-text, #493a32);
        margin-bottom: 22px;
        overflow-wrap: anywhere;
    }

    .preview-button {
        display: inline-block;
        padding: 11px 22px;
        border: 1px solid var(--preview-button, #59392b);
        border-radius: var(--preview-radius, 10px);
        background: var(--preview-button, #59392b);
        color: var(--preview-button-text, #ffffff);
        font-family: var(--preview-font, Poppins), sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition:
            transform var(--preview-speed, 300ms) ease,
            background var(--preview-speed, 300ms) ease,
            color var(--preview-speed, 300ms) ease,
            box-shadow var(--preview-speed, 300ms) ease;
    }

    .preview-button:hover {
        background: var(--preview-button-hover, #b96f4c);
        color: var(--preview-button-hover-text, #ffffff);
        border-color: var(--preview-button-hover, #b96f4c);
    }

    .preview-button.effect-lift:hover {
        transform: translateY(-4px);
    }

    .preview-button.effect-scale:hover {
        transform: scale(1.05);
    }

    .preview-button.effect-glow:hover {
        box-shadow: 0 0 18px var(--preview-animation, #b96f4c);
    }

    .preview-button.effect-none:hover {
        transform: none;
        box-shadow: none;
    }

    .preview-footer {
        padding: 17px 22px;
        background: var(--preview-footer-bg, #38271f);
        color: var(--preview-footer-text, #fffdf9);
        text-align: center;
        font-size: 13px;
    }

    .settings-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 20px 0 30px;
    }

    .btn-save-settings {
        background: #59392b;
        color: #fff;
        border: 1px solid #59392b;
        border-radius: 8px;
        padding: 11px 22px;
        font-weight: 600;
    }

    .btn-save-settings:hover {
        background: #754832;
        color: #fff;
    }

    .btn-reset-settings {
        background: #fff;
        color: #59392b;
        border: 1px solid #d9c8b8;
        border-radius: 8px;
        padding: 11px 18px;
        font-weight: 600;
    }

    .btn-restore-settings {
        background: #a53d32;
        color: #fff;
        border: 1px solid #a53d32;
        border-radius: 8px;
        padding: 11px 18px;
        font-weight: 600;
    }

    .btn-restore-settings:hover {
        background: #852d25;
        color: #fff;
    }

    @media (max-width: 576px) {
        .settings-card-body {
            padding: 16px;
        }

        .settings-card-header {
            padding: 16px;
        }

        .settings-actions {
            flex-direction: column;
        }

        .settings-actions button {
            width: 100%;
        }
    }
</style>

<div class="settings-page">

    <div class="settings-heading">
        <h2>Website Settings</h2>
        <p>Manage your bakery website's branding, theme, typography and homepage content.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="settingsForm"
        action="/admin/settings"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        {{-- GENERAL INFORMATION --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>General Information</h5>
                <p>Basic details about your bakery business.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="site_name" class="form-label">Website / Bakery Name</label>
                        <input
                            type="text"
                            id="site_name"
                            name="site_name"
                            class="form-control"
                            value="{{ old('site_name', $settings['site_name']) }}"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Contact Number</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $settings['phone']) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $settings['email']) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label">Bakery Address</label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            class="form-control"
                            value="{{ old('address', $settings['address']) }}"
                        >
                    </div>

                </div>
            </div>
        </div>

        {{-- BRANDING --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Branding & Images</h5>
                <p>Upload your bakery logo and browser favicon.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="site_logo" class="form-label">Website Logo</label>
                        <input
                            type="file"
                            id="site_logo"
                            name="site_logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.svg"
                        >
                        <div class="form-text">Recommended: transparent PNG or WebP.</div>

                        @if(!empty($settings['site_logo']))
                            <img
                                src="{{ asset('storage/' . $settings['site_logo']) }}"
                                alt="Current website logo"
                                class="image-preview"
                            >
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="site_favicon" class="form-label">Website Favicon</label>
                        <input
                            type="file"
                            id="site_favicon"
                            name="site_favicon"
                            class="form-control"
                            accept=".ico,.png,.jpg,.jpeg,.webp"
                        >
                        <div class="form-text">A square image is recommended.</div>

                        @if(!empty($settings['site_favicon']))
                            <img
                                src="{{ asset('storage/' . $settings['site_favicon']) }}"
                                alt="Current website favicon"
                                class="image-preview"
                            >
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- THEME COLORS --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Theme Colors</h5>
                <p>Customize colors throughout your bakery website.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-3">

                    @foreach($colorSettings as $key => $label)
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="color-setting">
                                <label for="{{ $key }}" class="form-label">
                                    {{ $label }}
                                </label>

                                <input
                                    type="color"
                                    id="{{ $key }}"
                                    name="{{ $key }}"
                                    class="form-control form-control-color w-100 theme-control"
                                    value="{{ old($key, $settings[$key]) }}"
                                    data-preview-key="{{ $key }}"
                                >

                                <div class="form-text color-value">
                                    {{ old($key, $settings[$key]) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- TYPOGRAPHY --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Typography</h5>
                <p>Control website fonts, body text size and heading size.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="font_family" class="form-label">Body Font Family</label>
                        <select
                            id="font_family"
                            name="font_family"
                            class="form-select theme-control"
                        >
                            @foreach([
                                'Poppins',
                                'Roboto',
                                'Open Sans',
                                'Lato',
                                'Montserrat',
                                'Nunito',
                                'Arial',
                                'Georgia',
                                'Playfair Display'
                            ] as $font)
                                <option
                                    value="{{ $font }}"
                                    {{ old('font_family', $settings['font_family']) == $font ? 'selected' : '' }}
                                >
                                    {{ $font }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="heading_font_family" class="form-label">Heading Font Family</label>
                        <select
                            id="heading_font_family"
                            name="heading_font_family"
                            class="form-select theme-control"
                        >
                            @foreach([
                                'Poppins',
                                'Roboto',
                                'Open Sans',
                                'Lato',
                                'Montserrat',
                                'Nunito',
                                'Arial',
                                'Georgia',
                                'Playfair Display'
                            ] as $font)
                                <option
                                    value="{{ $font }}"
                                    {{ old('heading_font_family', $settings['heading_font_family']) == $font ? 'selected' : '' }}
                                >
                                    {{ $font }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="font_size" class="form-label">
                            Body Font Size: <span id="fontSizeLabel">{{ old('font_size', $settings['font_size']) }}px</span>
                        </label>

                        <input
                            type="range"
                            id="font_size"
                            name="font_size"
                            class="form-range theme-control"
                            min="12"
                            max="24"
                            step="1"
                            value="{{ old('font_size', $settings['font_size']) }}"
                        >
                        <div class="form-text">Choose between 12px and 24px.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="heading_size" class="form-label">
                            Heading Font Size: <span id="headingSizeLabel">{{ old('heading_size', $settings['heading_size']) }}px</span>
                        </label>

                        <input
                            type="range"
                            id="heading_size"
                            name="heading_size"
                            class="form-range theme-control"
                            min="20"
                            max="60"
                            step="1"
                            value="{{ old('heading_size', $settings['heading_size']) }}"
                        >
                        <div class="form-text">Choose between 20px and 60px.</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- BUTTONS AND EFFECTS --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Buttons & Effects</h5>
                <p>Customize button shapes, hover animations and animation speed.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="button_hover_effect" class="form-label">Button Hover Effect</label>
                        <select
                            id="button_hover_effect"
                            name="button_hover_effect"
                            class="form-select theme-control"
                        >
                            <option value="none" {{ old('button_hover_effect', $settings['button_hover_effect']) == 'none' ? 'selected' : '' }}>
                                None
                            </option>
                            <option value="lift" {{ old('button_hover_effect', $settings['button_hover_effect']) == 'lift' ? 'selected' : '' }}>
                                Lift Up
                            </option>
                            <option value="scale" {{ old('button_hover_effect', $settings['button_hover_effect']) == 'scale' ? 'selected' : '' }}>
                                Scale Up
                            </option>
                            <option value="glow" {{ old('button_hover_effect', $settings['button_hover_effect']) == 'glow' ? 'selected' : '' }}>
                                Glow
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="border_radius" class="form-label">
                            Border Radius: <span id="borderRadiusLabel">{{ old('border_radius', $settings['border_radius']) }}px</span>
                        </label>

                        <input
                            type="range"
                            id="border_radius"
                            name="border_radius"
                            class="form-range theme-control"
                            min="0"
                            max="30"
                            step="1"
                            value="{{ old('border_radius', $settings['border_radius']) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="animation_speed" class="form-label">
                            Animation Speed: <span id="animationSpeedLabel">{{ old('animation_speed', $settings['animation_speed']) }}ms</span>
                        </label>

                        <input
                            type="range"
                            id="animation_speed"
                            name="animation_speed"
                            class="form-range theme-control"
                            min="100"
                            max="1000"
                            step="50"
                            value="{{ old('animation_speed', $settings['animation_speed']) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="animations_enabled" class="form-label">Website Animations</label>
                        <select
                            id="animations_enabled"
                            name="animations_enabled"
                            class="form-select theme-control"
                        >
                            <option value="1" {{ old('animations_enabled', $settings['animations_enabled']) == '1' ? 'selected' : '' }}>
                                Enabled
                            </option>
                            <option value="0" {{ old('animations_enabled', $settings['animations_enabled']) == '0' ? 'selected' : '' }}>
                                Disabled
                            </option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        {{-- HOMEPAGE CONTENT --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Homepage Content</h5>
                <p>Edit the main heading and description displayed on the homepage.</p>
            </div>

            <div class="settings-card-body">
                <div class="row g-4">

                    <div class="col-12">
                        <label for="hero_title" class="form-label">Hero Heading</label>
                        <input
                            type="text"
                            id="hero_title"
                            name="hero_title"
                            class="form-control theme-control"
                            value="{{ old('hero_title', $settings['hero_title']) }}"
                        >
                    </div>

                    <div class="col-12">
                        <label for="hero_description" class="form-label">Hero Description</label>
                        <textarea
                            id="hero_description"
                            name="hero_description"
                            class="form-control theme-control"
                            rows="4"
                        >{{ old('hero_description', $settings['hero_description']) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- LIVE PREVIEW --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <h5>Live Theme Preview</h5>
                <p>Change the controls above to see your selections in this preview before saving.</p>
            </div>

            <div class="settings-card-body">

                <div class="settings-preview" id="settingsPreview">

                    <div class="preview-navbar">
                        <div class="preview-brand" id="previewSiteName">
                            {{ old('site_name', $settings['site_name']) }}
                        </div>

                        <nav class="preview-nav-links">
                            <a href="#previewHome" id="previewLinkOne">Home</a>
                            <a href="#previewProducts" id="previewLinkTwo">Products</a>
                            <a href="#previewContact" id="previewLinkThree">Contact</a>
                        </nav>
                    </div>

                    <div class="preview-hero" id="previewHome">
                        <h3 id="previewHeroTitle">
                            {{ old('hero_title', $settings['hero_title']) }}
                        </h3>

                        <p id="previewHeroDescription">
                            {{ old('hero_description', $settings['hero_description']) }}
                        </p>

                        <button
                            type="button"
                            class="preview-button"
                            id="previewButton"
                        >
                            Explore Our Bakery
                        </button>
                    </div>

                    <div class="preview-footer" id="previewContact">
                        <span id="previewFooterText">
                            {{ old('site_name', $settings['site_name']) }}
                        </span>
                        &mdash; Freshly baked with love.
                    </div>

                </div>

                <p class="form-text mt-3 mb-0">
                    This is a sample preview. The actual customer-facing website must also load these saved settings.
                </p>

            </div>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="settings-actions">

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-save-settings">
                    <i class="bi bi-check-circle me-1"></i>
                    Save All Settings
                </button>

                <button type="reset" class="btn btn-reset-settings" id="resetFormButton">
                    Reset Form
                </button>
            </div>

            <button
                type="button"
                class="btn btn-restore-settings"
                id="restoreDefaultsButton"
            >
                Restore Theme Defaults
            </button>

        </div>

    </form>

    {{-- SEPARATE RESTORE DEFAULTS FORM --}}
    <form
        id="restoreDefaultsForm"
        action="/admin/settings/reset"
        method="POST"
        class="d-none"
    >
        @csrf
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('settingsForm');
    const preview = document.getElementById('settingsPreview');

    const colorMap = {
        primary_color: '--preview-primary',
        secondary_color: '--preview-secondary',
        accent_color: '--preview-animation',
        background_color: '--preview-bg',
        text_color: '--preview-text',
        heading_color: '--preview-heading',

        button_color: '--preview-button',
        button_text_color: '--preview-button-text',
        button_hover_color: '--preview-button-hover',
        button_hover_text_color: '--preview-button-hover-text',

        link_color: '--preview-link',
        border_color: '--preview-border',

        footer_bg_color: '--preview-footer-bg',
        footer_text_color: '--preview-footer-text',
        animation_color: '--preview-animation'
    };

    function getValue(id, fallback = '') {
        const element = document.getElementById(id);
        return element ? element.value : fallback;
    }

    function setPreviewColor(key) {
        const element = document.getElementById(key);

        if (!element || !colorMap[key]) {
            return;
        }

        preview.style.setProperty(colorMap[key], element.value);

        const valueLabel = element.closest('.color-setting')
            ?.querySelector('.color-value');

        if (valueLabel) {
            valueLabel.textContent = element.value;
        }
    }

    function updatePreview() {

        if (!preview) {
            return;
        }

        // Theme colors
        Object.keys(colorMap).forEach(function (key) {
            setPreviewColor(key);
        });

        // Site name
        const siteName = getValue('site_name', 'Sweet Crumbs Bakery');

        document.getElementById('previewSiteName').textContent = siteName;
        document.getElementById('previewFooterText').textContent = siteName;

        // Homepage content
        document.getElementById('previewHeroTitle').textContent =
            getValue('hero_title', 'Freshly Baked, Made with Love');

        document.getElementById('previewHeroDescription').textContent =
            getValue(
                'hero_description',
                'Delicious cakes, pastries and treats made fresh every day.'
            );

        // Typography
        const bodyFont = getValue('font_family', 'Poppins');
        const headingFont = getValue('heading_font_family', 'Poppins');

        preview.style.setProperty('--preview-font', '"' + bodyFont + '"');
        preview.style.setProperty('--preview-heading-font', '"' + headingFont + '"');

        const fontSize = getValue('font_size', '16');
        const headingSize = getValue('heading_size', '32');
        const borderRadius = getValue('border_radius', '10');
        const animationSpeed = getValue('animation_speed', '300');

        preview.style.setProperty('--preview-font-size', fontSize + 'px');
        preview.style.setProperty('--preview-heading-size', headingSize + 'px');
        preview.style.setProperty('--preview-radius', borderRadius + 'px');
        preview.style.setProperty('--preview-speed', animationSpeed + 'ms');

        // Range labels
        document.getElementById('fontSizeLabel').textContent = fontSize + 'px';
        document.getElementById('headingSizeLabel').textContent = headingSize + 'px';
        document.getElementById('borderRadiusLabel').textContent = borderRadius + 'px';
        document.getElementById('animationSpeedLabel').textContent = animationSpeed + 'ms';

        // Button hover effect
        const button = document.getElementById('previewButton');

        button.classList.remove(
            'effect-none',
            'effect-lift',
            'effect-scale',
            'effect-glow'
        );

        const effect = getValue('button_hover_effect', 'lift');
        button.classList.add('effect-' + effect);

        // Disable preview button animations if animations are disabled
        const animationsEnabled = getValue('animations_enabled', '1') === '1';

        if (animationsEnabled) {
            preview.style.setProperty('--preview-speed', animationSpeed + 'ms');
        } else {
            preview.style.setProperty('--preview-speed', '0ms');
        }

        // Link hover color
        const linkHoverColor = getValue('link_hover_color', '#b96f4c');

        ['previewLinkOne', 'previewLinkTwo', 'previewLinkThree'].forEach(function (id) {
            const link = document.getElementById(id);

            link.onmouseenter = function () {
                link.style.color = linkHoverColor;
            };

            link.onmouseleave = function () {
                link.style.color = getValue('link_color', '#754832');
            };
        });
    }

    // Update preview when any relevant input changes
    form.querySelectorAll('input, select, textarea').forEach(function (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    // Reset Form restores the initial page values and refreshes the preview.
    form.addEventListener('reset', function () {
        setTimeout(function () {
            updatePreview();
        }, 0);
    });

    // Restore Theme Defaults is a separate server-side action.
    document.getElementById('restoreDefaultsButton').addEventListener('click', function () {

        const confirmed = confirm(
            'Restore the default website settings? Your current saved theme and content settings will be replaced. Products, categories and orders will not be deleted.'
        );

        if (confirmed) {
            document.getElementById('restoreDefaultsForm').submit();
        }
    });

    // Initial preview
    updatePreview();

});
</script>

@endsection
