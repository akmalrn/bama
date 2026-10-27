@php($currentImage = $category->image ?? null)

<div class="col-md-6">
    <label class="form-label" for="category-image">Gambar Kategori</label>
    <input type="file" id="category-image" name="image" accept=".jpg,.jpeg,.png,.webp"
        class="form-control @error('image') is-invalid @enderror"
        aria-describedby="category-image-help">
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div id="category-image-help" class="service-form-help mt-2">
        Format yang didukung: JPG, JPEG, PNG, atau WEBP.
    </div>

    <div class="category-image-preview mt-3" id="category-image-preview">
        <img id="category-image-preview-image"
            src="{{ $currentImage ? asset($currentImage) : '' }}"
            alt="Preview gambar kategori"
            class="{{ $currentImage ? '' : 'd-none' }}">
        <div id="category-image-preview-empty" class="category-image-preview__empty {{ $currentImage ? 'd-none' : '' }}">
            <iconify-icon icon="solar:gallery-add-linear"></iconify-icon>
            <strong>Preview Gambar</strong>
            <span>Pilih gambar untuk melihat preview</span>
        </div>
        <button type="button" id="category-image-preview-remove"
            class="category-image-preview__remove {{ $currentImage ? '' : 'd-none' }}"
            aria-label="Batalkan gambar yang dipilih" title="Batalkan pilihan gambar">
            <iconify-icon icon="solar:close-circle-linear"></iconify-icon>
        </button>
    </div>
</div>

@once
    <style>
        .category-image-preview { position: relative; display: grid; min-height: 210px; overflow: hidden; place-items: center; border: 1px dashed var(--admin-border); border-radius: 12px; background: var(--admin-surface-raised); color: var(--admin-muted); }
        .category-image-preview img { width: 100%; height: 210px; object-fit: cover; }
        .category-image-preview__empty { display: flex; padding: 24px; align-items: center; justify-content: center; flex-direction: column; text-align: center; }
        .category-image-preview__empty iconify-icon { margin-bottom: 8px; color: var(--admin-focus); font-size: 38px; }
        .category-image-preview__empty strong { color: var(--admin-text); font-size: .875rem; }
        .category-image-preview__empty span { margin-top: 3px; font-size: .78rem; }
        .category-image-preview__remove { position: absolute; top: 10px; right: 10px; display: grid; width: 34px; height: 34px; padding: 0; place-items: center; border: 1px solid rgba(255,255,255,.65); border-radius: 50%; background: rgba(15,23,42,.74); color: #fff; cursor: pointer; backdrop-filter: blur(5px); }
        .category-image-preview__remove:hover { background: #dc2626; }
        .category-image-preview__remove iconify-icon { font-size: 21px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('category-image');
            const preview = document.getElementById('category-image-preview-image');
            const empty = document.getElementById('category-image-preview-empty');
            const remove = document.getElementById('category-image-preview-remove');
            if (!input || !preview || !empty || !remove) return;

            const originalSource = preview.getAttribute('src') || '';
            let objectUrl = null;

            const showImage = function (source) {
                preview.src = source;
                preview.classList.remove('d-none');
                empty.classList.add('d-none');
                remove.classList.remove('d-none');
            };

            const showEmpty = function () {
                preview.removeAttribute('src');
                preview.classList.add('d-none');
                empty.classList.remove('d-none');
                remove.classList.add('d-none');
            };

            input.addEventListener('change', function () {
                if (objectUrl) URL.revokeObjectURL(objectUrl);
                const file = input.files?.[0];
                if (!file) { originalSource ? showImage(originalSource) : showEmpty(); return; }
                objectUrl = URL.createObjectURL(file);
                showImage(objectUrl);
            });

            remove.addEventListener('click', function () {
                input.value = '';
                if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
                originalSource ? showImage(originalSource) : showEmpty();
            });
        });
    </script>
@endonce
