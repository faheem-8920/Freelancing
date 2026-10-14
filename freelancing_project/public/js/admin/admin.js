/* =========================================
   BAKERY ADMIN - admin.js
   Place at: public/js/admin/admin.js
   Features:
   - Delete confirmation modal
   - Live validation (name / image)
   - Image preview with file info + remove button
   - Character counter + slug preview
   - Unsaved-changes warning
   - Live search + status filter on categories table
   - Toast notifications, click-to-copy slug
   - Count-up stats, auto-dismiss alerts
   - Drag & drop upload, loading states
========================================= */

(function () {
    'use strict';

    const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    const NAME_MIN = 2;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- helpers ---------- */

    function el(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function slugify(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    /* ---------- toasts ---------- */

    function getToastStack() {
        let stack = document.querySelector('.admin-toast-stack');
        if (!stack) {
            stack = el('div', 'admin-toast-stack');
            stack.setAttribute('aria-live', 'polite');
            document.body.appendChild(stack);
        }
        return stack;
    }

    function toast(message, type) {
        type = type || 'info';
        const icons = { success: '✓', error: '!', info: 'i' };

        const item = el('div', 'admin-toast admin-toast-' + type);
        item.appendChild(el('span', 'admin-toast-icon', icons[type] || 'i'));
        item.appendChild(el('span', 'admin-toast-text', message));

        const close = el('button', 'admin-toast-close', '×');
        close.type = 'button';
        close.setAttribute('aria-label', 'Close');
        item.appendChild(close);

        function remove() {
            item.classList.add('is-leaving');
            setTimeout(function () { item.remove(); }, 350);
        }

        close.addEventListener('click', remove);
        getToastStack().appendChild(item);
        setTimeout(remove, 3500);
    }

    window.AdminUI = { toast: toast };

    /* ---------- confirm modal (delete) ---------- */

    const modal = (function () {
        let root, card, titleEl, textEl, previewEl, confirmBtn, cancelBtn;
        let onConfirm = null;
        let lastFocused = null;

        function build() {
            root = el('div', 'admin-modal');
            root.setAttribute('role', 'dialog');
            root.setAttribute('aria-modal', 'true');
            root.setAttribute('aria-hidden', 'true');

            const backdrop = el('div', 'admin-modal-backdrop');
            card = el('div', 'admin-modal-card');

            const icon = el('div', 'admin-modal-icon', '!');
            titleEl = el('h3', 'admin-modal-title');
            titleEl.id = 'adminModalTitle';
            root.setAttribute('aria-labelledby', 'adminModalTitle');
            textEl = el('p', 'admin-modal-text');
            previewEl = el('div', 'admin-modal-preview');

            const actions = el('div', 'admin-modal-actions');
            cancelBtn = el('button', 'category-btn category-btn-secondary', 'Cancel');
            cancelBtn.type = 'button';
            confirmBtn = el('button', 'category-btn admin-btn-danger', 'Delete');
            confirmBtn.type = 'button';
            actions.appendChild(cancelBtn);
            actions.appendChild(confirmBtn);

            card.appendChild(icon);
            card.appendChild(titleEl);
            card.appendChild(textEl);
            card.appendChild(previewEl);
            card.appendChild(actions);
            root.appendChild(backdrop);
            root.appendChild(card);
            document.body.appendChild(root);

            backdrop.addEventListener('click', close);
            cancelBtn.addEventListener('click', close);
            confirmBtn.addEventListener('click', function () {
                const fn = onConfirm;
                confirmBtn.classList.add('is-loading');
                if (fn) fn();
            });

            document.addEventListener('keydown', function (e) {
                if (!root.classList.contains('is-open')) return;

                if (e.key === 'Escape') close();

                if (e.key === 'Tab') {
                    const focusable = [cancelBtn, confirmBtn];
                    const idx = focusable.indexOf(document.activeElement);
                    if (e.shiftKey && idx <= 0) {
                        e.preventDefault();
                        focusable[focusable.length - 1].focus();
                    } else if (!e.shiftKey && idx === focusable.length - 1) {
                        e.preventDefault();
                        focusable[0].focus();
                    }
                }
            });
        }

        function open(opts) {
            if (!root) build();

            lastFocused = document.activeElement;
            titleEl.textContent = opts.title || 'Are you sure?';
            textEl.textContent = opts.text || '';
            confirmBtn.textContent = opts.confirmText || 'Confirm';
            confirmBtn.classList.remove('is-loading');
            onConfirm = opts.onConfirm;

            previewEl.innerHTML = '';
            if (opts.preview) {
                previewEl.appendChild(opts.preview);
                previewEl.style.display = 'flex';
            } else {
                previewEl.style.display = 'none';
            }

            root.classList.add('is-open');
            root.setAttribute('aria-hidden', 'false');
            document.body.classList.add('admin-modal-lock');
            setTimeout(function () { cancelBtn.focus(); }, 50);
        }

        function close() {
            if (!root) return;
            root.classList.remove('is-open');
            root.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('admin-modal-lock');
            onConfirm = null;
            if (lastFocused && lastFocused.focus) lastFocused.focus();
        }

        return { open: open, close: close };
    })();

    window.AdminUI.confirm = modal.open;

    /* ---------- delete category modal ---------- */

    function initDeleteModal() {
        document.querySelectorAll('form[action*="/deletecategory/"]').forEach(function (form) {
            // remove old inline confirm() so only the modal shows
            form.removeAttribute('onsubmit');

            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === '1') return;
                e.preventDefault();

                const row = form.closest('tr');
                const nameEl = row && row.querySelector('.category-name-wrapper strong');
                const countEl = row && row.querySelector('.category-name-wrapper small');
                const imgEl = row && row.querySelector('.category-image img');

                const name = nameEl ? nameEl.textContent.trim() : 'this category';
                const countText = countEl ? countEl.textContent.replace(/\s+/g, ' ').trim() : '';

                const preview = el('div', 'admin-modal-category');
                const thumb = el('div', 'admin-modal-thumb');
                if (imgEl) {
                    const img = el('img');
                    img.src = imgEl.src;
                    img.alt = name;
                    thumb.appendChild(img);
                } else {
                    thumb.textContent = name.charAt(0).toUpperCase();
                }
                const info = el('div');
                info.appendChild(el('strong', '', name));
                if (countText) info.appendChild(el('small', '', countText));
                preview.appendChild(thumb);
                preview.appendChild(info);

                modal.open({
                    title: 'Delete category?',
                    text: 'This action cannot be undone. The category and its image will be removed permanently.',
                    confirmText: 'Yes, delete',
                    preview: preview,
                    onConfirm: function () {
                        form.dataset.confirmed = '1';
                        form.submit();
                    }
                });
            });
        });
    }

    /* ---------- alerts + stats ---------- */

    function initAlerts() {
        document.querySelectorAll('.category-alert').forEach(function (alert) {
            function hide() {
                alert.classList.add('is-hiding');
                setTimeout(function () { alert.remove(); }, 450);
            }

            alert.style.cursor = 'pointer';
            alert.title = 'Click to dismiss';
            alert.addEventListener('click', hide);

            if (alert.classList.contains('category-alert-success')) {
                setTimeout(hide, 5000);
            }
        });
    }

    function initCountUp() {
        if (reduceMotion) return;

        document.querySelectorAll('.category-stat-card h3').forEach(function (node) {
            const target = parseInt(node.textContent.replace(/,/g, ''), 10);
            if (isNaN(target) || target === 0) return;

            const duration = 900;
            const start = performance.now();

            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                node.textContent = Math.round(target * eased).toLocaleString();
                if (progress < 1) requestAnimationFrame(tick);
            }

            node.textContent = '0';
            requestAnimationFrame(tick);
        });
    }

    /* ---------- validation ---------- */

    function setFieldState(field, message) {
        const group = field.closest('.category-form-group');
        if (!group) return;

        // remove server error for this field when user edits
        const serverError = group.querySelector('.field-error:not(.js-field-error)');
        if (serverError) serverError.remove();

        let err = group.querySelector('.js-field-error');

        if (message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            field.setAttribute('aria-invalid', 'true');

            if (!err) {
                err = el('small', 'field-error js-field-error');
                const anchor = field.closest('.category-upload-box') || field;
                anchor.insertAdjacentElement('afterend', err);
            }
            err.textContent = message;
        } else {
            field.classList.remove('is-invalid');
            field.removeAttribute('aria-invalid');
            if (err) err.remove();
            const filled = field.type === 'file' ? field.files.length : field.value;
            if (filled) field.classList.add('is-valid');
            else field.classList.remove('is-valid');
        }
    }

    /* rules come from data-rules="required|min:2|max:255" on each field */
    function parseRules(field) {
        const out = {};
        (field.dataset.rules || '').split('|').forEach(function (rule) {
            if (!rule) return;
            const parts = rule.split(':');
            out[parts[0]] = parts[1] === undefined ? true : parts[1];
        });
        return out;
    }

    /* returns an error message, or '' when valid. Does not touch the UI. */
    function getMessage(field) {
        const rules = parseRules(field);
        const label = field.dataset.label || 'This field';
        const isFile = field.type === 'file';
        const value = isFile ? (field.files.length ? 'file' : '') : field.value.trim();

        if (rules.required && !value) return label + ' is required.';
        if (value && rules.min && value.length < parseInt(rules.min, 10)) {
            return label + ' must be at least ' + rules.min + ' characters.';
        }
        if (value && rules.max && value.length > parseInt(rules.max, 10)) {
            return label + ' cannot exceed ' + rules.max + ' characters.';
        }
        return '';
    }

    function validateField(field) {
        const message = getMessage(field);
        setFieldState(field, message);
        return !message;
    }

    /* ---------- image preview + validation ---------- */

    function initImageUpload(form) {
        const input = form.querySelector('#image');
        const preview = form.querySelector('#imagePreview');
        if (!input || !preview) return { validate: function () { return true; } };

        const img = preview.querySelector('img');
        let objectUrl = null;

        // meta + remove button
        const meta = el('div', 'category-preview-meta');
        const fileName = el('strong');
        const fileInfo = el('small');
        const removeBtn = el('button', 'category-preview-remove', 'Remove image');
        removeBtn.type = 'button';
        meta.appendChild(fileName);
        meta.appendChild(fileInfo);
        meta.appendChild(removeBtn);
        preview.appendChild(meta);

        function clearPreview() {
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
            img.removeAttribute('src');
            preview.style.display = 'none';
        }

        function reset() {
            input.value = '';
            clearPreview();
            setFieldState(input, '');
        }

        function handle() {
            const file = input.files[0];

            if (!file) {
                clearPreview();
                setFieldState(input, '');
                return;
            }

            if (!ALLOWED_TYPES.includes(file.type)) {
                input.value = '';
                clearPreview();
                setFieldState(input, 'Only JPG, PNG or WEBP images are allowed.');
                toast('Unsupported image format.', 'error');
                return;
            }

            if (file.size > MAX_IMAGE_BYTES) {
                input.value = '';
                clearPreview();
                setFieldState(input, 'Image is ' + formatBytes(file.size) + '. Maximum allowed is 2 MB.');
                toast('Image is too large (max 2 MB).', 'error');
                return;
            }

            setFieldState(input, '');
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);
            img.src = objectUrl;
            fileName.textContent = file.name;
            fileInfo.textContent = formatBytes(file.size) + ' · ' + file.type.replace('image/', '').toUpperCase();

            // restart pop animation
            preview.style.display = 'none';
            void preview.offsetWidth;
            preview.style.display = 'block';
        }

        function refresh() {
            form.dispatchEvent(new Event('admin:refresh'));
        }

        input.addEventListener('change', function () { handle(); refresh(); });
        removeBtn.addEventListener('click', function () { reset(); refresh(); });

        // drag & drop
        const box = input.closest('.category-upload-box');
        if (box) {
            ['dragenter', 'dragover'].forEach(function (evt) {
                box.addEventListener(evt, function (e) {
                    e.preventDefault();
                    box.classList.add('is-dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function (evt) {
                box.addEventListener(evt, function (e) {
                    e.preventDefault();
                    box.classList.remove('is-dragover');
                });
            });
            box.addEventListener('drop', function (e) {
                if (!e.dataTransfer.files.length) return;
                input.files = e.dataTransfer.files;
                handle();
                refresh();
            });
        }

        return {
            validate: function () {
                return !input.classList.contains('is-invalid');
            }
        };
    }

    /* ---------- category form (add / edit) ---------- */

    function initCategoryForm(form) {
        const nameInput = form.querySelector('#name');
        const descInput = form.querySelector('#description');
        const submitBtn = form.querySelector('.category-form-buttons button[type="submit"]');
        const requireComplete = form.hasAttribute('data-require-complete');
        let dirty = false;
        let submitting = false;

        // edit page has no data-rules, so give the name field sensible defaults
        if (nameInput) {
            nameInput.dataset.rules = nameInput.dataset.rules || 'required|min:' + NAME_MIN + '|max:255';
            nameInput.dataset.label = nameInput.dataset.label || 'Category name';
        }

        initImageUpload(form);

        const fields = Array.from(form.querySelectorAll('[data-rules]'));

        /* character counters */
        function addCounter(field, max) {
            if (!field) return;
            const counter = el('small', 'category-counter');
            field.insertAdjacentElement('afterend', counter);

            function update() {
                const len = field.value.length;
                counter.textContent = max ? len + ' / ' + max : len + ' characters';
                counter.classList.toggle('is-near', !!max && len >= max * 0.9);
            }

            field.addEventListener('input', update);
            update();
        }

        addCounter(nameInput, 255);
        addCounter(descInput, descInput && descInput.getAttribute('maxlength')
            ? parseInt(descInput.getAttribute('maxlength'), 10) : 0);

        /* slug preview */
        if (nameInput) {
            const slugPreview = el('small', 'category-slug-preview');
            nameInput.insertAdjacentElement('afterend', slugPreview);

            const updateSlug = function () {
                const slug = slugify(nameInput.value);
                slugPreview.innerHTML = '';
                slugPreview.appendChild(document.createTextNode('Slug preview: '));
                slugPreview.appendChild(el('code', '', slug || '—'));
            };

            nameInput.addEventListener('input', updateSlug);
            updateSlug();
        }

        /* live red / green validation */
        fields.forEach(function (field) {
            if (field.type === 'file') return; // handled by the image uploader

            let touched = false;

            field.addEventListener('blur', function () {
                touched = true;
                validateField(field);
            });

            field.addEventListener('input', function () {
                const message = getMessage(field);
                if (touched || field.classList.contains('is-invalid')) {
                    setFieldState(field, message);
                } else if (!message) {
                    setFieldState(field, ''); // turns green as soon as it becomes valid
                }
            });

            // pre-filled values (edit page / old() after a server error)
            if (field.value && !getMessage(field)) setFieldState(field, '');
        });

        /* snapshot used by data-require-change (edit page) */
        function serialize() {
            return Array.from(form.elements).filter(function (f) {
                return f.name && f.name !== '_token' && f.name !== '_method';
            }).map(function (f) {
                if (f.type === 'file') return f.files.length ? f.files[0].name + f.files[0].size : '';
                if (f.type === 'radio' || f.type === 'checkbox') return f.checked ? f.name + ':' + f.value : '';
                return f.value;
            }).join('|');
        }

        const requireChange = form.hasAttribute('data-require-change');
        const initialState = serialize();

        /* progress, live preview and submit button state */
        const bar = form.querySelector('.category-progress-bar span');
        const progressText = form.querySelector('.category-progress-text');
        const hint = form.querySelector('.category-submit-hint');
        const livePreview = form.querySelector('.category-live-preview');

        function updateLivePreview() {
            if (!livePreview) return;

            const nameText = nameInput ? nameInput.value.trim() : '';
            const descText = descInput ? descInput.value.trim() : '';
            const checked = form.querySelector('input[name="is_active"]:checked');
            const isActive = !checked || checked.value === '1';
            const previewImg = form.querySelector('#imagePreview img');
            const src = (previewImg && previewImg.getAttribute('src')) || livePreview.dataset.image || '';

            livePreview.querySelector('[data-live-name]').textContent = nameText || 'Category name';
            livePreview.querySelector('[data-live-desc]').textContent =
                descText ? (descText.length > 70 ? descText.slice(0, 70) + '…' : descText)
                         : 'Your description will appear here.';

            const pill = livePreview.querySelector('[data-live-status]');
            pill.className = 'category-status ' + (isActive ? 'status-active' : 'status-inactive');
            pill.innerHTML = '<span></span> ' + (isActive ? 'Active' : 'Inactive');

            const img = livePreview.querySelector('img');
            const letter = livePreview.querySelector('.category-live-letter');
            letter.textContent = nameText ? nameText.charAt(0).toUpperCase() : 'C';

            if (src) {
                img.src = src;
                img.style.display = 'block';
                letter.style.display = 'none';
            } else {
                img.removeAttribute('src');
                img.style.display = 'none';
                letter.style.display = '';
            }
        }

        function refreshState() {
            let done = 0;

            fields.forEach(function (field) {
                const ok = !getMessage(field);
                if (ok) done++;

                const item = form.querySelector('[data-progress-for="' + field.id + '"]');
                if (item) item.classList.toggle('is-done', ok);
            });

            const total = fields.length;
            const complete = done === total;
            const ready = complete && (!requireChange || serialize() !== initialState);

            if (bar) bar.style.width = (total ? Math.round((done / total) * 100) : 100) + '%';
            if (bar) bar.parentNode.classList.toggle('is-complete', complete);
            if (progressText) progressText.textContent = done + ' of ' + total + ' completed';
            if (hint) hint.classList.toggle('is-hidden', ready);

            if ((requireComplete || requireChange) && submitBtn && !submitting) {
                submitBtn.disabled = !ready;
                submitBtn.title = ready ? '' : (complete ? 'Make a change to enable saving' : 'Complete all fields to enable saving');
            }

            updateLivePreview();
        }

        form.addEventListener('input', refreshState);
        form.addEventListener('change', refreshState);
        form.addEventListener('admin:refresh', refreshState);
        refreshState();

        /* unsaved changes */
        form.addEventListener('input', function () { dirty = true; });
        form.addEventListener('change', function () { dirty = true; });

        window.addEventListener('beforeunload', function (e) {
            if (dirty && !submitting) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        /* submit */
        form.addEventListener('submit', function (e) {
            if (submitting) {
                e.preventDefault();
                return;
            }

            let allValid = true;
            fields.forEach(function (field) {
                if (!validateField(field)) allValid = false;
            });

            if (!allValid) {
                e.preventDefault();
                toast('Please fix the highlighted fields.', 'error');

                const firstBad = form.querySelector('.is-invalid');
                if (firstBad) {
                    firstBad.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
                    if (firstBad.type !== 'file') firstBad.focus({ preventScroll: true });
                }
                return;
            }

            submitting = true;
            if (submitBtn) {
                submitBtn.classList.add('is-loading');
                submitBtn.lastChild.textContent = ' Saving...';
            }
        });

        // we render our own messages instead of the browser's bubbles
        form.setAttribute('novalidate', 'novalidate');
    }

    /* ---------- categories table: search, filter, copy slug ---------- */

    function initTable() {
        const table = document.querySelector('.category-table');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(function (r) {
            return r.querySelector('.category-info');
        });
        if (!rows.length) return;

        const wrapper = table.closest('.category-table-wrapper');

        /* toolbar */
        const toolbar = el('div', 'category-toolbar');

        const searchWrap = el('div', 'category-search');
        searchWrap.appendChild(el('span', 'category-search-icon', '⌕'));
        const search = el('input');
        search.type = 'search';
        search.placeholder = 'Search categories...  ( / )';
        search.setAttribute('aria-label', 'Search categories');
        searchWrap.appendChild(search);

        const filters = el('div', 'category-filters');
        const filterDefs = [
            { key: 'all', label: 'All' },
            { key: 'active', label: 'Active' },
            { key: 'inactive', label: 'Inactive' }
        ];
        let activeFilter = 'all';
        const filterBtns = filterDefs.map(function (f) {
            const b = el('button', 'category-filter-btn' + (f.key === 'all' ? ' is-active' : ''), f.label);
            b.type = 'button';
            b.dataset.filter = f.key;
            filters.appendChild(b);
            return b;
        });

        const result = el('span', 'category-result-count');

        toolbar.appendChild(searchWrap);
        toolbar.appendChild(filters);
        toolbar.appendChild(result);
        wrapper.parentNode.insertBefore(toolbar, wrapper);

        /* no-results row */
        const emptyRow = el('tr', 'category-no-results');
        const emptyCell = el('td');
        emptyCell.colSpan = 5;
        emptyCell.appendChild(el('div', 'category-no-results-box', 'No categories match your search.'));
        emptyRow.appendChild(emptyCell);
        emptyRow.style.display = 'none';
        tbody.appendChild(emptyRow);

        function apply() {
            const q = search.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach(function (row) {
                const name = (row.querySelector('.category-name-wrapper strong') || {}).textContent || '';
                const slug = (row.querySelector('.category-slug') || {}).textContent || '';
                const isActive = !!row.querySelector('.status-active');

                const matchText = !q || name.toLowerCase().includes(q) || slug.toLowerCase().includes(q);
                const matchStatus =
                    activeFilter === 'all' ||
                    (activeFilter === 'active' && isActive) ||
                    (activeFilter === 'inactive' && !isActive);

                const show = matchText && matchStatus;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            emptyRow.style.display = visible === 0 ? '' : 'none';
            result.textContent = 'Showing ' + visible + ' of ' + rows.length + ' on this page';
        }

        search.addEventListener('input', apply);

        filterBtns.forEach(function (b) {
            b.addEventListener('click', function () {
                activeFilter = b.dataset.filter;
                filterBtns.forEach(function (x) { x.classList.toggle('is-active', x === b); });
                apply();
            });
        });

        document.addEventListener('keydown', function (e) {
            const tag = (document.activeElement.tagName || '').toLowerCase();
            if (e.key === '/' && tag !== 'input' && tag !== 'textarea' && tag !== 'select') {
                e.preventDefault();
                search.focus();
            }
            if (e.key === 'Escape' && document.activeElement === search) {
                search.value = '';
                apply();
                search.blur();
            }
        });

        apply();

        /* click slug to copy */
        rows.forEach(function (row) {
            const slugEl = row.querySelector('.category-slug');
            if (!slugEl) return;

            slugEl.style.cursor = 'copy';
            slugEl.title = 'Click to copy';
            slugEl.addEventListener('click', function () {
                const text = slugEl.textContent.trim();
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () {
                        toast('Slug "' + text + '" copied.', 'success');
                    });
                }
            });
        });
    }

    /* ---------- boot ---------- */

    document.addEventListener('DOMContentLoaded', function () {
        initAlerts();
        initCountUp();
        initDeleteModal();
        initTable();
        document.querySelectorAll('form.category-form').forEach(initCategoryForm);
    });
})();