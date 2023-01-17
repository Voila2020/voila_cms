<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing Page Builder</title>

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
    <script src="{{ url('landing_page_builder/js/builder.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

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

        body {
            height: 100vh;
            width: 100vw;
        }

        #gjs {
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div id="gjs">
    </div>
    <div class="modal fade" id="less-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Theme Colors</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <label>First color</label>
                        </div>
                        <div class="col">
                            <input id="first-input" var="@first-color" class="color-inputs" type="color">
                            <input type="text" var="@first-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#000000" class="hexcolor" id="hexcolor1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Second color</label>
                        </div>
                        <div class="col">
                            <input id="second-input" var="@second-color" class="color-inputs" type="color">
                            <input type="text" var="@second-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#000000" class="hexcolor" id="hexcolor2"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Third color</label>
                        </div>
                        <div class="col">
                            <input id="third-input" var="@third-color" class="color-inputs" type="color">
                            <input type="text" var="@third-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#000000" class="hexcolor" id="hexcolor3"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Fourth color</label>
                        </div>
                        <div class="col">
                            <input id="fourth-input" var="@fourth-color" class="color-inputs" type="color">
                            <input type="text" var="@fourth-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#000000" class="hexcolor" id="hexcolor4"><br>
                        </div>
                    </div>
                    <div id="colorpicker"></div>
                </div>
            </div>
        </div>
    </div>

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

        $_SITE = "{{ url('/') }}";
        $is_rtl = {{ $landingPage->is_rtl }};
        $id = "{{ $landingPageId }}";
    </script>
</body>

</html>
