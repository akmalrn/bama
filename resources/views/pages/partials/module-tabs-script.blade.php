<script>
    (function() {
        function initializeModuleTabs() {
            const dataCard = document.getElementById('module-data-card');
            const categoryCard = document.getElementById('module-category-card');
            const labelBody = document.getElementById('module-label-card-body') || document.getElementById('product-label-card-body');
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');

            function initCategoryTable() {
                if (document.getElementById('categoryDataTable') && window.DataTable && !DataTable.isDataTable('#categoryDataTable')) {
                    new DataTable('#categoryDataTable');
                }
            }

            function syncModuleCards(button) {
                if (!button) return;
                const target = button.getAttribute('data-bs-target');

                if (dataCard) {
                    dataCard.classList.toggle('d-none', !['#tab-layanan', '#tab-project', '#tab-products', '#tab-blog', '#tab-partner'].includes(target));
                }

                if (categoryCard) {
                    categoryCard.classList.toggle('d-none', target !== '#tab-category');
                    if (target === '#tab-category') {
                        initCategoryTable();
                    }
                }

                if (labelBody) {
                    labelBody.classList.toggle('d-none', target !== '#tab-label');
                }
            }

            tabButtons.forEach(button => button.addEventListener('shown.bs.tab', event => syncModuleCards(event.target)));
            syncModuleCards(document.querySelector('[data-bs-toggle="tab"].active'));
            initCategoryTable();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeModuleTabs, { once: true });
        } else {
            initializeModuleTabs();
        }
    })();
</script>
