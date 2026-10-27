@php($editorId = $id ?? $name ?? 'description')

@once
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <style>
        .cke { max-width: 100%; }
        .cke_contents { min-height: 250px; }
    </style>
    <script>
        (function() {
            const themeStyleId = 'ckeditor-admin-theme';
            const contentThemeStyles = `
                :root { color-scheme: light; }
                html[data-theme="light"] body.cke_editable { background-color: #fff; color: #0f172a; }
                html[data-theme="dark"] { color-scheme: dark; }
                html[data-theme="dark"] body.cke_editable { background-color: #263449; color: #f8fafc; }
                html[data-theme="dark"] body.cke_editable a { color: #93c5fd; }
                html[data-theme="dark"] body.cke_editable blockquote { border-color: #64748b; color: #cbd5e1; }
                html[data-theme="dark"] body.cke_editable :is(th, td) { border-color: #64748b; }
                html[data-theme="dark"] body.cke_editable :is(pre, code) { background-color: #1e293b; color: #e2e8f0; }
                body.cke_editable { transition: background-color .2s ease, color .2s ease; }
                body.cke_editable img,
                body.cke_editable input[type="image"] {
                    max-width: 100% !important;
                    height: auto !important;
                    display: block;
                    margin: 16px auto;
                    box-sizing: border-box;
                    pointer-events: none;
                }
                body.cke_editable a:has(> img),
                body.cke_editable a:has(> input[type="image"]) {
                    pointer-events: none;
                    cursor: default;
                }
                body.cke_editable table { max-width: 100%; }
            `;

            function activeTheme() {
                return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            }

            function applyTheme(editor, theme) {
                const editorDocument = editor.document && editor.document.$;
                if (!editorDocument?.documentElement || !editorDocument.body) return;

                let style = editorDocument.getElementById(themeStyleId);
                if (!style) {
                    style = editorDocument.createElement('style');
                    style.id = themeStyleId;
                    style.textContent = contentThemeStyles;
                    editorDocument.head.appendChild(style);
                }

                editorDocument.documentElement.setAttribute('data-theme', theme);
                editorDocument.body.setAttribute('data-theme', theme);
            }

            window.initializeAdminCkeditor = function(editorId) {
                if (!window.CKEDITOR) return;

                let field = document.getElementById(editorId);
                if (!field) {
                    field = document.querySelector(`textarea[name="${editorId}"]`);
                    if (field && !field.id) {
                        field.id = editorId;
                    }
                }

                if (!field || (CKEDITOR.instances && CKEDITOR.instances[editorId])) return;

                const tabPane = field.closest('.tab-pane');
                if (tabPane && !tabPane.classList.contains('active')) return;

                const allowUploads = editorId === 'description';
                const defaultRemovePlugins = 'forms,save,newpage,preview,print,templates';
                const config = {
                    height: 280,
                    resize_enabled: true,
                    removePlugins: defaultRemovePlugins + (allowUploads ? '' : ',image,uploadimage,uploadfile,filebrowser')
                };

                if (allowUploads) {
                    config.filebrowserUploadUrl = @json(route('dashboard-admin.ckeditor.upload') . '?_token=' . csrf_token());
                    config.filebrowserImageUploadUrl = @json(route('dashboard-admin.ckeditor.upload') . '?_token=' . csrf_token());
                    config.filebrowserUploadMethod = 'form';
                }

                CKEDITOR.replace(field.id || editorId, config);
            };

            // Nonaktifkan tab Link pada dialog Gambar agar gambar tidak bisa dijadikan tautan klik,
            // serta atur dimensi gambar agar otomatis responsif dan tidak memaksakan lebar pixel besar.
            CKEDITOR.on('dialogDefinition', function(ev) {
                const dialogName = ev.data.name;
                const dialogDefinition = ev.data.definition;

                if (dialogName === 'image') {
                    dialogDefinition.removeContents('Link');

                    const infoTab = dialogDefinition.getContents('info');
                    if (infoTab) {
                        const txtWidth = infoTab.get('txtWidth');
                        if (txtWidth) {
                            txtWidth['default'] = '';
                        }
                        const txtHeight = infoTab.get('txtHeight');
                        if (txtHeight) {
                            txtHeight['default'] = '';
                        }
                    }

                    const originalOnOk = dialogDefinition.onOk;
                    dialogDefinition.onOk = function() {
                        if (typeof originalOnOk === 'function') {
                            originalOnOk.apply(this, arguments);
                        }
                        const editor = this.getParentEditor();
                        setTimeout(function() {
                            const element = editor.getSelection().getSelectedElement();
                            if (element && element.getName() === 'img') {
                                element.removeStyle('width');
                                element.removeStyle('height');
                                element.removeAttribute('width');
                                element.removeAttribute('height');
                                element.setStyle('max-width', '100%');
                                element.setStyle('height', 'auto');
                            }
                        }, 50);
                    };
                }
            });

            CKEDITOR.on('instanceReady', function(event) {
                applyTheme(event.editor, activeTheme());
                event.editor.on('mode', function() {
                    if (event.editor.mode === 'wysiwyg') {
                        window.setTimeout(() => applyTheme(event.editor, activeTheme()), 0);
                    }
                });
            });

            window.addEventListener('admin:themechange', function(event) {
                const requestedTheme = event.detail?.theme;
                const theme = ['dark', 'light'].includes(requestedTheme) ? requestedTheme : activeTheme();
                Object.values(CKEDITOR.instances || {}).forEach(editor => applyTheme(editor, theme));
            });

            document.addEventListener('submit', function() {
                if (window.CKEDITOR && CKEDITOR.instances) {
                    for (const name in CKEDITOR.instances) {
                        if (Object.prototype.hasOwnProperty.call(CKEDITOR.instances, name)) {
                            CKEDITOR.instances[name].updateElement();
                        }
                    }
                }
            }, true);

            function initializeVisibleEditors() {
                (window.adminCkeditorFields || []).forEach(window.initializeAdminCkeditor);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeVisibleEditors);
            } else {
                initializeVisibleEditors();
            }

            // Pastikan SweetAlert2 tersedia & arahkan window.alert ke SweetAlert2
            if (!window.Swal) {
                const swalScript = document.createElement('script');
                swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                document.head.appendChild(swalScript);
            }

            if (!window._swalAlertHooked) {
                window._swalAlertHooked = true;
                const originalAlert = window.alert;
                window.alert = function(msg) {
                    if (window.Swal) {
                        window.Swal.fire({
                            icon: 'info',
                            title: 'Pemberitahuan',
                            text: msg,
                            confirmButtonColor: '#28166f',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        originalAlert(msg);
                    }
                };
            }
        })();
    </script>
@endonce

<script>
    window.adminCkeditorFields = window.adminCkeditorFields || [];
    if (!window.adminCkeditorFields.includes(@json($editorId))) {
        window.adminCkeditorFields.push(@json($editorId));
    }
    if (typeof window.initializeAdminCkeditor === 'function') {
        window.setTimeout(() => window.initializeAdminCkeditor(@json($editorId)), 0);
    }
</script>
