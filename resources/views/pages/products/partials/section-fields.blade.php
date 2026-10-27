@php
    $value = fn($field, $default = '') => old($prefix . '.' . $field, data_get($section, $field, $default));
    $isEcosystem = $prefix === 'ecosystem';
@endphp

<div class="row g-3 product-section-fields">
    <div class="{{ $minimal ?? false ? 'col-md-9' : 'col-12' }}">
        <label class="form-label">Judul Section</label>
        <input class="form-control" name="{{ $prefix }}[title]" value="{{ $value('title') }}">
    </div>

    @unless ($minimal ?? false)
        <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" id="{{ $prefix }}-description" rows="4"
                name="{{ $prefix }}[description]">{{ $value('description') }}</textarea>
            @if ($withEditor ?? false)
                @include('components.ckeditor', [
                    'name' => $prefix . '-description',
                    'id' => $prefix . '-description',
                ])
            @endif
        </div>

        @if ($withButton ?? false)
            <div class="col-md-6">
                <label class="form-label">Nama Tombol</label>
                <input class="form-control" name="{{ $prefix }}[button_name]" value="{{ $value('button_name') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">URL Tombol</label>
                <input class="form-control" name="{{ $prefix }}[button_url]" value="{{ $value('button_url') }}">
            </div>
        @endif

        @if ($withImage ?? false)
            <div class="col-12" data-product-image-field>
                <label class="form-label">Gambar Ecosystem</label>
                <input type="file" class="form-control" name="{{ $prefix }}[image]"
                    accept=".jpg,.jpeg,.png,.webp" data-product-image-input>
                <div class="product-image-live-preview" data-product-image-preview>
                    <img src="{{ data_get($section, 'image') ? asset(data_get($section, 'image')) : '' }}" class="{{ data_get($section, 'image') ? '' : 'd-none' }}" alt="Preview Ecosystem">
                    <div class="product-image-live-preview__empty {{ data_get($section, 'image') ? 'd-none' : '' }}"><iconify-icon icon="solar:gallery-add-linear"></iconify-icon><span>Preview gambar ecosystem</span></div>
                </div>
            </div>
        @endif
    @endunless

    <div class="{{ $minimal ?? false ? 'col-md-3' : 'col-md-4' }} d-flex align-items-end">
        <div class="product-section-status w-100">
            <div><strong>Status Section</strong><small>Tampilkan section ini</small></div>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="{{ $prefix }}[status]" value="0">
                <input type="checkbox" class="form-check-input" name="{{ $prefix }}[status]" value="1"
                    @checked((bool) $value('status', true))>
            </div>
        </div>
    </div>
</div>

<style>
    .product-section-status {
        display: flex;
        min-height: 74px;
        padding: 13px 15px;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        background: var(--admin-surface-raised);
    }

    .product-section-status strong,
    .product-section-status small {
        display: block;
    }

    .product-section-status strong {
        color: var(--admin-text);
        font-size: .84rem;
    }

    .product-section-status small {
        margin-top: 2px;
        color: var(--admin-muted);
        font-size: .74rem;
    }
</style>
