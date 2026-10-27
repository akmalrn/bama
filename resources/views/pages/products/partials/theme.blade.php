@include('pages.services.partials.theme')

<style>
    .product-status-control { display: flex; min-height: 52px; padding: 10px 13px; align-items: center; justify-content: space-between; gap: 12px; border: 1px solid var(--admin-border); border-radius: 10px; background: var(--admin-surface-raised); color: var(--admin-text); }
    .product-status-control__label { display: flex; flex-direction: column; line-height: 1.2; }
    .product-status-control__label strong { font-size: .82rem; }
    .product-status-control__label small { margin-top: 3px; color: var(--admin-muted); font-size: .7rem; }
    .product-status-control .form-check-input { width: 2.65rem; height: 1.35rem; margin: 0; cursor: pointer; }
    .product-image-live-preview { position: relative; display: grid; min-height: 120px; margin-top: 10px; overflow: hidden; place-items: center; border: 1px dashed var(--admin-border); border-radius: 10px; background: var(--admin-surface-raised); color: var(--admin-muted); }
    .product-image-live-preview img { width: 100%; height: 140px; object-fit: cover; }
    .product-image-live-preview__empty { display: flex; padding: 18px; align-items: center; flex-direction: column; gap: 5px; font-size: .75rem; }
    .product-image-live-preview__empty iconify-icon { color: var(--admin-focus); font-size: 30px; }
    .product-swap-box { display: grid; grid-template-columns: minmax(240px, 1fr) minmax(260px, 1fr); padding: 16px; align-items: center; gap: 18px; border: 1px solid var(--admin-border); border-radius: 12px; background: var(--admin-surface-raised); }
    .product-swap-box strong { color: var(--admin-text); }
    .product-swap-box p { color: var(--admin-muted); font-size: .78rem; }
    @media (max-width: 767.98px) { .product-swap-box { grid-template-columns: 1fr; } }
</style>
