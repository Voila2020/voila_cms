@extends('crudbooster::admin_template')
@if (CRUDBooster::getSetting('recaptcha_site_key'))
    <script src='https://www.google.com/recaptcha/api.js?render={{ CRUDBooster::getSetting('recaptcha_site_key') }}'>
    </script>
@endif
@section('title', 'Form') @section('content')

<div class="row">
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{!! \Session::get('success') !!}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! $data !!}
    </div>
</div>
<script>
    var $site_key = <?php echo json_encode(CRUDBooster::getSetting('recaptcha_site_key'), 15, 512); ?>;
    var $secret_key = <?php echo json_encode(CRUDBooster::getSetting('recaptcha_secret_key'), 15, 512); ?>;
    var $action_name = '{{ CRUDBooster::mainPath('submit') }}';
    if ($site_key && $secret_key) {
        grecaptcha.ready(function() {
            grecaptcha.execute($site_key, {
                action: '{{ config('crudbooster.ADMIN_PATH') }}/forms/submit',
            }).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            }).catch(function(error) {
                console.error("reCAPTCHA error:", error);
            });
        });
    }
</script>
@endsection
