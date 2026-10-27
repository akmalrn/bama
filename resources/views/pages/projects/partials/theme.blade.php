@include('pages.services.partials.theme')

<style>
    .project-form-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .project-form-nav__item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        color: #4B5563;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .project-form-nav__item:hover {
        background: #F3F4F6;
        color: #1F2937;
    }

    .project-form-nav__item.is-active {
        background: #5445BD;
        border-color: #5445BD;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(84, 69, 189, 0.25);
    }

    .project-status-control {
        display: flex;
        min-height: 52px;
        padding: 10px 14px;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        background: #FFFFFF;
    }

    .project-status-control__label strong {
        font-size: .84rem;
        display: block;
    }

    .project-status-control__label small {
        color: #6B7280;
        font-size: .72rem;
    }

    .project-status-control .form-check-input {
        width: 2.65rem;
        height: 1.35rem;
        margin: 0;
        cursor: pointer;
    }

    .project-image-live-preview {
        position: relative;
        display: grid;
        min-height: 110px;
        margin-top: 8px;
        overflow: hidden;
        place-items: center;
        border: 1px dashed #D1D5DB;
        border-radius: 10px;
        background: #F9FAFB;
        color: #6B7280;
    }

    .project-image-live-preview img {
        width: 100%;
        max-height: 140px;
        object-fit: contain;
        padding: 6px;
    }

    .project-image-live-preview__empty {
        display: flex;
        padding: 16px;
        align-items: center;
        flex-direction: column;
        gap: 6px;
        font-size: .75rem;
    }

    .project-repeater-row {
        position: relative;
        padding: 20px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-left: 4px solid #5445BD;
        border-radius: 12px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .project-repeater-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
        margin-top: 20px;
    }

    .project-repeater-heading h6 {
        margin: 0 0 2px;
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
    }

    .project-repeater-heading p {
        margin: 0;
        color: #6B7280;
        font-size: .8rem;
    }
</style>
