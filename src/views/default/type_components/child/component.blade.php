<?php
$name = str_slug($form['label'], '');
$childTableName = $form['table'];
?>
@push('bottom')
    <script type="text/javascript">
        $(function() {
            $('#form-group-{{ $name }} .select2').select2();
        })
        var idtable = "#table-{{ $name }} tbody";
        $(idtable).sortable({});

        $('#table-{{ $name }} tbody').sortable({
            axis: 'y',
            update: function(event, ui) {
                var data_list = new Array();
                $('#table-{{ $name }}').find('tbody tr').each(function(e) {
                    data_list.push($(this).find('[name="{{ $name }}-id[]"]').val());
                });
                var table = '{{ $table }}';
                $('html, body').css("cursor", "wait");

                // POST to server using $.post or $.ajax
                $.ajax({
                    data: {
                        data: data_list,
                        table_name: "{{ $childTableName }}"
                    },
                    type: 'POST',
                    url: "{{ Crudbooster::mainPath('sort-table') }}",
                    success: function(data) {
                        $('html, body').css("cursor", "auto");
                    },
                    error: function(data) {
                        $('html, body').css("cursor", "auto");
                    }
                });
            }
        });
    </script>
@endpush
<div class='form-group {{ $header_group_class }}' id='form-group-{{ $name }}'>

    @if ($form['columns'])
        <div class="col-sm-12">

            <div id='panel-form-{{ $name }}' class="panel panel-default">
                <div class="panel-heading">
                    <i class='fa fa-bars'></i> {{ $form['label'] }}
                </div>
                <div class="panel-body">

                    <div class='row'>
                        <div class='col-sm-10'>
                            <div class="panel panel-default">
                                <div class="panel-heading"><i class="fa fa-pencil-square-o"></i>
                                    {{ cbLang('text_form') }}</div>
                                <div class="panel-body child-form-area child-form-area-{{ $name }}">
                                    <div class="hidden-value hide"></div>
                                    @foreach ($form['columns'] as $col_key => $col)
                                        <?php
                                        $name_column = $name . $col['name'];
                                        $required = strpos($col['validation'], 'required') !== false ? 'required' : '';
                                        ?>
                                        <div class='form-group'>
                                            @if ($col['type'] != 'hidden')
                                                <label class="control-label col-sm-2">{{ cbLang($col['label']) }}
                                                    @if ($required)
                                                        <span class="text-danger"
                                                            title="{{ cbLang('this_field_is_required') }}">*</span>
                                                    @endif
                                                </label>
                                            @endif
                                            @if ($col['type'] == 'filemanager')
                                                <div class="{{ 'col-sm-10 filemanager-col_' . $col['name'] }}"
                                                    style="{{ $value ? 'display: none' : '' }}">
                                                    <div class="input-group">
                                                        <input id="{{ $col['name'] }}" $required
                                                            class="form-control hide <?= $required ?>" type="text"
                                                            value='{{ $value }}' name="{{ $col['name'] }}">

                                                        <a data-lightbox="roadtrip" class="hide"
                                                            id="link-{{ $col['name'] }}" href=""
                                                            style="{{ $col['filemanager_type'] == 'file' ? 'pointer-events: none;' : '' }}">
                                                            <img style="width:150px;height:150px; {{ $col['filemanager_type'] == 'file' ? 'display:none;' : '' }}"
                                                                id="img-{{ $col['name'] }}"
                                                                title="Add image for {{ $col['name'] }}"
                                                                src="">
                                                            <p class="file-roadtrip" id="file-{{ $col['name'] }}"
                                                                style="{{ $col['filemanager_type'] == 'file' ? '' : 'display:none;' }}">
                                                            </p>
                                                        </a>

                                                        <span class="input-group-btn">
                                                            @if ($col['filemanager_type'] == 'file')
                                                                <a id="_{{ $col['name'] }}"
                                                                    onclick="OpenInsertChildImagesingle('{{ $col['name'] }}')"
                                                                    class="btn btn-primary" value="file_type">
                                                                    <i class="fa fa-file-o"></i>
                                                                    {{ cbLang('chose_an_file') }}
                                                                @else
                                                                    <a id="_{{ $col['name'] }}"
                                                                        onclick="OpenInsertChildImagesingle('{{ $col['name'] }}')"
                                                                        class="btn btn-primary" value="img_type">
                                                                        <i class='fa fa-picture-o'></i>
                                                                        {{ cbLang('chose_an_image') }}
                                                            @endif
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="{{ 'col-sm-10 filemanager-col_' . $col['name'] }}"
                                                    style="{{ $value ? '' : 'display: none;' }}">
                                                    <input id="thumbnail-{{ $col['name'] }}" class="form-control"
                                                        type="hidden" value='{{ $value }}'>
                                                    @if ($col['filemanager_type'] == 'file')
                                                        @if ($value)
                                                            <div style='margin-top:15px'><a
                                                                    id='holder-{{ $col['name'] }}'
                                                                    href='{{ asset($value) }}' target='_blank'
                                                                    title=' {{ cbLang('button_download_file') }} {{ basename($value) }}'><i
                                                                        class='fa fa-download'></i>
                                                                    {{ cbLang('button_download_file') }}
                                                                    {{ basename($value) }}</a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <p><a id="roadtrip-{{ $col['name'] }}" class="p-roadtrip"
                                                                data-lightbox="roadtrip"
                                                                href="{{ $value ? asset($value) : '' }}"><img
                                                                    id='holder-{{ $col['name'] }}'
                                                                    src="{{ $value ? asset($value) : '' }}"
                                                                    style="margin-top:15px;max-height:100px;"></a>
                                                        </p>
                                                    @endif
                                                    @if (!$readonly || !$disabled)
                                                        <p>
                                                            <a class='btn btn-danger btn-delete btn-sm btn-del-filemanager'
                                                                onclick='showChildDeletePopout("{{ $col['name'] }}")'><i
                                                                    class='fa fa-ban'></i>
                                                                {{ cbLang('text_delete') }} </a>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="modal fade"
                                                    id="modalInsertChildPhotosingle_{{ $col['name'] }}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <div class="buttons">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"
                                                                        aria-hidden="true">&times;</button>
                                                                    <button type="button" class="resize"
                                                                        title="<?php echo cbLang('filemanager.resize'); ?>"><i
                                                                            class="fa fa-expand"
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
                                                        var _Name;

                                                        function OpenInsertChildImagesingle(name) {
                                                            _Name = name;
                                                            // reback size of iframe to default
                                                            $('.modal.in .modal-dialog').width(900);
                                                            // check file manager type
                                                            if ($('#panel-form-{{ $name }} #_' + name).attr("value") == 'file_type') {
                                                                var link =
                                                                    `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=2&multiple=0&parent_field_id=panel-form-{{ $name }}&field_id=` +
                                                                    name +
                                                                    `" frameborder="0" ></iframe>`;
                                                            } else {
                                                                var link =
                                                                    `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=1&multiple=0&parent_field_id=panel-form-{{ $name }}&field_id=` +
                                                                    name +
                                                                    `" frameborder="0"></iframe>`;
                                                            }
                                                            $('#panel-form-{{ $name }} #img-' + name).prop("src", "");
                                                            $('#panel-form-{{ $name }} #link-' + name).prop("href", "");
                                                            $('#panel-form-{{ $name }} #link-' + name).addClass("hide");
                                                            // col-sm-10 empty value clear
                                                            $('#panel-form-{{ $name }} #' + name).val("");
                                                            $('#panel-form-{{ $name }} #thumbnail-' + name).prop("src", "").val("");
                                                            $('#panel-form-{{ $name }} #roadtrip-' + name).prop("href", "");
                                                            $('#panel-form-{{ $name }} #holder-' + name).prop("src", "");
                                                            $("#panel-form-{{ $name }} #modalInsertChildPhotosingle_" + name + " .modal-body").html(link);
                                                            $("#panel-form-{{ $name }} #modalInsertChildPhotosingle_" + name).modal();
                                                        }

                                                        function showChildDeletePopout(name) {
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
                                                                deleteImageFromChild(name);
                                                            });
                                                        }

                                                        function deleteImageFromChild(form_name) {
                                                            let currUrl = @json(CRUDBooster::mainpath()) + '/update-single';
                                                            let table = @json($table);
                                                            let id = @json($id);
                                                            let ajaxUrl = currUrl + '?table=' + table + '&column=' + form_name + '&value=&id=' + id;

                                                            $.ajax({
                                                                type: 'GET',
                                                                url: ajaxUrl,
                                                                success: function(data) {
                                                                    $('.filemanager-col_' + form_name).hide();
                                                                    $('#panel-form-{{ $name }} #img-' + form_name).prop("src", "");
                                                                    $('#panel-form-{{ $name }} #link-' + form_name).prop("href", "");
                                                                    $('#panel-form-{{ $name }} #link-' + form_name).addClass("hide");
                                                                    // col-sm-10 empty value clear
                                                                    $('#panel-form-{{ $name }} #' + form_name).val("");
                                                                    $('#panel-form-{{ $name }} #thumbnail-' + form_name).prop("src", "").val("");
                                                                    $('#panel-form-{{ $name }} #roadtrip-' + form_name).prop("href", "");
                                                                    $('#panel-form-{{ $name }} #holder-' + form_name).prop("src", "");
                                                                    $('#panel-form-{{ $name }} .empty-filemanager-col_' + form_name).show();
                                                                    swal.close();
                                                                },
                                                                error: function(data) {

                                                                }
                                                            });
                                                        }
                                                        $(function() {
                                                            var id = '#panel-form-{{ $name }} #modalInsertChildPhotosingle_{{ $col['name'] }}';
                                                            $(id).on('hidden.bs.modal', function() {
                                                                var check = $('#panel-form-{{ $name }} #' + _Name).val();
                                                                if (check != "") {
                                                                    check = check.substring(1);
                                                                    if ("{{ $col['filemanager_type'] }}" == 'file')
                                                                        $("#panel-form-{{ $name }} #file-" + _Name).html(check);
                                                                    else
                                                                        $("#panel-form-{{ $name }} #img-" + _Name).attr("src",
                                                                            '{{ URL::asset('') }}' + check);
                                                                    $("#panel-form-{{ $name }} #link-" + _Name).attr("href", check);
                                                                    $("#panel-form-{{ $name }} #link-" + _Name).removeClass("hide");
                                                                    $("#panel-form-{{ $name }} #thumbnail-" + _Name).attr("src",
                                                                        '{{ URL::asset('') }}' + check);
                                                                    $("#panel-form-{{ $name }} #thumbnail-" + _Name).attr("value", check);
                                                                    $("#panel-form-{{ $name }} #" + _Name).val(check);
                                                                }
                                                            });
                                                            resizeFilemanagerPopout();
                                                        });

                                                        function resizeFilemanagerPopout() {
                                                            $('.modal-header .resize').unbind().click(function() {
                                                                if ($('.modal.in .modal-dialog').width() == 900) {
                                                                    $('.modal.in .modal-dialog').width(1300);
                                                                    $('iframe').height(600);
                                                                } else {
                                                                    $('.modal.in .modal-dialog').width(900);
                                                                    $('iframe').height(400);
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                @endpush
                                            @endif
                                            <div class="col-sm-10">
                                                @if ($col['type'] == 'text')
                                                    <input id='{{ $name_column }}' type='text'
                                                        {{ $col['max'] ? "maxlength='" . $col['max'] . "'" : '' }}
                                                        name='child-{{ $col['name'] }}'
                                                        class='form-control {{ $col['required'] ? 'required' : '' }}'
                                                        {{ $col['readonly'] === true ? 'readonly' : '' }} />
                                                @elseif($col['type'] == 'switch')
                                                    <div class='form-group {{ $header_group_class }} {{ $errors->first($col['name']) ? 'has-error' : '' }}'
                                                        id='form-group' style="{{ @$form['style'] }}">
                                                        <div class="{{ $col_width ?: 'col-sm-10' }}">
                                                            <input class='form-control cms_switch_input'
                                                                type='checkbox' title="{{ $form['label'] }}"
                                                                {{ $readonly }} {!! $placeholder !!}
                                                                {{ $disabled }}
                                                                name="child_switch{{ $col['name'] }}"
                                                                id="child_switch{{ $col['name'] }}"
                                                                value="{{ $value ? $value : ($form['default_value'] && $command != 'edit' ? $form['default_value'] : 0) }}"
                                                                {{ $value == 1 ? 'checked' : ($form['default_value'] == 1 && $command != 'edit' ? 'checked' : '') }} />
                                                            <label class='cms_switch_label'
                                                                for='child_switch{{ $col['name'] }}'>Toggle</label>
                                                            <div class="text-danger">{!! $errors->first($col['name']) ? "<i class='fa fa-info-circle'></i> " . $errors->first($col['name']) : '' !!}</div>
                                                            <p class='help-block'>{{ @$form['help'] }}</p>

                                                        </div>
                                                    </div>
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
                                                            $('input[name="{{ $col['name'] }}"]').on('click', function() {
                                                                if ($(this).val() == 0) {
                                                                    $(this).val(1);
                                                                } else {
                                                                    $(this).val(0);
                                                                }
                                                            });
                                                        </script>
                                                    @endpush
                                                @elseif($col['type'] == 'icon')
                                                    @php
                                                        $fonts = crocodicstudio\crudbooster\fonts\Fontawesome::getIcons();
                                                    @endphp
                                                    <div class='form-group {{ $header_group_class }} {{ $errors->first($col['name']) ? 'has-error' : '' }}'
                                                        id='form-group' style="{{ @$form['style'] }}">
                                                        <div class="{{ $col_width ?: 'col-sm-10' }}">
                                                            <select id='list-icon_{{ $col['name'] }}'
                                                                class="form-control"
                                                                name="child_icon_{{ $col['name'] }}"
                                                                style="font-family: 'FontAwesome', Helvetica;">
                                                                <option value="">** Select an Icon</option>
                                                                @foreach ($fonts as $font)
                                                                    <option value='fa fa-{{ $font }}'
                                                                        {{ $value == "fa fa-$font" ? 'selected' : '' }}
                                                                        data-label='{{ $font }}'>
                                                                        {{ $font }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @push('head')
                                                        <link
                                                            href="{{ asset('vendor/crudbooster/assets/select2/dist/css/select2.min.css') }}"
                                                            rel="stylesheet" type="text/css" />
                                                    @endpush

                                                    @push('bottom')
                                                        <script src="{{ asset('vendor/crudbooster/assets/select2/dist/js/select2.full.min.js') }}"></script>
                                                        <script>
                                                            $(function() {
                                                                function format(icon) {
                                                                    var originalOption = icon.element;
                                                                    var label = $(originalOption).text();
                                                                    var val = $(originalOption).val();
                                                                    if (!val) return label;
                                                                    var $resp = $('<span><i style="margin-top:5px" class="pull-right ' + $(originalOption).val() +
                                                                        '"></i> ' +
                                                                        $(originalOption).data('label') + '</span>');
                                                                    return $resp;
                                                                }

                                                                $('#list-icon_{{ $col['name'] }}').select2({
                                                                    width: "100%",
                                                                    templateResult: format,
                                                                    templateSelection: format
                                                                });
                                                            });
                                                        </script>
                                                    @endpush
                                                @elseif($col['type'] == 'radio')
                                                    <?php
                                                    if($col['dataenum']):
                                                    $dataenum = $col['dataenum'];
                                                    if (strpos($dataenum, ';') !== false) {
                                                        $dataenum = explode(";", $dataenum);
                                                    } else {
                                                        $dataenum = [$dataenum];
                                                    }
                                                    array_walk($dataenum, 'trim');
                                                    foreach($dataenum as $e=>$enum):
                                                    $enum = explode('|', $enum);
                                                    if (count($enum) == 2) {
                                                        $radio_value = $enum[0];
                                                        $radio_label = $enum[1];
                                                    } else {
                                                        $radio_value = $radio_label = $enum[0];
                                                    }
                                                    ?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="child-{{ $col['name'] }}"
                                                            class='{{ $e == 0 && $col['required'] ? 'required' : '' }} {{ $name_column }}'
                                                            value="{{ $radio_value }}"{{ $e == 0 && $col['required'] ? ' checked' : '' }}>
                                                        {{ $radio_label }}
                                                    </label>
                                                    <?php endforeach;?>
                                                    <?php endif;?>
                                                @elseif($col['type'] == 'datamodal')
                                                    <div id='{{ $name_column }}' class="input-group">
                                                        <input type="hidden" class="input-id">
                                                        <input type="text"
                                                            class="form-control input-label {{ $col['required'] ? 'required' : '' }}"
                                                            readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-primary"
                                                                onclick="showModal{{ $name_column }}()"
                                                                type="button"><i class='fa fa-search'></i>
                                                                {{ cbLang('datamodal_browse_data') }}</button>
                                                        </span>
                                                    </div><!-- /input-group -->

                                                    @push('bottom')
                                                        <script type="text/javascript">
                                                            var url_{{ $name_column }} =
                                                                "{{ CRUDBooster::mainpath('modal-data') }}?table={{ $col['datamodal_table'] }}&columns=id,{{ $col['datamodal_columns'] }}&name_column={{ $name_column }}&where={{ urlencode($col['datamodal_where']) }}&select_to={{ urlencode($col['datamodal_select_to']) }}&columns_name_alias={{ urlencode($col['datamodal_columns_alias']) }}";
                                                            var url_is_setted_{{ $name_column }} = false;

                                                            function showModal{{ $name_column }}() {
                                                                if (url_is_setted_{{ $name_column }} == false) {
                                                                    url_is_setted_{{ $name_column }} = true;
                                                                    $('#panel-form-{{ $name }} #iframe-modal-{{ $name_column }}').attr('src',
                                                                        url_{{ $name_column }});
                                                                }
                                                                $('#panel-form-{{ $name }} #modal-datamodal-{{ $name_column }}').modal('show');
                                                            }

                                                            function hideModal{{ $name_column }}() {
                                                                $('#panel-form-{{ $name }} #modal-datamodal-{{ $name_column }}').modal('hide');
                                                            }

                                                            function selectAdditionalData{{ $name_column }}(select_to_json) {
                                                                $.each(select_to_json, function(key, val) {
                                                                    if (key == 'datamodal_id') {
                                                                        $('#panel-form-{{ $name }} #{{ $name_column }} .input-id').val(val);
                                                                    }
                                                                    if (key == 'datamodal_label') {
                                                                        $('#panel-form-{{ $name }} #{{ $name_column }} .input-label').val(val);
                                                                    }
                                                                    $('#panel-form-{{ $name }} #{{ $name }}' + key).val(val).trigger('change');
                                                                })
                                                                hideModal{{ $name_column }}();
                                                            }
                                                        </script>
                                                    @endpush

                                                    <div id='modal-datamodal-{{ $name_column }}' class="modal"
                                                        tabindex="-1" role="dialog">
                                                        <div class="modal-dialog {{ $col['datamodal_size'] == 'large' ? 'modal-lg' : '' }} "
                                                            role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close"><span
                                                                            aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><i
                                                                            class='fa fa-search'></i>
                                                                        {{ cbLang('datamodal_browse_data') }}
                                                                        {{ $col['label'] }}
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <iframe id='iframe-modal-{{ $name_column }}'
                                                                        style="border:0;height: 430px;width: 100%"
                                                                        src=""></iframe>
                                                                </div>

                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                @elseif($col['type'] == 'multitext')
                                                    <div class='form-group {{ $col['name'] }}'
                                                        id='form-group-{{ $col['name'] }}'
                                                        style="{{ @$form['style'] }}">
                                                        <div
                                                            class='{{ $col_width ?: 'col-sm-10' }} input_fields_wrap {{ $col['name'] }}'>

                                                            <div class="input-group">
                                                                <input type='text' title="{{ $col['label'] }}"
                                                                    {{ $required }} {{ $readonly }}
                                                                    {!! $placeholder !!} {{ $disabled }}
                                                                    {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }}
                                                                    class='form-control {{ $col['name'] }} first_value'
                                                                    name="{{ $col['name'] }}[]"
                                                                    id="{{ $col['name'] }}"
                                                                    value='{{ $col['value'] }}' /> <span
                                                                    class="input-group-addon"
                                                                    style="padding: 1px;"><button
                                                                        class="add_field_button {{ $col['name'] }}  btn btn-danger  btn-xs"><i
                                                                            class='fa fa-plus'></i></button></span>
                                                            </div>

                                                            <div class="text-danger">{!! $errors->first($col['name']) ? "<i class='fa fa-info-circle'></i> " . $errors->first($col['name']) : '' !!}</div>
                                                            <p class='help-block'>{{ $col['help'] }}</p>

                                                        </div>
                                                    </div>
                                                    @push('bottom')
                                                        <script>
                                                            $(document).ready(function() {
                                                                var max_fields_{{ $col['name'] }} = "{{ @$col['max_fields'] }}";
                                                                var max_fields_{{ $col['name'] }} = parseInt(max_fields_{{ $col['name'] }}) ?
                                                                    max_fields_{{ $col['name'] }} : 5; //maximum input boxes allowed
                                                                var wrapper_{{ $col['name'] }} = $(".input_fields_wrap").filter(
                                                                    '.{{ $col['name'] }}'); //Fields wrapper
                                                                var add_button_{{ $col['name'] }} = $(".add_field_button").filter(
                                                                    '.{{ $col['name'] }}'); //Add button ID


                                                                var count_{{ $col['name'] }} = 1; //initlal text box count
                                                                $(add_button_{{ $col['name'] }}).click(function(e) { //on add input button click
                                                                    e.preventDefault();
                                                                    if (count_{{ $col['name'] }} <
                                                                        max_fields_{{ $col['name'] }}) { //max input box allowed
                                                                        count_{{ $col['name'] }}++; //text box increment
                                                                        $(wrapper_{{ $col['name'] }}).append(
                                                                            '<div><input class="form-control {{ $col['name'] }}" type="text" name="{{ $col['name'] }}[]"/><a href="#" class="remove_field {{ $col['name'] }}"><i class="fa fa-minus"></a></div>'
                                                                        ); //add input box
                                                                    }
                                                                });

                                                                $(wrapper_{{ $col['name'] }}).on("click", ".remove_field ", function(e) { //user click on remove text
                                                                    e.preventDefault();
                                                                    $(this).parent('div').remove();
                                                                    count_{{ $col['name'] }}--;
                                                                })

                                                                function Load() {
                                                                    var val = "{{ $col['value'] }}";
                                                                    val = val.split("|");
                                                                    $(".first_value").filter(".{{ $col['name'] }}").val(val[0]);
                                                                    for (i = 1; i < val.length; i++) {
                                                                        $(wrapper_{{ $col['name'] }}).append(
                                                                            ' <div > <input class="form-control" {{ $required }} {{ $readonly }} {!! $placeholder !!} {{ $disabled }} {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }}  type="text" name="{{ $col['name'] }}[]" value="' +

                                                                            val[i] +
                                                                            '"/><a href="#" class="remove_field {{ $col['name'] }}"><i class="fa fa-minus"></a></div>'
                                                                        ); //add input box
                                                                    }
                                                                }

                                                                Load();
                                                            });
                                                        </script>
                                                    @endpush
                                                @elseif($col['type'] == 'number')
                                                    <input id='{{ $name_column }}' type='number'
                                                        {{ $col['min'] ? "min='" . $col['min'] . "'" : '' }}
                                                        {{ $col['max'] ? "max='$col[max]'" : '' }}
                                                        name='child-{{ $col['name'] }}'
                                                        class='form-control {{ $col['required'] ? 'required' : '' }}'
                                                        {{ $col['readonly'] === true ? 'readonly' : '' }} />
                                                @elseif($col['type'] == 'textarea')
                                                    <textarea id='{{ $name_column }}' name='child-{{ $col['name'] }}'
                                                        class='form-control {{ $col['required'] ? 'required' : '' }}'
                                                        {{ $col['readonly'] === true ? 'readonly' : '' }}></textarea>
                                                @elseif($col['type'] == 'upload')
                                                    <div id='{{ $name_column }}' class="input-group">
                                                        <input type="hidden" class="input-id">
                                                        <input type="text"
                                                            class="form-control input-label {{ $col['required'] ? 'required' : '' }}"
                                                            readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-primary"
                                                                id="btn-upload-{{ $name_column }}"
                                                                onclick="showFakeUpload{{ $name_column }}()"
                                                                type="button"><i class='fa fa-search'></i>
                                                                {{ cbLang('datamodal_browse_file') }}</button>
                                                        </span>
                                                    </div><!-- /input-group -->

                                                    <div id="loading-{{ $name_column }}" class='text-info'
                                                        style="display: none">
                                                        <i class='fa fa-spin fa-spinner'></i>
                                                        {{ cbLang('text_loading') }}
                                                    </div>

                                                    <input type="file" id='fake-upload-{{ $name_column }}'
                                                        style="display: none">
                                                    @push('bottom')
                                                        <script type="text/javascript">
                                                            var file;
                                                            var filename;
                                                            var is_uploading = false;

                                                            function showFakeUpload{{ $name_column }}() {
                                                                if (is_uploading) {
                                                                    return false;
                                                                }

                                                                $('#fake-upload-{{ $name_column }}').click();
                                                            }

                                                            // Add events
                                                            $('#fake-upload-{{ $name_column }}').on('change', prepareUpload{{ $name_column }});

                                                            // Grab the files and set them to our variable
                                                            function prepareUpload{{ $name_column }}(event) {
                                                                var max_size = {{ $col['max'] ?: 2000 }};
                                                                file = event.target.files[0];

                                                                var filesize = Math.round(parseInt(file.size) / 1024);

                                                                if (filesize > max_size) {
                                                                    sweetAlert('{{ cbLang('alert_warning') }}', '{{ cbLang('your_file_size_is_too_big') }}', 'warning');
                                                                    return false;
                                                                }

                                                                filename = $('#fake-upload-{{ $name_column }}').val().replace(/C:\\fakepath\\/i, '');
                                                                var extension = filename.split('.').pop().toLowerCase();
                                                                var img_extension = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                                                                var available_extension = "{{ config('crudbooster.UPLOAD_TYPES') }}".split(",");
                                                                var is_image_only = {{ $col['upload_type'] == 'image' ? 'true' : 'false' }};

                                                                if (is_image_only) {
                                                                    if ($.inArray(extension, img_extension) == -1) {
                                                                        sweetAlert('{{ cbLang('alert_warning') }}', '{{ cbLang('your_file_extension_is_not_allowed') }}',
                                                                            'warning');
                                                                        return false;
                                                                    }
                                                                } else {
                                                                    if ($.inArray(extension, available_extension) == -1) {
                                                                        sweetAlert('{{ cbLang('alert_warning') }}', '{{ cbLang('your_file_extension_is_not_allowed') }}!',
                                                                            'warning');
                                                                        return false;
                                                                    }
                                                                }


                                                                $('#panel-form-{{ $name }} #{{ $name_column }} .input-label').val(filename);

                                                                $('#panel-form-{{ $name }} #loading-{{ $name_column }}').fadeIn();
                                                                $('#panel-form-{{ $name }} #btn-add-table-{{ $name }}').addClass('disabled');
                                                                $('#panel-form-{{ $name }} #btn-upload-{{ $name_column }}').addClass('disabled');
                                                                is_uploading = true;

                                                                //Upload File To Server
                                                                uploadFiles{{ $name_column }}(event);
                                                            }

                                                            function uploadFiles{{ $name_column }}(event) {
                                                                event.stopPropagation(); // Stop stuff happening
                                                                event.preventDefault(); // Totally stop stuff happening

                                                                // START A LOADING SPINNER HERE

                                                                // Create a formdata object and add the files
                                                                var data = new FormData();
                                                                data.append('userfile', file);

                                                                $.ajax({
                                                                    url: '{{ CRUDBooster::mainpath('upload-file') }}',
                                                                    type: 'POST',
                                                                    data: data,
                                                                    cache: false,
                                                                    processData: false, // Don't process the files
                                                                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                                                    success: function(data, textStatus, jqXHR) {
                                                                        $('#panel-form-{{ $name }} #btn-add-table-{{ $name }}').removeClass(
                                                                            'disabled');
                                                                        $('#panel-form-{{ $name }} #loading-{{ $name_column }}').hide();
                                                                        $('#panel-form-{{ $name }} #btn-upload-{{ $name_column }}').removeClass(
                                                                            'disabled');
                                                                        is_uploading = false;

                                                                        var basename = data.split('/').reverse()[0];
                                                                        $('#panel-form-{{ $name }} #{{ $name_column }} .input-label').val(basename);

                                                                        $('#panel-form-{{ $name }} #{{ $name_column }} .input-id').val(data);
                                                                    },
                                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                                        $('#panel-form-{{ $name }} #btn-add-table-{{ $name }}').removeClass(
                                                                            'disabled');
                                                                        $('#panel-form-{{ $name }} #btn-upload-{{ $name_column }}').removeClass(
                                                                            'disabled');
                                                                        is_uploading = false;
                                                                        // Handle errors here
                                                                        // STOP LOADING SPINNER
                                                                        $('#panel-form-{{ $name }} #loading-{{ $name_column }}').hide();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    @endpush
                                                @elseif($col['type'] == 'select')
                                                    @if ($col['parent_select'])
                                                        @push('bottom')
                                                            <script type="text/javascript">
                                                                $(function() {
                                                                    $("#{{ $name . $col['parent_select'] }} , #{{ $name . $col['name'] }}").select2("destroy");

                                                                    $('#{{ $name . $col['parent_select'] }}, input:radio[name={{ $name . $col['parent_select'] }}]')
                                                                        .change(
                                                                            function() {
                                                                                var $current = $("#{{ $name . $col['name'] }}");
                                                                                var parent_id = $(this).val();
                                                                                var fk_name = "{{ $col['parent_select'] }}";
                                                                                var fk_value = $('#{{ $name . $col['parent_select'] }}').val();
                                                                                var datatable = "{{ $col['datatable'] }}".split(',');
                                                                                var datatableWhere = "{{ $col['datatable_where'] }}";
                                                                                var table = datatable[0].trim('');
                                                                                var label = datatable[1].trim('');
                                                                                var value = "{{ $value }}";

                                                                                if (fk_value != '') {
                                                                                    $current.html("<option value=''>{{ cbLang('text_loading') }} {{ $col['label'] }}");
                                                                                    $.get("{{ CRUDBooster::mainpath('data-table') }}?table=" + table + "&label=" + label +
                                                                                        "&fk_name=" + fk_name + "&fk_value=" + fk_value + "&datatable_where=" +
                                                                                        encodeURI(datatableWhere),
                                                                                        function(response) {
                                                                                            if (response) {
                                                                                                $current.html("<option value=''>{{ $default }}");
                                                                                                $.each(response, function(i, obj) {
                                                                                                    var selected = (value && value == obj.select_value) ?
                                                                                                        "selected" : "";
                                                                                                    $("<option " + selected + " value='" + obj.select_value +
                                                                                                        "'>" + obj.select_label + "</option>").appendTo(
                                                                                                        "#{{ $name . $col['name'] }}");
                                                                                                });
                                                                                                $current.trigger('change');
                                                                                            }
                                                                                        });
                                                                                } else {
                                                                                    $current.html("<option value=''>{{ $default }}");
                                                                                }
                                                                            });

                                                                    $('#{{ $name . $col['parent_select'] }}').trigger('change');
                                                                    $("#{{ $name . $col['name'] }}").trigger('change');

                                                                    $("#{{ $name . $col['parent_select'] }} , #{{ $name . $col['name'] }}").select2();

                                                                })
                                                            </script>
                                                        @endpush
                                                    @endif

                                                    <select id='{{ $name_column }}'
                                                        name='child-{{ $col['name'] }}'
                                                        class='form-control select2 {{ $col['required'] ? 'required' : '' }}'
                                                        {{ $col['readonly'] === true ? 'readonly' : '' }}>
                                                        <option value=''>{{ cbLang('text_prefix_option') }}
                                                            {{ $col['label'] }}</option>
                                                        <?php
                                                        if ($col['datatable']) {
                                                            $tableJoin = explode(',', $col['datatable'])[0];
                                                            $titleField = explode(',', $col['datatable'])[1];
                                                            if (!$col['datatable_where']) {
                                                                $data = CRUDBooster::get($tableJoin, null, "$titleField ASC");
                                                            } else {
                                                                $data = CRUDBooster::get($tableJoin, $col['datatable_where'], "$titleField ASC");
                                                            }
                                                            foreach ($data as $d) {
                                                                echo "<option value='$d->id'>" . $d->$titleField . '</option>';
                                                            }
                                                        } else {
                                                            $data = $col['dataenum'];
                                                            $data = is_array($data) ? $data : explode(';', $data);
                                                            foreach ($data as $d) {
                                                                $enum = explode('|', $d);
                                                                if (count($enum) == 2) {
                                                                    $opt_value = $enum[0];
                                                                    $opt_label = $enum[1];
                                                                } else {
                                                                    $opt_value = $opt_label = $enum[0];
                                                                }
                                                                echo "<option value='$opt_value'>$opt_label</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                @elseif($col['type'] == 'hidden')
                                                    <input type="{{ $col['type'] }}"
                                                        id="{{ $name . $col['name'] }}"
                                                        name="child-{{ $name . $col['name'] }}"
                                                        value="{{ $col['value'] }}">
                                                @endif

                                                @if ($col['help'])
                                                    @if ($col['type'] == 'filemanager')
                                                        <div class='help-block' style="width:54%;text-align:center;">
                                                            {{ $col['help'] }}
                                                        </div>
                                                    @else
                                                        <div class='help-block'>
                                                            {{ $col['help'] }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        @if ($col['formula'])
                                            <?php
                                            $formula = $col['formula'];
                                            $formula_function_name = 'formula' . str_slug($name . $col['name'], '');
                                            $script_onchange = '';
                                            foreach ($form['columns'] as $c) {
                                                if (strpos($formula, '[' . $c['name'] . ']') !== false) {
                                                    $script_onchange .= "$('#$name$c[name]').change(function() {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $formula_function_name();});";
                                                }
                                                $formula = str_replace('[' . $c['name'] . ']', "\$('#" . $name . $c['name'] . "').val()", $formula);
                                            }
                                            ?>
                                            @push('bottom')
                                                <script type="text/javascript">
                                                    function {{ $formula_function_name }}() {
                                                        var v = {!! $formula !!};
                                                        $('#{{ $name_column }}').val(v);
                                                    }

                                                    $(function() {
                                                        {!! $script_onchange !!}
                                                    })
                                                </script>
                                            @endpush
                                        @endif
                                    @endforeach

                                    @push('bottom')
                                        <script type="text/javascript">
                                            var currentRow = null;

                                            function resetForm{{ $name }}() {
                                                $('#panel-form-{{ $name }}').find("input[type=text],input[type=number],select,textarea").val('');
                                                $('#panel-form-{{ $name }}').find(".select2").val('').trigger('change');
                                                $('#panel-form-{{ $name }}').find("input[type=checkbox]").val(0);
                                                $('#panel-form-{{ $name }}').find("input[type=checkbox]").prop('checked', false);

                                                $('.child-form-area-{{ $name }}').find('a:not(span a)').prop("href", "").addClass("hide");
                                                $('.child-form-area-{{ $name }}').find('img').prop("src", "");
                                            }

                                            function deleteRow{{ $name }}(t) {

                                                if (confirm("{{ cbLang('delete_title_confirm') }}")) {
                                                    $(t).parent().parent().remove();
                                                    if ($('#panel-form-{{ $name }} #table-{{ $name }} tbody tr').length == 0) {
                                                        var colspan = $('#table-{{ $name }} thead tr th').length;
                                                        $('#panel-form-{{ $name }} #table-{{ $name }} tbody').html(
                                                            "<tr class='trNull'><td colspan='" + colspan +
                                                            "' align='center'>{{ cbLang('table_data_not_found') }}</td></tr>");
                                                    }
                                                }
                                            }

                                            function editRow{{ $name }}(t) {
                                                var p = $(t).parent().parent(); //parentTR
                                                currentRow = p;
                                                let currValue = currentRow.find('input[name="{{ $name }}-id[]"]').val();
                                                $('.hidden-value').attr("value", currValue);
                                                p.addClass('warning');
                                                $('#panel-form-{{ $name }} #btn-add-table-{{ $name }}').val(
                                                    '{{ cbLang('save_changes') }}');
                                                @foreach ($form['columns'] as $c)
                                                    @if ($c['type'] == 'filemanager')
                                                        pSRC = p.find($('.tb_img-{{ $c['name'] }}')).attr("src");
                                                        pSRC = pSRC.replace("{{ url('/') }}", "");
                                                        if (pSRC.charAt(0) !== '/')
                                                            pSRC = "/".pSRC;
                                                        //---------------------------------------//
                                                        $('#panel-form-{{ $name }} #link-{{ $c['name'] }}').removeClass('hide');
                                                        $('#panel-form-{{ $name }} #link-{{ $c['name'] }}').attr('href', pSRC);
                                                        $('#panel-form-{{ $name }} #img-{{ $c['name'] }}').attr('src', pSRC);
                                                    @elseif ($c['type'] == 'switch')
                                                        var s_val = p.find($('.{{ $c['name'] }} input[type=hidden]')).attr('value');
                                                        if (s_val == "1") {
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').val(1);
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').prop('checked', true);
                                                        } else {
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').val(0);
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').prop('checked', false);
                                                        }
                                                    @elseif ($c['type'] == 'icon')
                                                        var icon_value = p.find($('.{{ $c['name'] }} input[type=hidden]')).val();
                                                        $('#panel-form-{{ $name }} #list-icon_{{ $c['name'] }}').val(icon_value).trigger(
                                                            'change');
                                                    @elseif ($c['type'] == 'select')
                                                        $('#panel-form-{{ $name }} #{{ $name . $c['name'] }}').val(p.find(
                                                            ".{{ $c['name'] }} input").val()).trigger("change");
                                                    @elseif ($c['type'] == 'radio')
                                                        var v = p.find(".{{ $c['name'] }} input").val();
                                                        $('.{{ $name . $c['name'] }}[value=' + v + ']').prop('checked', true);
                                                    @elseif ($c['type'] == 'datamodal')
                                                        $('#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val(p.find(
                                                            ".{{ $c['name'] }} .td-label").text());
                                                        $('#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val(p.find(
                                                            ".{{ $c['name'] }} input").val());
                                                    @elseif ($c['type'] == 'multitext')
                                                        var values = p.find($('input[name="{{ $name }}-{{ $c['name'] }}[]"]')).val();
                                                        var valuesArr = values.split('|');
                                                        $('input[name="{{ $col['name'] }}[]"]').each(function(index) {
                                                            if (index > 0) $(this).parent().remove();
                                                        });
                                                        for (var i = 1; i < valuesArr.length; i++) {
                                                            $('.input_fields_wrap.{{ $col['name'] }}').append(`
                                                            <div>
                                                                <input class='form-control {{ $col['name'] }}' type='text' name='{{ $col['name'] }}[]'>
                                                                <a href="#" class='remove_field {{ $col['name'] }}'>
                                                                    <i class="fa fa-minus"></i>
                                                                </a>
                                                            </div>
                                                            `);
                                                        }

                                                        $('input[name="{{ $col['name'] }}[]"]').each(function(index) {
                                                            $(this).val(valuesArr[index]);
                                                        });
                                                    @elseif ($c['type'] == 'upload')
                                                        @if ($c['upload_type'] == 'image')
                                                            $('#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val(p.find(
                                                                ".{{ $c['name'] }} img").data(
                                                                'label'));
                                                        @else
                                                            $('#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val(p.find(
                                                                ".{{ $c['name'] }} a").data('label'));
                                                        @endif
                                                        $('#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val(p.find(
                                                            ".{{ $c['name'] }} input").val());
                                                    @else
                                                        $('#panel-form-{{ $name }} #{{ $name . $c['name'] }}').val(p.find(
                                                            ".{{ $c['name'] }} input").val());
                                                    @endif
                                                @endforeach
                                            }

                                            function validateForm{{ $name }}() {
                                                var is_false = 0;
                                                $('#panel-form-{{ $name }} .required').each(function() {
                                                    var v = $(this).val();
                                                    if (v == '') {
                                                        sweetAlert("{{ cbLang('alert_warning') }}", "{{ cbLang('please_complete_the_form') }}",
                                                            "warning");
                                                        is_false += 1;
                                                    }
                                                })

                                                if (is_false == 0) {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }

                                            function addToTable{{ $name }}() {
                                                if (validateForm{{ $name }}() == false) {
                                                    return false;
                                                }
                                                var trRow = '<tr>';
                                                let currValue = $('.hidden-value').attr("value")
                                                $('.hidden-value').attr("value", ''); // to avoid scenario of edit exist child then add new child
                                                if (typeof currValue == 'undefined') currValue = '';
                                                trRow += "<input type='hidden' name='{{ $name }}-id[]' value='" + currValue + "'/>";

                                                @foreach ($form['columns'] as $c)
                                                    <?php $inputId = "{$name}-{$c['name']}"; ?>
                                                    @if ($c['type'] == 'hidden' && strpos($c['name'], 'webp') != false)
                                                        trRow +=
                                                            "<input <?= $currValue !== '' ? "id='$inputId'" : '' ?> type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='{{ $currValue }}'>";
                                                    @elseif ($c['type'] == 'filemanager')
                                                        pSRC = $('#panel-form-{{ $name }} #img-{{ $c['name'] }}').attr('src');
                                                        pSRC = pSRC.replace("{{ url('/') }}", "");
                                                        if (pSRC.charAt(0) !== '/')
                                                            pSRC = "/".pSRC;
                                                        trRow += "<td class='{{ $c['name'] }}'>" +
                                                            "<a data-lightbox='roadtrip' href='" + pSRC + "'><img class='tb_img-" + '{{ $c['name'] }}' +
                                                            "' data-label='" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val() +
                                                            "' src='" + pSRC +
                                                            "' width='50px' height='50px'/></a>" +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + pSRC + "'/>" +

                                                            "</td>"

                                                        pSRC = $('#panel-form-{{ $name }} #img-{{ $c['name'] }}').attr('src');

                                                        //---------------------------------------//
                                                        //convert to webp
                                                        var imageUrl = pSRC;
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
                                                                    if (currValue != '') {
                                                                        $('input[name="{{ $name }}-id[]"]').each(function() {
                                                                            if ($(this).val() === currValue) {
                                                                                var row = $(this).closest('tr');
                                                                                row.find(
                                                                                    'input[name="{{ $name }}-{{ $c['name'] }}_webp[]"]'
                                                                                ).val(base64Data);
                                                                            }
                                                                        });
                                                                    } else {
                                                                        $('#{{ $name }}-{{ $c['name'] }}_webp').val(base64Data);
                                                                    }
                                                                    //---------------------------------------//
                                                                    var webpImageElement = document.createElement('img');
                                                                    webpImageElement.src = base64Data;
                                                                };
                                                                reader.readAsDataURL(blob);
                                                            }, 'image/webp');
                                                        };
                                                        img.src = imageUrl;
                                                        //---------------------------------------//
                                                        $('#panel-form-{{ $name }} #link-{{ $c['name'] }}').addClass('hide');
                                                        $('#panel-form-{{ $name }} #link-{{ $c['name'] }}').attr("href", "");
                                                        $('#panel-form-{{ $name }} #img-{{ $c['name'] }}').attr("src", "");
                                                    @elseif ($c['type'] == 'switch')
                                                        trRow += "<td class='{{ $c['name'] }}' value='" +
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').val() + "'>" + $(
                                                                '#panel-form-{{ $name }} #child_switch{{ $c['name'] }} ')
                                                            .val() +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" +
                                                            $('#panel-form-{{ $name }} #child_switch{{ $c['name'] }}').val() + "'/>" +
                                                            "</td>";
                                                    @elseif ($c['type'] == 'icon')
                                                        trRow += "<td class='{{ $c['name'] }}' value='" +
                                                            $('select[name=child_icon_{{ $c['name'] }}]').val() + "'>" +
                                                            $('select[name=child_icon_{{ $c['name'] }}]').val() +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" +
                                                            $('select[name=child_icon_{{ $c['name'] }}]').val() + "'/>" +
                                                            "</td>";
                                                        $('#panel-form-{{ $name }} #list-icon_{{ $c['name'] }}').val('').trigger("change");
                                                    @elseif ($c['type'] == 'multitext')
                                                        var values = $('input[name="{{ $col['name'] }}[]"]').map(function() {
                                                            return $(this).val();
                                                        }).get().join('|');
                                                        trRow += "<td class='{{ $c['name'] }}'>" + values +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + values +
                                                            "'/>" +
                                                            "</td>";
                                                        $('input[name="{{ $c['name'] }}[]"]').each(function(index) {
                                                            if (index > 0) $(this).parent().remove();
                                                        });
                                                    @elseif ($c['type'] == 'select')
                                                        trRow += "<td class='{{ $c['name'] }}'>" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }} option:selected')
                                                            .text() +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }}').val() + "'/>" +
                                                            "</td>";
                                                    @elseif ($c['type'] == 'radio')
                                                        trRow += "<td class='{{ $c['name'] }}'><span class='td-label'>" + $(
                                                                '.{{ $name . $c['name'] }}:checked').val() + "</span>" +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                '.{{ $name . $c['name'] }}:checked').val() + "'/>" +
                                                            "</td>";
                                                    @elseif ($c['type'] == 'datamodal')
                                                        trRow += "<td class='{{ $c['name'] }}'><span class='td-label'>" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val() +
                                                            "</span>" +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() + "'/>" +
                                                            "</td>";
                                                    @elseif ($c['type'] == 'upload')
                                                        @if ($c['upload_type'] == 'image')
                                                            trRow += "<td class='{{ $c['name'] }}'>" +
                                                                "<a data-lightbox='roadtrip' href='{{ asset('/') }}" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() +
                                                                "'><img data-label='" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val() +
                                                                "' src='{{ asset('/') }}" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() +
                                                                "' width='50px' height='50px'/></a>" +
                                                                "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() + "'/>" +
                                                                "</td>";
                                                        @else
                                                            trRow += "<td class='{{ $c['name'] }}'><a data-label='" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val() +
                                                                "' href='{{ asset('/') }}" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() + "'>" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-label').val() +
                                                                "</a>" +
                                                                "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                    '#panel-form-{{ $name }} #{{ $name . $c['name'] }} .input-id').val() + "'/>" +
                                                                "</td>";
                                                        @endif
                                                    @else
                                                        trRow += "<td class='{{ $c['name'] }}'>" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }}').val() +
                                                            "<input type='hidden' name='{{ $name }}-{{ $c['name'] }}[]' value='" + $(
                                                                '#panel-form-{{ $name }} #{{ $name . $c['name'] }}').val() + "'/>" +
                                                            "</td>";
                                                    @endif
                                                @endforeach
                                                trRow += "<td>" +
                                                    "<a href='#panel-form-{{ $name }}' onclick='editRow{{ $name }}(this)' class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i></a> " +
                                                    "<a href='javascript:void(0)' onclick='deleteRow{{ $name }}(this)' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a></td>";
                                                trRow += '</tr>';
                                                $('#panel-form-{{ $name }} #table-{{ $name }} tbody .trNull').remove();
                                                if (currentRow == null) {
                                                    $("#table-{{ $name }} tbody").prepend(trRow);
                                                } else {
                                                    currentRow.removeClass('warning');
                                                    currentRow.replaceWith(trRow);
                                                    currentRow = null;
                                                }
                                                $('#panel-form-{{ $name }} #btn-add-table-{{ $name }}').val(
                                                    '{{ cbLang('button_add_to_table') }}');
                                                $('#panel-form-{{ $name }} #btn-reset-form-{{ $name }}').click();
                                            }
                                        </script>
                                    @endpush
                                </div>
                                <div class="panel-footer" align="right">
                                    <input type='button' class='btn btn-default'
                                        id="btn-reset-form-{{ $name }}"
                                        onclick="resetForm{{ $name }}()"
                                        value='{{ cbLang('button_reset') }}' />
                                    <input type='button' id='btn-add-table-{{ $name }}'
                                        class='btn btn-primary' onclick="addToTable{{ $name }}()"
                                        value='{{ cbLang('button_add_to_table') }}' />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class='fa fa-table'></i> {{ cbLang('table_detail') }}
                        </div>
                        <div class="panel-body no-padding table-responsive" style="max-height: 400px;overflow: auto;">
                            <table id='table-{{ $name }}' class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        @foreach ($form['columns'] as $col_key => $col)
                                            @continue($col['type'] == 'hidden' && strpos($col['name'], 'webp') != false)
                                            <th>{{ $col['label'] }}</th>
                                        @endforeach
                                        <th width="90px">{{ cbLang('action_label') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                $columns_tbody = [];
                                $data_child = DB::table($form['table'])->where($form['foreign_key'], $id);
                                foreach ($form['columns'] as $i => $c) {
                                    $data_child->addselect($form['table'].'.'.$c['name']);
                                    $data_child->addselect($form['table'].'.id');

                                    if ($c['type'] == 'datamodal') {
                                        $datamodal_title = explode(',', $c['datamodal_columns'])[0];
                                        $datamodal_table = $c['datamodal_table'];
                                        $data_child->join($c['datamodal_table'], $c['datamodal_table'].'.id', '=', $c['name']);
                                        $data_child->addselect($c['datamodal_table'].'.'.$datamodal_title.' as '.$datamodal_table.'_'.$datamodal_title);
                                    } elseif ($c['type'] == 'select') {
                                        if ($c['datatable']) {
                                            $join_table = explode(',', $c['datatable'])[0];
                                            $join_field = explode(',', $c['datatable'])[1];
                                            $data_child->join($join_table, $join_table.'.id', '=', $c['name']);
                                            $data_child->addselect($join_table.'.'.$join_field.' as '.$join_table.'_'.$join_field);
                                        }

                                    }
                                }
                                if(Crudbooster::isColumnExists($form['table'],"sorting")){
                                    $data_child = $data_child->orderby($form['table'].'.sorting', 'asc')->get();
                                } else {
                                    $data_child = $data_child->orderby($form['table'].'.id', 'desc')->get();
                                }
                                foreach($data_child as $d):
                                ?>
                                    <tr>
                                        <input type='hidden' name='{{ $name }}-id[]'
                                            value='{{ $d->id }}' />
                                        @foreach ($form['columns'] as $col)
                                            <?php //for webp images
                                            if ($col['type'] == 'hidden' && strpos($col['name'], 'webp') != false) {
                                                echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                continue;
                                            }
                                            ?> <td class="{{ $col['name'] }}">
                                                <?php
                                                if ($col['type'] == 'filemanager') {
                                                    $tempLink = $d->{$col['name']};
                                                    $tempLink = str_replace(url('/'), '', $tempLink);
                                                    if (strpos($tempLink, '/') !== 0) {
                                                        $tempLink = "/$tempLink";
                                                    }
                                                    echo "<a data-lightbox='roadtrip' href='" . $tempLink . "'>";
                                                    echo "<img data-label='undefined' class='tb_img-" . $col['name'] . "' src='" . $tempLink . "' width='50px' height='50px'>";
                                                    echo '</a>';
                                                    echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                } elseif ($col['type'] == 'select') {
                                                    if ($col['datatable']) {
                                                        $join_table = explode(',', $col['datatable'])[0];
                                                        $join_field = explode(',', $col['datatable'])[1];
                                                        echo "<span class='td-label'>";
                                                        echo $d->{$join_table . '_' . $join_field};
                                                        echo '</span>';
                                                        echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                    }
                                                    if ($col['dataenum']) {
                                                        echo "<span class='td-label'>";
                                                        echo $d->{$col['name']};
                                                        echo '</span>';
                                                        echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                    }
                                                } elseif ($col['type'] == 'datamodal') {
                                                    $datamodal_title = explode(',', $col['datamodal_columns'])[0];
                                                    $datamodal_table = $col['datamodal_table'];
                                                    echo "<span class='td-label'>";
                                                    echo $d->{$datamodal_table . '_' . $datamodal_title};
                                                    echo '</span>';
                                                    echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                } elseif ($col['type'] == 'upload') {
                                                    $filename = basename($d->{$col['name']});
                                                    if ($col['upload_type'] == 'image') {
                                                        echo "<a href='" . asset($d->{$col['name']}) . "' data-lightbox='roadtrip'><img data-label='$filename' src='" . asset($d->{$col['name']}) . "' width='50px' height='50px'/></a>";
                                                        echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                    } else {
                                                        echo "<a data-label='$filename' href='" . asset($d->{$col['name']}) . "'>$filename</a>";
                                                        echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                    }
                                                } else {
                                                    echo "<span class='td-label'>";
                                                    echo $d->{$col['name']};
                                                    echo '</span>';
                                                    echo "<input type='hidden' name='" . $name . '-' . $col['name'] . "[]' value='" . $d->{$col['name']} . "'/>";
                                                }
                                                ?>
                                            </td>
                                        @endforeach
                                        <td>
                                            <a href='#panel-form-{{ $name }}'
                                                onclick='editRow{{ $name }}(this)'
                                                class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i></a>
                                            <a href='javascript:void(0)'
                                                onclick='deleteRow{{ $name }}(this)'
                                                class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
                                        </td>
                                    </tr>

                                    <?php endforeach;?>

                                    @if (count($data_child) == 0)
                                        <tr class="trNull">
                                            <td colspan="{{ count($form['columns']) + 1 }}" align="center">
                                                {{ cbLang('table_data_not_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    @else
        <div style="border:1px dashed #c41300;padding:20px;margin:20px">
            <span style="background: yellow;color: black;font-weight: bold">CHILD {{ $name }} : COLUMNS
                ATTRIBUTE IS MISSING !</span>
            <p>You need to set the "columns" attribute manually</p>
        </div>
    @endif
</div>
