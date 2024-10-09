<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Builder</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/css/grapes.min.css">
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/less/styles.less') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/plugins/css/grapes.min.css') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/v62njx9127wlchyml53il7iulsly6idz61l60t9d629j33pt/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <link rel="stylesheet" href="{{ url('landing_page_builder/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ url('landing_page_builder/css/builder.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .fancybox-content {
            width: 900px !important;
            height: 600px !important;
        }
    </style>


</head>

<body>

    <div id="gjs">
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
                            <input id="first-input" var="@first-color" class="color-inputs" type="color">
                            <input type="text" var="@first-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                value="#000000" class="hexcolor" id="hexcolor1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Second color</label>
                        </div>
                        <div class="col">
                            <input id="second-input" var="@second-color" class="color-inputs" type="color">
                            <input type="text" var="@second-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                value="#000000" class="hexcolor" id="hexcolor2"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Third color</label>
                        </div>
                        <div class="col">
                            <input id="third-input" var="@third-color" class="color-inputs" type="color">
                            <input type="text" var="@third-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                value="#000000" class="hexcolor" id="hexcolor3"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Fourth color</label>
                        </div>
                        <div class="col">
                            <input id="fourth-input" var="@fourth-color" class="color-inputs" type="color">
                            <input type="text" var="@fourth-color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                value="#000000" class="hexcolor" id="hexcolor4"><br>
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


    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-preset-webpage.min.js') }}"></script>
    <script src="{{ url('landing_page_builder/plugins/js/grapesjs-plugin-export.min.js') }}"></script>
    <script>
        $_SITE = "{{ url('/') }}";
        $id = "{{ $landingPageId }}";
        $url = "{{ $landingPageUrl }}";
        $is_rtl = "{{ $landingPage->is_rtl }}";
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
    <script src="{{ url('landing_page_builder/js/fontawsome.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/traits.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/components.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/basic.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/layouts.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/cards.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/typography.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/structure.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/navigation.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/features.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/headings.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/footers.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/blocks/forms.js') }}"></script>
    <script src="{{ url('landing_page_builder/js/builder.js') }}"></script>

    <script src="{{ url('landing_page_builder/js/traits.js') }}"></script>
</body>

</html>
