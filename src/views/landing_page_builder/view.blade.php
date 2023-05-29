<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $landingPageSeo->title_en }}</title>

    <meta property="og:image" content="{{ $landingPageSeo->image }}" />
    <meta property="og:image:secure_url" content="{{ $landingPageSeo->image }}" />
    <meta property="og:title" content="{{ $landingPageSeo->title_en }}" />
    <meta property="og:site_name" content="Voila" />
    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:description" content="{{ $landingPageSeo->description }}" />
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@Voila">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $landingPageSeo->title_en }}">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.6/swiper-bundle.css">
    <link rel="stylesheet" type='text/css'
        href="https://fonts.googleapis.com/css?family=Lobster|Tajawal|Vollkorn|Open+Sans|Cairo|Almarai|Changa|Lareza|Noto+Sans+Arabic|IBM+Plex+Sans+Arabic|Lato">

    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/less/styles.less') }}" />
    <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/css/styles.css') }}" />
    <link rel="stylesheet/less" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @if ($landingPage->is_rtl)
        <link rel="stylesheet/less" type="text/css" href="{{ url('landing_page_builder/css/rtl_styles.css') }}" />
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>

    <script src="{{ url('landing_page_builder/js/font-awesome.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.6/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    @if (CRUDBooster::getSetting('recaptcha_site_key'))
        <script src='https://www.google.com/recaptcha/api.js?render={{ CRUDBooster::getSetting('recaptcha_site_key') }}'>
        </script>
    @endif
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
            @if (session()->has('message'))
                let message = "{{ session('message') }}";
                if ("{{ session('message_type') }}" == "success")
                    toastr.success(message);
                else
                    toastr.error(message);
            @endif
            variables = JSON.parse(@json($landingPage->variables));
            less.modifyVars(variables);
        });
        var $site_key = <?php echo json_encode(CRUDBooster::getSetting('recaptcha_site_key'), 15, 512); ?>;
        var $secret_key = <?php echo json_encode(CRUDBooster::getSetting('recaptcha_secret_key'), 15, 512); ?>;
        if ($site_key && $secret_key) {
            grecaptcha.ready(function() {
                grecaptcha.execute($site_key, {
                    action: '{{ config('crudbooster.ADMIN_PATH') }}/forms/submit/'
                }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                }).catch(function(error) {
                    console.error("reCAPTCHA error:", error);
                });
            });
        }
    </script>
</body>

</html>
