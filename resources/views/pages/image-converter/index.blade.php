@extends('layout.layout')

@php
    $title = 'Image Converter';
    $subTitle = 'Bulk Converter ke WebP';
    $maxFileSizeMb = $maxFileSizeKb / 1024;
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h6 class="card-title mb-1">Converter Gambar</h6>
                        <p class="text-secondary-light mb-0">Konversi beberapa gambar sekaligus menjadi WebP tanpa mengubah
                            file asli.</p>
                    </div>
                    <span class="badge bg-primary-50 text-primary-600 px-12 py-8">Kualitas WebP
                        {{ config('image-converter.quality', 80) }}%</span>
                </div>

                <div class="card-body">
                    <form id="converter-form" action="{{ route('dashboard-admin.image-converter.convert') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="converter-images" id="converter-dropzone"
                            class="converter-dropzone d-flex flex-column align-items-center justify-content-center text-center rounded-12 p-24 p-md-40">
                            <iconify-icon icon="solar:gallery-add-outline" class="text-primary-600 mb-12"
                                style="font-size: 48px"></iconify-icon>
                            <span class="fw-semibold text-lg text-primary-light">Pilih atau jatuhkan gambar di sini</span>
                            <span class="text-secondary-light mt-4">JPG, JPEG, PNG · Maks.
                                {{ number_format($maxFileSizeMb, 0) }} MB per file · Maks. {{ $maxFilesPerBatch }}
                                file</span>
                            <span class="btn btn-outline-primary mt-16">Pilih Gambar</span>
                        </label>
                        <input id="converter-images" class="d-none" type="file" name="images[]"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png" multiple>

                        <div id="selection-section" class="d-none mt-24">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-16">
                                <div>
                                    <h6 class="mb-1">Gambar Dipilih</h6>
                                    <span id="selection-summary" class="text-secondary-light text-sm"></span>
                                </div>
                                <button type="button" id="clear-selection" class="btn btn-sm btn-outline-danger">
                                    <iconify-icon icon="solar:trash-bin-trash-outline" class="me-1"></iconify-icon>
                                    Hapus Semua
                                </button>
                            </div>

                            <div class="table-responsive border rounded-12">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Nama File</th>
                                            <th>Ukuran</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected-files"></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-20">
                                <button type="submit" id="convert-all" class="btn btn-primary px-24">
                                    <iconify-icon icon="solar:magic-stick-3-outline" class="me-2"></iconify-icon>
                                    Convert Semua ke WebP
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="results-section" class="col-12 d-none">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h6 class="card-title mb-1">Hasil Konversi</h6>
                        <p id="result-summary" class="text-secondary-light mb-0"></p>
                    </div>
                    <a id="download-all" href="#" class="btn btn-success d-none" data-nprogress-ignore>
                        <iconify-icon icon="solar:archive-down-minimlistic-outline" class="me-2"></iconify-icon>
                        Download Semua (.zip)
                    </a>
                </div>
                <div class="card-body">
                    <div id="result-grid" class="row gy-4"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .converter-dropzone {
            border: 2px dashed var(--primary-300, #a7b7f9);
            background: var(--primary-50, #f5f7ff);
            cursor: pointer;
            transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
        }

        .converter-dropzone:hover,
        .converter-dropzone.is-dragging {
            border-color: var(--primary-600, #487fff);
            background: var(--primary-100, #eaf0ff);
            transform: translateY(-1px);
        }

        [data-theme="dark"] .converter-dropzone {
            background: rgba(72, 127, 255, .08);
        }

        .converter-thumb,
        .converter-result-image {
            width: 72px;
            height: 56px;
            object-fit: contain;
            background: var(--neutral-100, #f5f6fa);
            border: 1px solid var(--neutral-200, #ebecef);
            border-radius: 8px;
        }

        .converter-result-image {
            width: 100%;
            height: 190px;
        }

        .converter-file-name {
            max-width: 320px;
            word-break: break-word;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('converter-form');
            const input = document.getElementById('converter-images');
            const dropzone = document.getElementById('converter-dropzone');
            const selectionSection = document.getElementById('selection-section');
            const selectionSummary = document.getElementById('selection-summary');
            const tableBody = document.getElementById('selected-files');
            const clearButton = document.getElementById('clear-selection');
            const convertButton = document.getElementById('convert-all');
            const resultsSection = document.getElementById('results-section');
            const resultSummary = document.getElementById('result-summary');
            const resultGrid = document.getElementById('result-grid');
            const downloadAll = document.getElementById('download-all');
            const maxFiles = {{ $maxFilesPerBatch }};
            const maxBytes = {{ $maxFileSizeKb }} * 1024;
            const supportedTypes = ['image/jpeg', 'image/png'];
            let selectedFiles = [];
            let objectUrls = [];

            const escapeHtml = (value) => {
                const element = document.createElement('div');
                element.textContent = value ?? '';
                return element.innerHTML;
            };

            const formatBytes = (bytes) => {
                if (!bytes) return '0 KB';
                const units = ['B', 'KB', 'MB', 'GB'];
                const unit = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
                return `${(bytes / Math.pow(1024, unit)).toFixed(unit === 0 ? 0 : 2)} ${units[unit]}`;
            };

            const fileIssue = (file) => {
                if (!supportedTypes.includes(file.type) && !/\.(jpe?g|png)$/i.test(file.name))
                return 'Format tidak didukung';
                if (file.size > maxBytes) return `Melebihi ${formatBytes(maxBytes)}`;
                return null;
            };

            const cleanupObjectUrls = () => {
                objectUrls.forEach(url => URL.revokeObjectURL(url));
                objectUrls = [];
            };

            const renderSelection = () => {
                cleanupObjectUrls();
                tableBody.innerHTML = '';
                selectionSection.classList.toggle('d-none', selectedFiles.length === 0);
                selectionSummary.textContent =
                    `${selectedFiles.length} file · ${formatBytes(selectedFiles.reduce((sum, file) => sum + file.size, 0))}`;

                selectedFiles.forEach((file, index) => {
                    const previewUrl = URL.createObjectURL(file);
                    const issue = fileIssue(file);
                    objectUrls.push(previewUrl);
                    tableBody.insertAdjacentHTML('beforeend', `
                        <tr data-index="${index}">
                            <td><img src="${previewUrl}" class="converter-thumb" alt="Preview ${escapeHtml(file.name)}"></td>
                            <td><div class="converter-file-name fw-medium text-primary-light">${escapeHtml(file.name)}</div></td>
                            <td>${formatBytes(file.size)}</td>
                            <td class="file-status">${issue
                                ? `<span class="badge bg-danger-focus text-danger-main px-12 py-6">${escapeHtml(issue)}</span>`
                                : '<span class="badge bg-warning-focus text-warning-main px-12 py-6">Siap dikonversi</span>'}</td>
                            <td class="text-center">
                                <button type="button" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center remove-file" data-index="${index}" aria-label="Hapus ${escapeHtml(file.name)}">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </button>
                            </td>
                        </tr>`);
                });
            };

            const addFiles = (files) => {
                const incoming = Array.from(files);
                if (selectedFiles.length + incoming.length > maxFiles) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Terlalu banyak file',
                        text: `Maksimal ${maxFiles} gambar dalam satu proses.`
                    });
                }
                selectedFiles = selectedFiles.concat(incoming).slice(0, maxFiles);
                input.value = '';
                renderSelection();
            };

            input.addEventListener('change', () => addFiles(input.files));
            ['dragenter', 'dragover'].forEach(eventName => dropzone.addEventListener(eventName, event => {
                event.preventDefault();
                dropzone.classList.add('is-dragging');
            }));
            ['dragleave', 'drop'].forEach(eventName => dropzone.addEventListener(eventName, event => {
                event.preventDefault();
                dropzone.classList.remove('is-dragging');
            }));
            dropzone.addEventListener('drop', event => addFiles(event.dataTransfer.files));

            tableBody.addEventListener('click', event => {
                const button = event.target.closest('.remove-file');
                if (!button) return;
                selectedFiles.splice(Number(button.dataset.index), 1);
                renderSelection();
            });

            clearButton.addEventListener('click', () => {
                selectedFiles = [];
                renderSelection();
                resultsSection.classList.add('d-none');
            });

            form.addEventListener('submit', async event => {
                event.preventDefault();
                if (selectedFiles.length === 0) return;

                const formData = new FormData();
                formData.append('_token', form.querySelector('[name="_token"]').value);
                selectedFiles.forEach(file => formData.append('images[]', file));
                convertButton.disabled = true;
                convertButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Mengonversi...';
                tableBody.querySelectorAll('.file-status').forEach(cell => {
                    cell.innerHTML =
                        '<span class="badge bg-info-focus text-info-main px-12 py-6">Diproses</span>';
                });

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Konversi tidak dapat diproses.');
                    renderResults(data);
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Konversi gagal',
                        text: error.message
                    });
                    renderSelection();
                } finally {
                    convertButton.disabled = false;
                    convertButton.innerHTML =
                        '<iconify-icon icon="solar:magic-stick-3-outline" class="me-2"></iconify-icon>Convert Semua ke WebP';
                }
            });

            const renderResults = (data) => {
                resultGrid.innerHTML = '';
                resultSummary.textContent = data.message;
                resultsSection.classList.remove('d-none');
                downloadAll.classList.toggle('d-none', !data.download_all_url);
                downloadAll.href = data.download_all_url || '#';

                data.results.forEach((result, index) => {
                    const statusCell = tableBody.querySelector(
                    `tr[data-index="${index}"] .file-status`);
                    const success = result.status === 'success';
                    if (statusCell) statusCell.innerHTML = success ?
                        '<span class="badge bg-success-focus text-success-main px-12 py-6">Berhasil</span>' :
                        `<span class="badge bg-danger-focus text-danger-main px-12 py-6" title="${escapeHtml(result.message)}">Gagal</span>`;

                    const saving = success && result.original_size > 0 ?
                        Math.round((1 - result.output_size / result.original_size) * 100) :
                        null;
                    resultGrid.insertAdjacentHTML('beforeend', `
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded-12 p-16 h-100">
                                ${success
                                    ? `<img src="${result.preview_url}" class="converter-result-image mb-16" alt="${escapeHtml(result.output_name)}">
                                           <h6 class="text-md converter-file-name mb-8">${escapeHtml(result.output_name)}</h6>
                                           <div class="d-flex flex-wrap gap-2 text-sm text-secondary-light mb-16">
                                               <span>${formatBytes(result.output_size)}</span>
                                               ${saving !== null ? `<span>· ${saving >= 0 ? `${saving}% lebih kecil` : 'ukuran bertambah'}</span>` : ''}
                                           </div>
                                           <a href="${result.download_url}" class="btn btn-sm btn-primary w-100" download>
                                               <iconify-icon icon="solar:download-minimalistic-outline" class="me-1"></iconify-icon> Download WebP
                                           </a>`
                                    : `<div class="d-flex align-items-center justify-content-center converter-result-image mb-16">
                                               <iconify-icon icon="solar:danger-triangle-outline" class="text-danger-main" style="font-size: 44px"></iconify-icon>
                                           </div>
                                           <h6 class="text-md converter-file-name mb-8">${escapeHtml(result.name)}</h6>
                                           <p class="text-danger-main text-sm mb-0">${escapeHtml(result.message)}</p>`}
                            </div>
                        </div>`);
                });

                resultsSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            };
        });
    </script>
@endsection
