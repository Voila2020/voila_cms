<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ cbLang('page_title_login') }} : {{ Session::get('appname') }}</title>
    <meta name='generator' content='CRUDBooster' />
    <meta name='robots' content='noindex,nofollow' />
    <link rel="shortcut icon"
        href="{{ CRUDBooster::getSetting('favicon') ? asset(CRUDBooster::getSetting('favicon')) : asset('vendor/crudbooster/assets/voila_logo.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- support rtl-->
    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset('vendor/crudbooster/assets/rtl.css') }}" rel="stylesheet" type="text/css" />
    @endif

    <!-- recaptcha -->
    @if (CRUDBooster::getSetting('recaptcha_site_key'))
        <script src='https://www.google.com/recaptcha/api.js?render={{ CRUDBooster::getSetting('recaptcha_site_key') }}'>
        </script>
    @endif

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href='{{ asset('vendor/crudbooster/assets/css/main.css') }}' />
    <style type="text/css">
        .login-page,
        .register-page {
            background: {{ CRUDBooster::getSetting('login_background_color') ?: '#dddddd' }} url('{{ CRUDBooster::getSetting('login_background_image') ? asset(CRUDBooster::getSetting('login_background_image')) : url('vendor/crudbooster/login_default.png') }}');
            color: {{ CRUDBooster::getSetting('login_font_color') ?: '#ffffff' }} !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .login-box,
        .register-box {
            margin: 2% auto;
        }

        .login-box-body {
            box-shadow: 0px 0px 50px rgba(0, 0, 0, 0.8);
            background: rgba(255, 255, 255, 0.9);
            color: {{ CRUDBooster::getSetting('login_font_color') ?: '#666666' }} !important;
        }

        html,
        body {
            overflow: hidden;
        }

        .password-reset {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }

        .login-logo {
            margin-top: 50%;
        }
    </style>
</head>

<body class="login-page">

    <div class="login-box">
        <div class="login-logo">
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            @if (Session::get('message') != '')
                <div class='alert alert-warning'>
                    {{ Session::get('message') }}
                </div>
            @endif

            <p class='login-box-msg'>{{ cbLang('login_message') }}</p>
            <form id="login-form" action="{{ route('postLogin') }}" method="POST" autocomplete='off'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                @if (!empty(config('services.google')))
                    {{-- <div style="margin-bottom:10px" class='row'>
                        <div class='col-xs-12'>

                            <a href='{{ route('redirect', 'google') }}' class="btn btn-primary btn-block btn-flat"><i
                                    class='fa fa-google'></i>
                                Google Login</a>

                            <hr>
                        </div>
                    </div> --}}
                @endif

                <div class="form-group has-feedback">
                    <input autocomplete='off' type="text" class="form-control" name='email'
                        value="{{ Session::get('remember_email') }}" required placeholder="Email" />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input autocomplete='off' type="password" id="id_password" class="form-control" name='password'
                        required placeholder="Password" />
                    <div id="togglePassword" class="password-reset">
                        <span class="glyphicon glyphicon-eye-open"></span>
                    </div>

                </div>
                <div style="margin-bottom:10px" class='row'>
                    <div class='col-xs-12'>
                        <button type="submit" id="login-button" class="btn btn-primary btn-block btn-flat"><i
                                class='fa fa-lock'></i>
                            {{ cbLang('button_sign_in') }}</button>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-xs-12' align="center">
                        <p style="padding:10px 0px 10px 0px">{{ cbLang('text_forgot_password') }} <a
                                href='{{ route('getForgot') }}'>{{ cbLang('click_here') }}</a></p>
                    </div>
                </div>
                @if (CRUDBooster::getSetting('recaptcha_secret_key'))
                    <input id="g-recaptcha-response" type="hidden" name="g-recaptcha-response"
                        data-sitekey="{{ CRUDBooster::getSetting('recaptcha_secret_key') }}" />
                @endif
            </form>
            <br />
            <!--a href="#">I forgot my password</a-->
        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.4.1 JS -->
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js') }}" type="text/javascript">
    </script>
    <script language="javascript">
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#id_password');

        togglePassword.addEventListener('click', function(e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (togglePassword.querySelector('.glyphicon').classList.contains('glyphicon-eye-open')) {
                togglePassword.querySelector('.glyphicon').classList.remove('glyphicon-eye-open');
                togglePassword.querySelector('.glyphicon').classList.add('glyphicon-eye-close');
            } else {
                togglePassword.querySelector('.glyphicon').classList.add('glyphicon-eye-open');
                togglePassword.querySelector('.glyphicon').classList.remove('glyphicon-eye-close');
            }
            // toggle the eye slash icon
        });
        var $site_key = @json(CRUDBooster::getSetting('recaptcha_site_key'));
        var keysValidity = false;
        if ($site_key) {
            grecaptcha.ready(function() {
                grecaptcha.execute($site_key, {
                    action: '{{ config('crudbooster.ADMIN_PATH') }}/login'
                }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                });
            });
        }
    </script>
</body>

</html>
