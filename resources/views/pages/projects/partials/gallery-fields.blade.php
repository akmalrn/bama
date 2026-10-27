@php
    $projectGalleryImages = old('old_images', isset($data) ? ($data->images ?? []) : []);

    if (is_string($projectGalleryImages)) {
        $projectGalleryImages = json_decode($projectGalleryImages, true) ?: [];
    }

    $projectGalleryImages = is_array($projectGalleryImages) ? $projectGalleryImages : [];
@endphp

<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h6 class="card-title mb-1">Galeri Perbandingan</h6>
                <small class="text-secondary-light">Tambahkan beberapa gambar dokumentasi perbandingan.</small>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="add-project-gallery">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Gambar
            </button>
        </div>

        <div class="card-body">
            <input type="hidden" name="gallery_state_submitted" value="1">

            @if ($projectGalleryImages !== [])
                <div class="project-gallery-existing mb-4" id="project-gallery-existing">
                    @foreach ($projectGalleryImages as $image)
                        <div class="project-gallery-existing-item">
                            <img src="{{ asset($image) }}" alt="Gambar galeri projek">
                            <input type="hidden" name="old_images[]" value="{{ $image }}">
                            <button type="button" class="btn btn-danger btn-sm project-gallery-remove"
                                aria-label="Hapus gambar galeri">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <label class="form-label">Gambar Galeri Baru</label>

            <div id="project-gallery-new-wrapper" class="d-flex flex-column gap-3">
                <div class="project-gallery-new-row">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-3">
                            <div class="project-gallery-input-preview">
                                <span>Preview</span>
                                <img class="d-none" alt="Preview gambar galeri">
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <input type="file" name="images[]"
                                class="form-control project-gallery-input @if ($errors->has('images') || $errors->has('images.*')) is-invalid @endif"
                                accept=".jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-outline-danger w-100 project-gallery-remove-new">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->has('images') || $errors->has('images.*'))
                <div class="text-danger small mt-2">
                    {{ $errors->first('images') ?: $errors->first('images.*') }}
                </div>
            @endif

            <small class="text-secondary-light d-block mt-2">
                Format: JPG, JPEG, PNG, atau WEBP. Gunakan tombol Tambah Gambar untuk menambah field.
            </small>
        </div>
    </div>
</div>

<template id="project-gallery-row-template">
    <div class="project-gallery-new-row">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-3">
                <div class="project-gallery-input-preview">
                    <span>Preview</span>
                    <img class="d-none" alt="Preview gambar galeri">
                </div>
            </div>
            <div class="col-12 col-md-7">
                <input type="file" name="images[]" class="form-control project-gallery-input"
                    accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 project-gallery-remove-new">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</template>

@once
    <style>
        .project-gallery-existing {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 14px;
        }

        .project-gallery-existing-item {
            position: relative;
            overflow: hidden;
            min-height: 120px;
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            background: var(--bs-light);
        }

        .project-gallery-existing-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .project-gallery-new-row {
            padding: 14px;
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            background: var(--bs-light);
        }

        .project-gallery-input-preview {
            display: flex;
            min-height: 105px;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--bs-border-color);
            border-radius: 6px;
            color: var(--bs-secondary-color);
            background: var(--bs-body-bg);
            font-size: 12px;
        }

        .project-gallery-input-preview img {
            width: 100%;
            height: 105px;
            object-fit: cover;
        }

        .project-gallery-remove {
            position: absolute;
            top: 7px;
            right: 7px;
            display: inline-flex;
            width: 32px;
            height: 32px;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        html[data-theme="dark"] .project-gallery-existing-item,
        html[data-theme="dark"] .project-gallery-new-row {
            border-color: var(--admin-border, #334155);
            background-color: var(--admin-surface-raised, #1e293b);
        }

        html[data-theme="dark"] .project-gallery-input-preview {
            border-color: #64748b;
            color: var(--admin-muted, #94a3b8);
            background-color: var(--admin-surface, #0f172a);
        }

        html[data-theme="dark"] .project-gallery-new-row .form-control[type="file"] {
            border-color: #475569;
            background-color: var(--admin-surface, #0f172a);
            color: var(--admin-text, #e2e8f0);
        }

        html[data-theme="dark"] .project-gallery-new-row .form-control[type="file"]::file-selector-button {
            border-inline-end-color: #475569;
            background-color: var(--admin-surface-raised, #1e293b);
            color: var(--admin-muted, #cbd5e1);
        }

        html[data-theme="dark"] .project-gallery-new-row .form-control[type="file"]:hover::file-selector-button {
            background-color: #334155;
            color: #ffffff;
        }

        html[data-theme="dark"] .project-gallery-new-row .form-control[type="file"]:focus {
            border-color: var(--admin-focus, #60a5fa);
            box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.16);
        }

        html[data-theme="dark"] .project-gallery-new-row .btn-outline-danger {
            border-color: #f87171;
            color: #fca5a5;
        }

        html[data-theme="dark"] .project-gallery-new-row .btn-outline-danger:hover,
        html[data-theme="dark"] .project-gallery-new-row .btn-outline-danger:focus-visible {
            border-color: #ef4444;
            background-color: #dc2626;
            color: #ffffff;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-project-gallery');
            const newWrapper = document.getElementById('project-gallery-new-wrapper');
            const rowTemplate = document.getElementById('project-gallery-row-template');

            addButton?.addEventListener('click', function() {
                newWrapper.insertAdjacentHTML('beforeend', rowTemplate.innerHTML);
            });

            document.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.project-gallery-remove');
                const removeNewButton = event.target.closest('.project-gallery-remove-new');

                if (removeButton) {
                    removeButton.closest('.project-gallery-existing-item')?.remove();
                }

                if (removeNewButton) {
                    const row = removeNewButton.closest('.project-gallery-new-row');
                    const previewImage = row?.querySelector('.project-gallery-input-preview img');

                    if (previewImage?.dataset.objectUrl) {
                        URL.revokeObjectURL(previewImage.dataset.objectUrl);
                    }

                    row?.remove();
                }
            });

            document.addEventListener('change', function(event) {
                const galleryInput = event.target.closest('.project-gallery-input');

                if (!galleryInput) {
                    return;
                }

                const row = galleryInput.closest('.project-gallery-new-row');
                const preview = row.querySelector('.project-gallery-input-preview');
                const previewImage = preview.querySelector('img');
                const placeholder = preview.querySelector('span');
                const file = galleryInput.files[0];

                if (previewImage.dataset.objectUrl) {
                    URL.revokeObjectURL(previewImage.dataset.objectUrl);
                    delete previewImage.dataset.objectUrl;
                }

                if (!file || !file.type.startsWith('image/')) {
                    previewImage.removeAttribute('src');
                    previewImage.classList.add('d-none');
                    placeholder.classList.remove('d-none');
                    return;
                }

                const imageUrl = URL.createObjectURL(file);

                previewImage.src = imageUrl;
                previewImage.alt = file.name;
                previewImage.dataset.objectUrl = imageUrl;
                previewImage.classList.remove('d-none');
                placeholder.classList.add('d-none');
            });
        });
    </script>
@endonce
