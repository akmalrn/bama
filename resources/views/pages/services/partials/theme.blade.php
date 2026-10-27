<style>
    /* ========================================================
       DARK THEME REFINEMENTS - CRUD SERVICE (ADMIN WOWDASH)
       ======================================================== */
    html[data-theme="dark"] .service-admin-page :is(.form-label, .form-check-label) {
        color: #e2e8f0 !important;
        font-weight: 600;
    }

    html[data-theme="dark"] .service-admin-page .form-label .text-danger {
        color: #f87171 !important;
    }

    /* CARD WRAPPER */
    html[data-theme="dark"] .service-admin-page .card,
    html[data-theme="dark"] .service-admin-page .service-form-card {
        background-color: var(--admin-surface, #1e293b) !important;
        border-color: var(--admin-border, rgba(148, 163, 184, 0.2)) !important;
        color: var(--admin-text, #f8fafc) !important;
    }

    html[data-theme="dark"] .service-admin-page .card-header {
        background-color: var(--admin-surface-raised, #263449) !important;
        border-bottom-color: var(--admin-border, rgba(148, 163, 184, 0.2)) !important;
        color: var(--admin-text, #f8fafc) !important;
    }

    html[data-theme="dark"] .service-admin-page .card-title {
        color: #f8fafc !important;
    }

    /* FORM CONTROLS & INPUTS */
    html[data-theme="dark"] .service-admin-page :is(.form-control, .form-select, textarea) {
        background-color: #172334 !important;
        border-color: rgba(148, 163, 184, 0.28) !important;
        color: #f8fafc !important;
    }

    html[data-theme="dark"] .service-admin-page :is(.form-control, .form-select, textarea):focus {
        background-color: #1c2a3d !important;
        border-color: var(--admin-focus, #60a5fa) !important;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.22) !important;
        color: #ffffff !important;
    }

    html[data-theme="dark"] .service-admin-page :is(.form-control, textarea)::placeholder {
        color: #64748b !important;
        opacity: 0.85 !important;
    }

    /* REPEATER ITEM BOX */
    html[data-theme="dark"] .service-admin-page .service-repeater-item-box {
        background-color: var(--admin-surface-raised, #263449) !important;
        border: 1px solid rgba(148, 163, 184, 0.25) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    html[data-theme="dark"] .service-admin-page .service-repeater-item-box .border-bottom {
        border-bottom-color: rgba(148, 163, 184, 0.2) !important;
    }

    /* FEATURE / KEUNGGULAN BOX (GAMBAR 3) */
    html[data-theme="dark"] .service-admin-page .service-feature-box,
    html[data-theme="dark"] .service-admin-page .dark-bg-card {
        background-color: #172436 !important;
        border: 1px solid rgba(148, 163, 184, 0.28) !important;
    }

    html[data-theme="dark"] .service-admin-page .service-feature-box :is(.form-control, textarea),
    html[data-theme="dark"] .service-admin-page .dark-bg-card :is(.form-control, textarea) {
        background-color: #101a26 !important;
        border-color: rgba(148, 163, 184, 0.28) !important;
        color: #f8fafc !important;
    }

    html[data-theme="dark"] .service-admin-page .service-feature-box :is(.form-control, textarea):focus,
    html[data-theme="dark"] .service-admin-page .dark-bg-card :is(.form-control, textarea):focus {
        background-color: #142030 !important;
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.22) !important;
    }

    /* PREVIEWS & ICONS */
    html[data-theme="dark"] .service-admin-page #preview_icon,
    html[data-theme="dark"] .service-admin-page .service-main-image-preview,
    html[data-theme="dark"] .service-admin-page .service-gallery-preview {
        background-color: #111c2a !important;
        border: 1px dashed rgba(148, 163, 184, 0.3) !important;
        color: #94a3b8 !important;
    }

    /* FILE INPUTS */
    html[data-theme="dark"] .service-admin-page .form-control[type="file"]::file-selector-button {
        border-inline-end-color: rgba(148, 163, 184, 0.28) !important;
        background-color: #2b394e !important;
        color: #f1f5f9 !important;
    }

    html[data-theme="dark"] .service-admin-page .form-control[type="file"]:hover::file-selector-button {
        background-color: #3b4d66 !important;
        color: #ffffff !important;
    }

    /* TABS */
    html[data-theme="dark"] .service-admin-page .nav-tabs {
        --bs-nav-tabs-border-color: var(--admin-border);
        border-bottom-color: var(--admin-border);
    }

    html[data-theme="dark"] .service-admin-page .nav-tabs .nav-link {
        border-color: transparent;
        background-color: transparent;
        color: var(--admin-muted);
    }

    html[data-theme="dark"] .service-admin-page .nav-tabs .nav-link:hover,
    html[data-theme="dark"] .service-admin-page .nav-tabs .nav-link:focus-visible {
        border-color: var(--admin-border);
        background-color: rgba(96, 165, 250, 0.08);
        color: var(--admin-text);
    }

    html[data-theme="dark"] .service-admin-page .nav-tabs .nav-link.active {
        border-color: var(--admin-border) var(--admin-border) var(--admin-surface-raised);
        background-color: var(--admin-surface-raised);
        color: #ffffff;
    }

    /* REPEATER ROW & HELP */
    html[data-theme="dark"] .service-admin-page .service-repeater-row {
        border-color: var(--admin-border);
        background-color: var(--admin-surface-raised);
        color: var(--admin-muted);
    }

    html[data-theme="dark"] .service-admin-page .service-form-help {
        color: #94a3b8;
    }

    html[data-theme="dark"] .service-admin-page .input-group-text {
        border-color: rgba(148, 163, 184, 0.28);
        background-color: var(--admin-surface);
        color: var(--admin-muted);
    }

    /* BUTTONS */
    html[data-theme="dark"] .service-admin-page .btn-light {
        border-color: var(--admin-border) !important;
        background-color: var(--admin-surface-raised) !important;
        color: var(--admin-text) !important;
    }

    html[data-theme="dark"] .service-admin-page .btn-light:hover,
    html[data-theme="dark"] .service-admin-page .btn-light:focus-visible {
        border-color: #64748b !important;
        background-color: #334155 !important;
        color: #ffffff !important;
    }

    html[data-theme="dark"] .service-admin-page .btn-outline-primary {
        border-color: #60a5fa;
        color: #93c5fd;
    }

    html[data-theme="dark"] .service-admin-page .btn-outline-primary:hover {
        border-color: #3b82f6;
        background-color: #2563eb;
        color: #ffffff;
    }

    html[data-theme="dark"] .service-admin-page .btn-outline-danger {
        border-color: #f87171;
        color: #fca5a5;
    }

    html[data-theme="dark"] .service-admin-page .btn-outline-danger:hover {
        border-color: #ef4444;
        background-color: #dc2626;
        color: #ffffff;
    }

    html[data-theme="dark"] .service-admin-page :is(.invalid-feedback, .text-danger) {
        color: #fca5a5 !important;
    }

    html[data-theme="dark"] .service-admin-page img.border {
        border-color: rgba(148, 163, 184, 0.25) !important;
    }

    /* EMPTY NOTICE */
    html[data-theme="dark"] .service-admin-page .empty-notice {
        color: #94a3b8 !important;
        border: 1px dashed rgba(148, 163, 184, 0.25);
        border-radius: 12px;
        background-color: rgba(17, 28, 42, 0.5);
    }

    /* TABLE & BADGES FOR DARK THEME */
    html[data-theme="dark"] .service-admin-page .table {
        color: #e2e8f0 !important;
        border-color: rgba(148, 163, 184, 0.18) !important;
    }

    html[data-theme="dark"] .service-admin-page .table th,
    html[data-theme="dark"] .service-admin-page .table td {
        border-color: rgba(148, 163, 184, 0.15) !important;
        background-color: transparent !important;
        color: #cbd5e1;
    }

    html[data-theme="dark"] .service-admin-page .table thead,
    html[data-theme="dark"] .service-admin-page .table thead th,
    html[data-theme="dark"] .service-admin-page .table-light {
        background-color: var(--admin-surface-raised, #263449) !important;
        color: #94a3b8 !important;
        border-color: rgba(148, 163, 184, 0.22) !important;
    }

    html[data-theme="dark"] .service-admin-page .table strong,
    html[data-theme="dark"] .service-admin-page .table .text-dark,
    html[data-theme="dark"] .service-admin-page h5,
    html[data-theme="dark"] .service-admin-page h6 {
        color: #f8fafc !important;
    }

    html[data-theme="dark"] .service-admin-page .table .text-muted,
    html[data-theme="dark"] .service-admin-page .text-muted {
        color: #94a3b8 !important;
    }

    html[data-theme="dark"] .service-admin-page .badge.bg-light {
        background-color: #172436 !important;
        color: #e2e8f0 !important;
        border-color: rgba(148, 163, 184, 0.28) !important;
    }

    html[data-theme="dark"] .service-admin-page .badge.bg-purple-subtle {
        background-color: rgba(125, 68, 221, 0.22) !important;
        color: #ddd6fe !important;
        border-color: rgba(125, 68, 221, 0.35) !important;
    }

    html[data-theme="dark"] .service-admin-page .badge.bg-success-subtle {
        background-color: rgba(22, 163, 74, 0.22) !important;
        color: #86efac !important;
        border-color: rgba(22, 163, 74, 0.35) !important;
    }

    html[data-theme="dark"] .service-admin-page .badge.bg-secondary-subtle {
        background-color: rgba(148, 163, 184, 0.22) !important;
        color: #94a3b8 !important;
        border-color: rgba(148, 163, 184, 0.35) !important;
    }
</style>
