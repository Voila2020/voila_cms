@php
    $images = DB::table('model_images')
        ->where('model_type', $table)
        ->where('model_id', $row->id)
        ->get();
@endphp

<div class='form-group filemanager-form-group_{{ $name }} {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
    id='form-group-{{ $name }}' style='{{ @$form['style'] }}'>
    <label class='control-label col-sm-2'>{{ $form['label'] }}
        @if ($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{ $col_width ? $col_width . ' empty-filemanager-col_' . $name : 'col-sm-10 filemanager-col_' . $name }}"
        style="{{ $value ? 'display: none' : '' }}">
        <div class="input-group">
            <input id="{{ $name }}"
                filemanager_type="{{ @$form['filemanager_type'] == 'file' ? 'file' : 'image' }}"
                class="form-control hide" type="text" value='{{ $value }}' name="{{ $name }}">

            <a data-lightbox="roadtrip" class="hide" id="link-{{ $name }}" href=""
                style="{{ @$form['filemanager_type'] == 'file' ? 'pointer-events: none;' : '' }}">
                <img style="width:150px;height:150px; {{ @$form['filemanager_type'] == 'file' ? 'display:none;' : '' }}"
                    id="img-{{ $name }}" title="Add image for {{ $name }}" src="">
                <p class="file-roadtrip" id="file-{{ $name }}"
                    style="{{ @$form['filemanager_type'] == 'file' ? '' : 'display:none;' }}"></p>
            </a>

            <span class="input-group-btn">
                @if (@$form['filemanager_type'] == 'file')
                    <a id="_{{ $name }}" onclick='OpenInsertImagesingle("{{ $name }}")'
                        class="btn btn-primary" value="file_type">
                        <i class="fa fa-file-o"></i> {{ cbLang('chose_an_file') }}
                    @else
                        <a id="_{{ $name }}" onclick='OpenInsertImagesingle("{{ $name }}")'
                            class="btn btn-primary" value="img_type">
                            <i class='fa fa-picture-o'></i> {{ cbLang('chose_an_image') }}
                @endif
                </a>
            </span>
        </div>
        <div class="text-danger">{!! $errors->first($name) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name) : '' !!}</div>
        <div class='help-block'>{{ @$form['help'] }}</div>
    </div>

    <div class="{{ $col_width ? $col_width . ' filemanager-col_' . $name : 'col-sm-10 filemanager-col_' . $name }}"
        style="{{ $value ? '' : 'display: none;' }}">
        <input id="thumbnail-{{ $name }}" class="form-control" type="hidden" value='{{ $value }}'
            name="{{ $name }}">
        @if (@$form['filemanager_type'] == 'file')
            @if ($value)
                <div style='margin-top:15px'><a id='holder-{{ $name }}' href='{{ asset($value) }}'
                        target='_blank' title=' {{ cbLang('button_download_file') }} {{ basename($value) }}'><i
                            class='fa fa-download'></i> {{ cbLang('button_download_file') }}
                        {{ basename($value) }}</a>
                </div>
            @endif
        @else
            <p><a id="roadtrip-{{ $name }}" class="p-roadtrip" data-lightbox="roadtrip"
                    href="{{ $value ? asset($value) : '' }}"><img id='holder-{{ $name }}'
                        src="{{ $value ? asset($value) : '' }}" style="margin-top:15px;max-height:100px;"></a>
            </p>
        @endif
        @if (!$readonly || !$disabled)
            <p>
                <a class='btn btn-danger btn-delete btn-sm btn-del-filemanager'
                    onclick='showDeletePopout("{{ $name }}")'><i class='fa fa-ban'></i>
                    {{ cbLang('text_delete') }} </a>
            </p>
        @endif
        <div class='help-block'>{{ @$form['help'] }}</div>
    </div>


</div>

<div class="modal fade" id="modalInsertPhotosingle_{{ $name }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="buttons">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <button type="button" class="resize" title="<?php echo cbLang('filemanager.resize'); ?>"><i class="fa fa-expand"
                            aria-hidden="true"></i></button>
                </div>
                <div class="title-sec">
                    <h4 class="modal-title">Insert Image</h4>
                </div>
            </div>
            <div class="modal-body">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@push('bottom')
    <script type="text/javascript">
        var Name;

        function OpenInsertImagesingle(name) {
            Name = name;
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
            if (id == null) {
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
                return;
            }
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

        $(function() {
            var id = '#modalInsertPhotosingle_{{ $name }}';
            $(id).on('hidden.bs.modal', function() {
                var check = $('#' + Name).val();
                if (check != "") {
                    check = check.substring(1);
                    if ($('#_' + Name).attr("value") == 'file_type') {
                        $("#file-" + Name).html(check);
                    } else {
                        $("#img-" + Name).attr("src", '{{ URL::asset('') }}' + check);
                        //---------------------------------------//
                        //convert to webp
                        var imageUrl = '{{ URL::asset('') }}' + check;

                        var img = new Image();
                        img.crossOrigin = 'Anonymous';
                        img.onload = function() {
                            var canvas = document.createElement('canvas');
                            canvas.width = img.width;
                            canvas.height = img.height;
                            var ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, img.width, img.height);
                            //---------------------------------------//
                            canvas.toBlob(function(blob) {
                                var reader = new FileReader();
                                reader.onloadend = function() {
                                    var base64Data = reader.result;
                                    //---------------------------------------//
                                    $('input[name="' + Name + '_webp"]').val(base64Data);                                    //---------------------------------------//
                                    //---------------------------------------//
                                    var webpImageElement = document.createElement('img');
                                    webpImageElement.src = base64Data;
                                };
                                reader.readAsDataURL(blob);
                            }, 'image/webp');
                        };
                        img.src = imageUrl;
                    }
                    $("#link-" + Name).attr("href", '{{ URL::asset('') }}' + check);
                    $("#link-" + Name).removeClass("hide");
                    $("#thumbnail-" + Name).attr("src", '{{ URL::asset('') }}' + check);
                    $("#thumbnail-" + Name).attr("value", check);
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
