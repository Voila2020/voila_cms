<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Email Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/css/grapes.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<style>
    html,
    body {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }
</style>
<style>
    .fancybox-content {
        width: 900px !important;
        height: 600px !important;
    }
</style>

<body>

    <div id="email_gjs">
    </div>

    <script>
        $_SITE = "{{ url('/') }}";
        $id = "{{ $id }}";
        $_token = $('meta[name="csrf-token"]').attr("content");
        $lang = "{{ $button_lang }}";
    </script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/grapes.min.js"></script>
    <script src="https://unpkg.com/grapesjs-preset-newsletter"></script>
    <script src=" https://cdn.jsdelivr.net/npm/grapesjs-plugin-ckeditor@1.0.1/dist/index.min.js "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>


    <script src="{{ asset('email_builder/builder.js') }}"></script>

</body>

</html>
