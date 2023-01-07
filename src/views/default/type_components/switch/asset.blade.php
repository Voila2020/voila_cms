@push('head')
    <style>
        .switch-button-input-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 5px;
        }
    </style>
@endpush

@push('bottom')
    <script>
        $('input[type="checkbox"]').on('click', function() {
            if ($(this).val() == 0) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });
    </script>
@endpush
