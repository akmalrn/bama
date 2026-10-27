@php($iconValue = old('icon', $category->icon ?? ''))

<div class="col-md-6">
    <label class="form-label" for="category-icon">Iconify Icon</label>
    <textarea id="category-icon" name="icon" rows="5"
        placeholder='<iconify-icon icon="mdi:factory" width="32" height="32" style="color: #2563eb;"></iconify-icon>'
        class="form-control font-monospace @error('icon') is-invalid @enderror"
        aria-describedby="category-icon-help">{{ $iconValue }}</textarea>
    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div id="category-icon-help" class="service-form-help mt-2">
        Masukkan markup Iconify lengkap. Atribut seperti <code>width</code>, <code>height</code>, <code>style</code>,
        <code>class</code>, <code>rotate</code>, dan <code>flip</code> dapat digunakan.
    </div>

    <div class="category-icon-preview mt-3" id="category-icon-preview">
        <div class="category-icon-preview__canvas">
            <span id="category-icon-preview-icon"></span>
        </div>
        <div>
            <strong>Preview Icon</strong>
            <span id="category-icon-preview-name">{{ filled($iconValue) ? $iconValue : 'Belum ada icon dipilih' }}</span>
        </div>
    </div>
</div>

@once
    <style>
        .category-icon-preview { display: flex; min-height: 88px; padding: 14px; align-items: center; gap: 14px; border: 1px dashed var(--admin-border); border-radius: 12px; background: var(--admin-surface-raised); color: var(--admin-text); }
        .category-icon-preview__canvas { display: grid; width: 58px; height: 58px; flex: 0 0 58px; place-items: center; border: 1px solid var(--admin-border); border-radius: 12px; background: var(--admin-surface); color: #2563eb; }
        .category-icon-preview__canvas iconify-icon { font-size: 34px; }
        .category-icon-preview strong { display: block; margin-bottom: 3px; font-size: .875rem; }
        .category-icon-preview span { display: block; color: var(--admin-muted); font-size: .78rem; word-break: break-word; }
        html[data-theme="dark"] .category-icon-preview__canvas { color: #93c5fd; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('category-icon');
            const preview = document.getElementById('category-icon-preview-icon');
            const name = document.getElementById('category-icon-preview-name');
            if (!input || !preview || !name) return;

            const updatePreview = function () {
                let value = input.value.trim();
                if (value && !value.toLowerCase().includes('<iconify-icon')) {
                    value = `<iconify-icon icon="${value.replace(/[<>"']/g, '')}"></iconify-icon>`;
                }

                const template = document.createElement('template');
                template.innerHTML = value;
                const icon = template.content.querySelector('iconify-icon');

                if (!icon) {
                    preview.replaceChildren();
                    name.textContent = value ? 'Markup Iconify tidak valid' : 'Belum ada icon dipilih';
                    return;
                }

                [...icon.attributes].forEach(attribute => {
                    if (/^on/i.test(attribute.name) || /javascript:|expression\s*\(|url\s*\(/i.test(attribute.value)) {
                        icon.removeAttribute(attribute.name);
                    }
                });

                preview.replaceChildren(icon);
                name.textContent = icon.getAttribute('icon') || 'Atribut icon belum diisi';
            };

            input.addEventListener('input', updatePreview);
            updatePreview();
        });
    </script>
@endonce
