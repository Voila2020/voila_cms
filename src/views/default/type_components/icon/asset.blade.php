@push('head')
    <link href="{{ asset('vendor/crudbooster/assets/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('bottom')
    <script src="{{ asset('vendor/crudbooster/assets/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            function format(icon) {
                debugger
                var originalOption = icon.element;
                var label = $(originalOption).text();
                var val = $(originalOption).val();
                if (!val) return label;
                var $resp = $('<span><i style="margin-top:5px" class="pull-right ' + $(originalOption).val() +
                    '"></i> ' +
                    $(originalOption).data('label') + '</span>');
                return $resp;
            }

            $('#list-icon').select2({
                width:  "100%",
                templateResult: format,
                templateSelection: format
            });
        });
    </script>
@endpush
