<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Email Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/css/grapes.min.css">
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/grapes.min.css?v0.17.4">
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/less/styles.less') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/plugins/css/grapes.min.css') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
    <script src="{{ url('landing_page_builder/js/grapes.min.js') }}"></script>
    <script src="https://unpkg.com/grapesjs-mjml@0.5.2/dist/grapesjs-mjml.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/grapesjs-plugin-ckeditor@0.0.9/dist/grapesjs-plugin-ckeditor.min.js"></script>

    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-preset-webpage.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-custom-code.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-touch.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-parser-postcss.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-tooltip.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-tui-image-editor.min.js') }}"></script>

    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-blocks-bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.min.js"></script>

    <script src="{{ url('landing_page_builder/js/font-awesome.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>




</head>

<body>
    <style>
        .fancybox-content {
            width: 900px !important;
            height: 600px !important;
        }

        .gjs-dashed span.icon {
            min-height: 1.5rem !important;
            min-width: 1.5rem !important;
            display: block;
        }

        #gjs {
            overflow: hidden;
        }
    </style>
    <div id="gjs">
        <mjml>
            <mj-body background-color="#DCEDF6" width="600px">
                <mj-wrapper padding="0px" background-color="#ffffff">
                    <mj-section>
                        <mj-column>
                            <mj-text font-size="14px" color="rgb(112, 107, 109)"
                                font-family="Arial, Helvetica, sans-serif" font-weight="400" line-height="1.5"
                                font-style="normal" text-decoration="initial">
                                <div
                                    style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:rgb(112, 107, 109);font-weight:400;line-height:1.5;font-style:normal;text-decoration:initial">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    Nisl tincidunt eget nullam non nisi est sit.
                                </div>
                            </mj-text>
                        </mj-column>
                    </mj-section>
                </mj-wrapper>
                <mj-wrapper padding="0px">
                    <mj-section type="footer" version="0.1.0">
                        <mj-column data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                            data-gjs-droppable="false" data-gjs-highlightable="false" data-gjs-hoverable="false"
                            data-gjs-badgable="false" data-gjs-selectable="false">
                            <mj-text data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                                data-gjs-droppable="false" data-gjs-editable="false" data-gjs-hoverable="false"
                                data-gjs-selectable="false" font-size="12px" color="#626262"
                                font-family="Arial, Helvetica, sans-serif" align="center" type="text"
                                padding="0 25px">
                                Copyright &copy; <span class="escala-element-custom-field"
                                    data-field="year">[YEAR]</span> [ALL_RIGHTS_RESERVED]
                            </mj-text>
                            <mj-text data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                                data-gjs-droppable="false" data-gjs-editable="false" data-gjs-hoverable="false"
                                data-gjs-selectable="false" font-size="12px" color="#626262"
                                font-family="Arial, Helvetica, sans-serif" align="center" type="text"
                                padding="0 25px">
                                <span class="escala-element-custom-field"
                                    data-field="company_data">[COMPANY_DATA]</span>
                            </mj-text>
                        </mj-column>
                    </mj-section>
                </mj-wrapper>
            </mj-body>
        </mjml>
    </div>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // localStorage.clear();
            $body = $("body");
            emailBuilderID = "{{ $id }}";
            $template = '';
            $site = "{{ url('/') }}";
            $adminPath = "{{ crocodicstudio\crudbooster\helpers\Crudbooster::adminPath() }}";
            currentHtml = '';
            editor = grapesjs.init({
                clearOnRender: true,
                fromElement: 1,
                showToolbar: 1,
                container: '#gjs',
                storageManager: false,
                fromElement: 0,
                keepUnusedStyles: 1,
                plugins: [
                    'grapesjs-mjml',
                    'gjs-plugin-ckeditor',
                    "grapesjs-touch",
                    "grapesjs-parser-postcss",
                    "grapesjs-tui-image-editor",
                ],
                pluginsOpts: {
                    'grapesjs-mjml': {
                        columnsPadding: ''
                    },
                    'gjs-plugin-ckeditor': {
                        position: 'center',
                        options: {
                            startupFocus: true,
                            extraAllowedContent: '*(*);*{*}', // Allows any class and any inline style
                            allowedContent: true, // Disable auto-formatting, class removing, etc.
                            enterMode: CKEDITOR.ENTER_BR,
                            extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
                            toolbar: [{
                                    name: 'styles',
                                    items: ['Font', 'FontSize']
                                },
                                ['Bold', 'Italic', 'Underline', 'Strike'],
                                {
                                    name: 'paragraph',
                                    items: ['NumberedList', 'BulletedList']
                                },
                                {
                                    name: 'links',
                                    items: ['Link', 'Unlink']
                                },
                                {
                                    name: 'colors',
                                    items: ['TextColor', 'BGColor']
                                },
                            ],
                        }
                    },
                },
                storageManager: {
                    options: {
                        local: {
                            key: 'gjsProjectMjml'
                        }
                    }
                },
                assetManager: {
                    custom: {
                        open(props) {
                            let imageId = props.options.target.ccid;
                            let iframeUrl =
                                `${$site}/filemanager-dialog?type=1&multiple=0&crossdomain=0&popup=0&field_id=${imageId}`;
                            let fancybox = $.fancybox.open({
                                width: 900,
                                height: 600,
                                type: "iframe",
                                src: iframeUrl,
                                autoScale: false,
                                autoDimensions: false,
                                fitToView: false,
                                autoSize: false,
                                afterClose: function() {
                                    closedCommand();
                                },
                            });
                        },
                        close(props) {},
                    },
                },
                styleManager: {
                    clearProperties: 1,
                },
            });

            var mdlClass = 'gjs-mdl-dialog-sm';
            var pnm = editor.Panels;
            var cmdm = editor.Commands;
            var md = editor.Modal;


            // Simple warn notifier
            var origWarn = console.warn;
            toastr.options = {
                closeButton: true,
                preventDuplicates: true,
                showDuration: 250,
                hideDuration: 150
            };
            console.warn = function(msg) {
                toastr.warning(msg);
                origWarn(msg);
            };

            // Beautify tooltips
            var titles = document.querySelectorAll('*[title]');
            for (var i = 0; i < titles.length; i++) {
                var el = titles[i];
                var title = el.getAttribute('title');
                title = title ? title.trim() : '';
                if (!title)
                    break;
                el.setAttribute('data-tooltip', title);
                el.setAttribute('data-tooltip-pos', 'bottom');
                el.setAttribute('title', '');
            }

            //-----------------------------------------------//
            editor.on("storage:start:store", function() {
                $body.addClass("loading");
            });
            editor.on("storage:load:end", function() {
                $body.removeClass("loading");
            });
            editor.on("storage:end", function() {
                $body.removeClass("loading");
            });

            //-----------------------------------------------//
            //---- Get File Manager Photos
            const am = editor.AssetManager;
            //-----------------------------------------------//
            editor.on("storage:end:load", (vars) => {
                if (vars.variables) variables = JSON.parse(vars.variables);
            });
            // set component
            $template = `{!! $template !!}`;
            if ($template != '""' && typeof $template != 'undefined')
                editor.setComponents(`{!! $template !!}`);
            //show modal save..
            editor.Panels.addButton("options", [{
                id: "save",
                className: "fa fa-floppy-o icon-blank",
                command: function(editor1, sender) {
                    editor.store();

                    currentHtml = editor.runCommand('mjml-get-code').html;
                    var mjmlTemplate = editor.getHtml();
                    $.ajax({
                        type: "PUT",
                        url: `${$adminPath}/email_templates/save-template/${emailBuilderID}`,
                        data: {
                            content: currentHtml,
                            template: mjmlTemplate
                        },
                        success: function(response) {},
                        error: function(xhr, status, error) {}
                    });
                },
                attributes: {
                    title: "Save Email Template"
                },
            }, ]);

            editor.Panels.addButton('options', [{
                id: 'preview',
                className: 'fa fa-eye',
                command: 'preview',
                attributes: {
                    title: 'Preview'
                }
            }]);

        });

        function responsive_filemanager_callback(field_id, value) {
            selected = editor.getSelected();
            if (selected.get('type') == 'mj-section') {
                selected.setAttributes({
                    'background-url': value,
                    'background-size': 'cover',
                    'background-repeat': 'no-repeat',
                    'padding': '10px'
                });
            } else {
                $("#" + field_id, $(".gjs-frame").contents()).prop("src", value);
                editor.getSelected().set("src", $site + value);
            }
        }

        function closedCommand() {
            editor.stopCommand("open-assets");
        }
    </script>
</body>

</html>
