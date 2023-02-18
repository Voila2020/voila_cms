@push('head')
    <style>
        .buttons {
            width: 85%;
            display: flex;
            justify-content: end;
            flex-direction: row-reverse;
        }

        .modal-header {
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
        }

        .modal-header .resize {
            height: 25px;
            border: none;
            background: none;
            margin-right: 2%;
        }

        .modal-body {
            padding: 0px !important;
            margin: 0px !important;
            width: 100% !important;
        }

        .file-roadtrip {
            margin-top: 8px !important;
        }

        .filemanager-iframe {
            overflow: scroll !important;
            overflow-x: hidden !important;
            overflow-y: scroll !important;
        }

        @if (App::getlocale() == 'en')
            .help-block {
                margin-left: 18%;
            }
        @else
            .help-block {
                margin-right: 18%;
            }
        @endif
    </style>
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endpush
