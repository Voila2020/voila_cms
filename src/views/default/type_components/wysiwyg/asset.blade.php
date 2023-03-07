@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/crudbooster/assets/summernote/summernote.css') }}">
    @if (Crudbooster::getCurrentModule()->path == 'email_templates')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/css/grapes.min.css">
    @endif
    <style>
        #model_scrach .modal-dialog {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #model_scrach .modal-content {
            height: auto;
            min-height: 100%;
            border-radius: 0;
        }
    </style>
@endpush
@push('bottom')
    <script type="text/javascript" src="{{ asset('vendor/crudbooster/assets/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('vendor/crudbooster/summernote-cleaner.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/v62njx9127wlchyml53il7iulsly6idz61l60t9d629j33pt/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    @if (Crudbooster::getCurrentModule()->path == 'email_templates')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/grapes.min.js"></script>
        <script src=""></script>
        <script src="https://unpkg.com/grapesjs-mjml@0.5.2/dist/grapesjs-mjml.min.js"></script>
        <script>
            var editor = grapesjs.init({
                clearOnRender: true,
                fromElement: 1,
                container: '#gjs',
                storageManager: false,
                plugins: ['grapesjs-mjml'],
                pluginsOpts: {
                    'grapesjs-mjml': {
                        columnsPadding: ''
                    }
                }
            });
            //show modal save..
            editor.Panels.addButton("options", [{
                id: "save",
                className: "fa fa-floppy-o icon-blank",
                command: function(editor1, sender) {
                    editor.store();
                },
                attributes: {
                    title: "Save Email Template"
                },
            }, ]);
        </script>
    @endif


@endpush
