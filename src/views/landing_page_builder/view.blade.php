<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $landingPageSeo->title }}</title>

    <meta property="og:image" content="{{ $landingPageSeo->image }}" />
    <meta property="og:image:secure_url" content="{{ $landingPageSeo->image }}" />
    <meta property="og:title" content="{{ $landingPageSeo->title }}" />
    <meta property="og:site_name" content="Voila" />
    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:description" content="{{ $landingPageSeo->description }}" />
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@Voila">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $landingPageSeo->title }}">
    <meta name="twitter:description" content="{{ $landingPageSeo->description }}">
    <meta name="twitter:image" content="{{ $landingPageSeo->image }}">
    <title>{{ $landingPageSeo->title_en }}</title>
    <meta name="description" content="{{ $landingPageSeo->description }}">
    <meta name="keywords" content="{{ $landingPageSeo->keywords }}">
    <meta name="DC.Title" content="{{ $landingPageSeo->title_en }}">
    <meta name="DC.Creator" content="Voila">
    <meta name="DC.Subject" content="Voila">
    <meta name="DC.Description" content="{{ $landingPageSeo->description }}">
    <meta name="DC.Publisher" content="Voila">
    <meta name="DC.Date" content="{{ date('Y-m-d') }}">
    <meta name="DC.Type" content="text">
    <meta name="DC.Identifier" content="{{ request()->url() }}">
    <meta name="DC.Language" content="en">
    <meta name="DC.Coverage" content="{{ date('Y') }}">
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-shims.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" type='text/css'
        href="https://fonts.googleapis.com/css?family=Lobster|Tajawal|Vollkorn|Open+Sans|Cairo|Almarai|Changa|Lareza|Noto+Sans+Arabic|IBM+Plex+Sans+Arabic|Lato">

    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/less/styles.less') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('landing_page_builder/css/styles.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('landing_page_builder/css/canvas.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @if ($landingPage->is_rtl)
        <link rel="stylesheet" type="text/css" href="{{ url('landing_page_builder/css/rtl_styles.css') }}" />
    @else
        <link rel="stylesheet" type="text/css" href="{{ url('landing_page_builder/css/ltr_styles.css') }}" />
    @endif

    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

</head>
<style>
    {!! $landingPage->css !!}
</style>

<body class="innerBody">
    {!! $landingPage->html !!}
    <script>
        let old = @json(old());
        $(function() {
            for (const key in old) {
                if (key != "_token" && key != "landing_page_id")
                    $('[name="' + key + '"]').val(old[key]);
            }
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-{{ $landingPage->is_rtl ? 'left' : 'right' }}",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "10000",
                "hideDuration": "10000",
                "timeOut": "10000",
                "extendedTimeOut": "10000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            @if (session()->has('error'))
                let message = "{{ session('error') }}";
                toastr.error(message);
            @endif
            variables = JSON.parse(@json($landingPage->variables));
            less.modifyVars(variables);
        })
    </script>
</body>

</html>
