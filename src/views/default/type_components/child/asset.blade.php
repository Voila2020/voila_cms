@push('bottom')
    <script src='<?php echo asset('vendor/crudbooster/assets/select2/dist/js/select2.full.min.js'); ?>'></script>
@endpush
@push('head')
    <link rel='stylesheet' href='<?php echo asset('vendor/crudbooster/assets/select2/dist/css/select2.min.css'); ?>' />
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: 0px !important
        }

        .select2-container .select2-selection--single {
            height: 35px
        }

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
    </style>
@endpush
