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

@push('bottom')
    <script type="text/javascript">
        function OpenInsertImagesingle(name) {
            // reback size of iframe to default
            $('.modal.in .modal-dialog').width(900);
            // check file manager type
            if ($('#_' + name).attr("value") == 'file_type') {
                var link =
                    `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=` +
                    name +
                    `" frameborder="0" ></iframe>`;
            } else {
                var link =
                    `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=1&multiple=0&field_id=` +
                    name +
                    `" frameborder="0"></iframe>`;
            }
            $('#img-' + name).prop("src", "");
            $('#link-' + name).prop("href", "");
            $('#link-' + name).addClass("hide");
            // col-sm-10 empty value clear
            $('#' + name).val("");
            $('#thumbnail-' + name).prop("src", "").val("");
            $('#roadtrip-' + name).prop("href", "");
            $('#holder-' + name).prop("src", "");
            $("#modalInsertPhotosingle_{{ $name }} .modal-body").html(link);
            $("#modalInsertPhotosingle_{{ $name }}").modal();
        }

        function showDeletePopout(name) {
            swal({
                title: "{{ cbLang('delete_title_confirm') }}",
                text: "{{ cbLang('delete_description_confirm') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ cbLang('confirmation_yes') }}",
                cancelButtonText: "{{ cbLang('button_cancel') }}",
                closeOnConfirm: false
            }, function() {
                deleteImage(name);
            });
        }


        function deleteImage(form_name) {
            let currUrl = @json(CRUDBooster::mainpath()) + '/update-single';
            let table = @json($table);
            let id = @json($id);
            let ajaxUrl = currUrl + '?table=' + table + '&column=' + form_name + '&value=&id=' + id;

            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                success: function(data) {
                    $('.filemanager-col_' + form_name).hide();
                    $('#img-' + form_name).prop("src", "");
                    $('#link-' + form_name).prop("href", "");
                    $('#link-' + form_name).addClass("hide");
                    // col-sm-10 empty value clear
                    $('#' + form_name).val("");
                    $('#thumbnail-' + form_name).prop("src", "").val("");
                    $('#roadtrip-' + form_name).prop("href", "");
                    $('#holder-' + form_name).prop("src", "");
                    $('.empty-filemanager-col_' + form_name).show();
                    swal.close();
                },
                error: function(data) {

                }
            });
        }

        var id = '#modalInsertPhotosingle_{{ $name }}';
        $(function() {
            $(id).on('hidden.bs.modal', function() {
                var check = $('#{{ $name }}').val();
                if (check != "") {
                    check = check.substring(1);
                    if ("{{ @$form['filemanager_type'] }}" == 'file')
                        $("#file-{{ $name }}").html(check);
                    else
                        $("#img-{{ $name }}").attr("src", '{{ URL::asset('') }}' + check);
                    $("#link-{{ $name }}").attr("href", '{{ URL::asset('') }}' + check);
                    $("#link-{{ $name }}").removeClass("hide");
                    $("#thumbnail-{{ $name }}").attr("src", '{{ URL::asset('') }}' + check);
                    $("#thumbnail-{{ $name }}").attr("value", '{{ URL::asset('') }}' + check);
                }
            });
            resizeFilemanagerPopout();
        });

        function resizeFilemanagerPopout() {
            $('.modal-header .resize').unbind().click(function() {
                if ($('.modal.in .modal-dialog').width() == 900) {
                    $('.modal.in .modal-dialog').width($(window).width());
                    $('iframe').height($(window).height());
                } else {
                    $('.modal.in .modal-dialog').width(900);
                    $('iframe').height(400);
                }
            });
        }
    </script>
@endpush
