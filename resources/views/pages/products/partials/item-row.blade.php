@php
$item = (array) $item;
$faq = $type === 'faqs';
@endphp
<div class="service-repeater-row mb-3">
    <input type="hidden" name="{{ $type }}[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
    <div class="row g-3">
        @if ($tabName ?? false)
        <div class="col-md-4"><label class="form-label">Nama Tab</label><input class="form-control"
                name="{{ $type }}[{{ $i }}][tab_name]" value="{{ $item['tab_name'] ?? '' }}"></div>
        @endif
        <div class="col-md-{{ $tabName ?? false ? 8 : 6 }}"><label
                class="form-label">{{ $faq ? 'Pertanyaan' : 'Judul' }} *</label><input class="form-control"
                name="{{ $type }}[{{ $i }}][{{ $faq ? 'question' : 'title' }}]"
                value="{{ $item[$faq ? 'question' : 'title'] ?? '' }}"></div>
        <div class="col-12"><label class="form-label">{{ $faq ? 'Jawaban' : 'Deskripsi' }}</label>
            <textarea class="form-control" rows="3"
                @if ($faq) id="faq-answer-{{ $i }}" @endif
                name="{{ $type }}[{{ $i }}][{{ $faq ? 'answer' : 'description' }}]">{{ $item[$faq ? 'answer' : 'description'] ?? '' }}</textarea>
            @if ($faq)
            @include('components.ckeditor', ['name' => 'faq-answer-' . $i, 'id' => 'faq-answer-' . $i])
            @endif
        </div>
        @if (!$faq)
        <div class="col-md-4" data-product-image-field><label class="form-label">Gambar</label><input type="file" class="form-control"
                name="{{ $type }}[{{ $i }}][image]" accept=".jpg,.jpeg,.png,.webp" data-product-image-input>
            <div class="product-image-live-preview" data-product-image-preview>
                <img src="{{ ($item['image'] ?? null) ? asset($item['image']) : '' }}" class="{{ ($item['image'] ?? null) ? '' : 'd-none' }}" alt="Preview gambar">
                <div class="product-image-live-preview__empty {{ ($item['image'] ?? null) ? 'd-none' : '' }}"><iconify-icon icon="solar:gallery-add-linear"></iconify-icon><span>Preview gambar</span></div>
            </div>
        </div>
        <div class="col-md-4"><label class="form-label">Nama Tombol</label><input class="form-control"
                name="{{ $type }}[{{ $i }}][button_name]"
                value="{{ $item['button_name'] ?? '' }}"></div>
        <div class="col-md-4"><label class="form-label">URL Tombol</label><input class="form-control"
                name="{{ $type }}[{{ $i }}][button_url]"
                value="{{ $item['button_url'] ?? '' }}"></div>
        @endif
        <div class="col-md-3"><label class="form-label">Urutan</label><input type="number" min="0"
                class="form-control" name="{{ $type }}[{{ $i }}][order]"
                value="{{ $item['order'] ?? 0 }}"></div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="product-status-control w-100"><span class="product-status-control__label"><strong>Status</strong><small>Tampilkan item</small></span>
                <div class="form-check form-switch m-0"><input type="hidden" name="{{ $type }}[{{ $i }}][status]" value="0"><input class="form-check-input" type="checkbox" role="switch" name="{{ $type }}[{{ $i }}][status]" value="1" @checked((bool) ($item['status'] ?? true))></div>
            </div>
        </div>
        <div class="col-md-3 ms-auto d-flex align-items-end"><button type="button" class="btn btn-outline-danger w-100"
                data-remove-row>Hapus</button></div>
    </div>
</div>