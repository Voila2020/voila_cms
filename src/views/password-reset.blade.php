<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ cbLang('page_title_forgot') }} : {{ $appname }}</title>
    <meta name='generator' content='CRUDBooster.com' />
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
    <link rel='stylesheet' href='{{ asset('vendor/crudbooster/assets/css/main.css') }}' />
    <link rel='stylesheet' href='{{ asset('vendor/crudbooster/assets/css/main.css') }}' />
    <style type="text/css">
        .resset-password-page {
            background: {{ CRUDBooster::getSetting('login_background_color') ?: '#dddddd' }} url('{{ CRUDBooster::getSetting('login_background_image') ? asset(CRUDBooster::getSetting('login_background_image')) : asset('vendor/crudbooster/assets/bg_blur3.jpg') }}');
            color: {{ CRUDBooster::getSetting('login_font_color') ?: '#ffffff' }} !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .reset-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 50%;
            margin: 0 auto;
            margin-top: 2%;
        }

        .reset-box-body {
            box-shadow: 0px 0px 50px rgba(0, 0, 0, 0.8);
            background: rgba(255, 255, 255, 0.9);
            color: {{ CRUDBooster::getSetting('login_font_color') ?: '#666666' }} !important;
            padding: 50px 20px 40px 20px;
            width: 360px;
            margin: 0 auto;
        }

        .row {
            margin-bottom: 10px;
        }

        .btn-row {
            display: flex;
            justify-content: center;
        }

        .reset-pass-btn {
            width: 100%;
            margin-left: 10px;
            margin-right: 10px;
        }

        .input-text {
            color: #666666;
            font-weight: normal;
        }

        .login-logo {
            margin-top: 25%;
        }
    </style>
</head>

<body class="resset-password-page">
    <div class="reset-box">
        <div class="login-logo">
        </div><!-- /.login-logo -->
        <div class="reset-box-body">
            @if (Session::get('message') != '')
                <div class='alert alert-warning'>
                    {{ Session::get('message') }}
                </div>
            @endif

            <form class="validate-form" action="{{ Route('cms_reset_password') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="token" value={{ $token }} required />
                <input type="hidden" name="email" value="{{ request()->has('email') ? request('email') : '' }}"
                    required />
                <div class="form-group has-feedback">
                    <div class="row">
                        <label class="control-label col-sm-3">
                            <span class="text-danger input-text" title="this_field_is_required">Password</span>
                        </label>
                        <div class="col-sm-9">
                            <input type='password' title="Password" id="reset-password" class='form-control'
                                name="reset_password" required />
                        </div>
                    </div>
                    <div class="row">
                        <label class="control-label col-sm-3">
                            <span class="text-danger input-text" title="this_field_is_required">Confirmation
                                password</span>
                        </label>
                        <div class="col-sm-9">
                            <input type='password' title="Password Confirmation" id="password-confirmation"
                                class='form-control' name="password_confirmation" required />
                        </div>
                    </div>
                </div>
                <div class="row btn-row">
                    <button class="btn btn-primary reset-pass-btn">Submit</button>
                </div>
            </form>
        </div><!-- /.teset-box-body -->
    </div><!-- /.reset-box -->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.4.1 JS -->
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js') }}" type="text/javascript">
    </script>

    <script>
        $('#password-confirmation').on('keyup', function() {
            var password = $('#reset-password').val();
            var password_confirmation = $('#password-confirmation').val();
            if (password != password_confirmation) {
                $('#password-confirmation').html('Passwords does not matches');
            }
        });
    </script>

</body>

</html>
