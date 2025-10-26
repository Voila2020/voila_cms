<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Content Builder</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/css/grapes.min.css">
    <link rel="stylesheet/less" type="text/css" href="{{ url('content_builder/less/styles.less') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('content_builder/plugins/css/grapes.min.css') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('content_builder/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    {{-- RichTextEditor Plugin --}}
    <link href="https://unpkg.com/grapesjs-rte-extensions/dist/grapesjs-rte-extensions.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/v62njx9127wlchyml53il7iulsly6idz61l60t9d629j33pt/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}

    <link rel="stylesheet" href="{{ url('content_builder/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ url('content_builder/css/builder.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
    <style>
        .fancybox-content {
            width: 900px !important;
            height: 600px !important;
        }

        .gjs-mdl-dialog {
            margin-top: 30px;
        }

        span#startTour{
            color:#08c;
        }
        /* Force Intro.js popover and overlay to be on top */
        .introjs-overlay {
            z-index: 99999 !important;
        }
        .introjs-helperLayer {
            z-index: 100000 !important;
        }
        .introjs-tooltip {
            z-index: 100001 !important;
        }

        .gjs-pn-panel{
            z-index: 1000 !important;
        }
        .gjs-editor {
            z-index: 1000 !important;
        }
        .gjs-pn-panel.gjs-pn-views{
            z-index: 9999 !important;
        }
        .gjs-mdl-container{
            z-index: 9999 !important;
        }
    </style>


</head>

<body>

    <div id="gjs">
    </div>

    <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" data-backdrop="false"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">Important Notes 📝</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Welcome to the Content Page Builder! Follow these tips to get started and make the most of its
                        features:</p>
                    <ul>
                        <!-- Core Information -->
                        <li>This builder is powered by <strong>Bootstrap 5</strong>, so the overall layout and structure
                            follow its framework.</li>
                        <li><strong>Important:</strong> A main stylesheet is included to keep your design consistent
                            with the website’s theme. Not all edits or custom styles are allowed.</li>

                        <!-- Key Actions -->
                        <li>Use the <strong>Blocks</strong> panel to drag and drop components onto your page.</li>
                        <li>Click any element to open the <strong>Style Manager</strong> for customization.</li>
                        <li>Save your work regularly by clicking the <strong>Save</strong> button. </li>
                        <li>You can <strong>Export</strong> your page as a complete HTML file to create a backup of your
                            work.</li>
                        <li>Use the <strong>Undo</strong> and <strong>Redo</strong> buttons to quickly revert or repeat
                            recent changes.</li>
                        <li><strong>Clear Canvas:</strong> Removes all content from the page. Be sure to save or export
                            before using this action.</li>

                        <!-- Interface Overview -->
                        <li>The builder interface is divided into three main sections:
                            <ul>
                                <li><strong>Sidebar:</strong> Contains three tabs. The first tab provides blocks that
                                    you can add to the canvas and edit.</li>
                                <li><strong>Navigation:</strong> Offers key actions such as Save, Clear, Undo, Redo,
                                    Import, and Export.</li>
                                <li><strong>Canvas:</strong> The editable workspace where you design your content.</li>
                            </ul>
                        </li>

                        <!-- Design & Best Practices -->
                        <li>Pages are fully responsive. Use the preview/responsive mode to test layouts on different
                            screen sizes.</li>
                        <!-- User Roles -->
                        <li>Who can use this builder:
                            <ul>
                                <li><strong>Content Editors:</strong> For a smooth experience, use the ready-made custom
                                    blocks and pre-designed elements to avoid layout issues.</li>
                                <li><strong>Advanced Users:</strong> If you’re familiar with HTML, JavaScript, and
                                    Bootstrap 5, you can fully customize pages and use the <strong>Import</strong>
                                    button to include your own custom HTML.</li>
                            </ul>
                        </li>

                        <!-- Advanced & Security -->
                        <li>Custom code is allowed only within the limits of the theme’s stylesheet and security
                            settings. Avoid inline scripts or unapproved third-party embeds.</li>
                        <li>Regularly export or back up your work to prevent accidental data loss.</li>
                    </ul>


                </div>
                <!-- Modal Footer with Start Tour -->
                <div class="modal-footer d-flex justify-content-between">
                    <span><strong>Want to learn more?</strong>  Take a guided tour of the builder features.</span>
                    <button type="button" class="btn btn-primary" id="startTourBtn">
                        <i class="fa fa-compass"></i> Start Tour
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1"
        data-backdrop="false" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Are you sure you want to save this block?!</h5>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body ">

                    <form id="custom-block-form">
                        <div class="form-outline mb-4">
                            <label class="form-label" for="block_name">Block Name *</label>
                            <input type="text" id="block_name" class="form-control" required />
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="less-modal" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="false"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Theme Colors</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <label>First color</label>
                        </div>
                        <div class="col">
                            <input id="first-input" var="@first-color" class="color-inputs" type="color" readonly
                                disabled>
                            <input type="text" var="@first-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                readonly value="#000000" class="hexcolor" id="hexcolor1" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Second color</label>
                        </div>
                        <div class="col">
                            <input id="second-input" var="@second-color" class="color-inputs" type="color"
                                readonly disabled>
                            <input type="text" var="@second-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                readonly value="#000000" class="hexcolor" id="hexcolor2" disabled><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Third color</label>
                        </div>
                        <div class="col">
                            <input id="third-input" var="@third-color" class="color-inputs" type="color" readonly
                                disabled>
                            <input type="text" var="@third-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                readonly value="#000000" class="hexcolor" id="hexcolor3" disabled><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Fourth color</label>
                        </div>
                        <div class="col">
                            <input id="fourth-input" var="@fourth-color" class="color-inputs" type="color"
                                readonly disabled>
                            <input type="text" var="@fourth-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                readonly value="#000000" class="hexcolor" id="hexcolor4" disabled><br>
                        </div>
                    </div>
                    <div id="colorpicker"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <script>
        $('#first-input').on('input', function() {
            $('#hexcolor1').val(this.value);
        });

        $('#hexcolor1').on('input', function() {
            $('#first-input').val(this.value);
            $(this).val(this.value);
        });

        $('#second-input').on('input', function() {
            $('#hexcolor2').val(this.value);
        });

        $('#hexcolor2').on('input', function() {
            $('#second-input').val(this.value);
            $(this).val(this.value);
        });

        $('#third-input').on('input', function() {
            $('#hexcolor3').val(this.value);
        });

        $('#hexcolor3').on('input', function() {
            $('#third-input').val(this.value);
            $(this).val(this.value);
        });

        $('#fourth-input').on('input', function() {
            $('#hexcolor4').val(this.value);
        });

        $('#hexcolor4').on('input', function() {
            $('#fourth-input').val(this.value);
            $(this).val(this.value);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/grapes.min.js"></script>


    <script src="{{ url('content_builder/plugins/js/grapesjs-preset-webpage.min.js') }}"></script>
    <script src="{{ url('content_builder/plugins/js/grapesjs-plugin-export.min.js') }}"></script>
    <script src="https://unpkg.com/grapesjs-rte-extensions@1.0.10/dist/grapesjs-rte-extensions.min.js"></script>
    <script>
        $_SITE = "{{ url('/') }}";
        $modulePath = "{{ $modulePath }}";
        $id = "{{ $itemId }}";
        $url = "{{ $itemLink }}";
        $is_rtl = "{{ $is_rtl }}";
        $extra_params = "{!! $extra_params !!}";
        $template = "";
        $_token = $('meta[name="csrf-token"]').attr("content");
        var blocks = @json($blocks);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.0.0/jquery.form.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="{{ url('content_builder/js/fontawsome.js') }}"></script>
    <script src="{{ url('content_builder/js/traits.js') }}"></script>
    <script src="{{ url('content_builder/js/components.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/basic.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/layouts.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/cards.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/typography.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/structure.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/navigation.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/features.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/headings.js') }}"></script>
    <script src="{{ url('content_builder/js/blocks/footers.js') }}"></script>
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

    <script src="{{ url('content_builder/js/builder.js') }}"></script>

    <script src="{{ url('content_builder/js/traits.js') }}"></script>
</body>

</html>
