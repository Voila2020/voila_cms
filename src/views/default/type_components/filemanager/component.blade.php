<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div class='form-group filemanager-form-group {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
    id='form-group-{{ $name }}' style='{{ @$form['style'] }}'>
    <label class='control-label col-sm-2'>{{ $form['label'] }}
        @if ($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>


    @if ($value == '')
        <div class="{{ $col_width ? $col_width . ' empty-filemanager-col' : 'col-sm-10 filemanager-col' }}">
            <div class="input-group">
                <input id="{{ $name }}" class="form-control hide" type="text" value='{{ $value }}'
                    name="{{ $name }}">

                <a data-lightbox="roadtrip" class="hide" id="link-{{ $name }}" href="">
                    <img style="width:150px;height:150px;" id="img-{{ $name }}"
                        title="Add image for {{ $name }}" src="">
                </a>

                <span class="input-group-btn">
                    @if (@$form['filemanager_type'] == 'file')
                        <a id="_{{$name}}" onclick="OpenInsertImagesingle('{{ $name }}')" class="btn btn-primary" value="file_type">
                            <i class="fa fa-file-o"></i> {{ cbLang('chose_an_file') }}
                        @else
                        <a id="_{{$name}}" onclick="OpenInsertImagesingle('{{ $name }}')" class="btn btn-primary" value="img_type">
                            <i class='fa fa-picture-o'></i> {{ cbLang('chose_an_image') }}
                        @endif
                    </a>
                </span>
            </div>
    @endif

    @if ($value)
        <div class="{{ $col_width ? $col_width . ' filemanager-col' : 'col-sm-10 filemanager-col' }}">
            <input id="thumbnail-{{ $name }}" class="form-control" type="hidden" value='{{ $value }}'
                name="{{ $name }}">
            @if (@$form['filemanager_type'] == 'file')
                @if ($value)
                    <div style='margin-top:15px'><a id='holder-{{ $name }}' href='{{ asset($value) }}'
                            target='_blank' title=' {{ cbLang('button_download_file') }} {{ basename($value) }}'><i
                                class='fa fa-download'></i> {{ cbLang('button_download_file') }}
                            {{ basename($value) }}</a>
                        &nbsp;<a class='btn btn-danger btn-delete btn-xs'
                            onclick='swal({   title: "{{ cbLang('delete_title_confirm') }}",   text: "{{ cbLang('delete_description_confirm') }}",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "{{ cbLang('confirmation_yes') }}",cancelButtonText: "{{ cbLang('button_cancel') }}",   closeOnConfirm: false }, function(){  location.href="{{ url($mainpath . '/delete-filemanager?file=' . $row->{$name} . '&id=' . $row->id . '&column=' . $name) }}" });'
                            href='javascript:void(0)' title='{{ cbLang('text_delete') }}'><i class='fa fa-ban'></i></a>
                    </div>
                @endif
            @else
                <p><a data-lightbox="roadtrip" href="{{ $value ? asset($value) : '' }}"><img
                            id='holder-{{ $name }}' {{ $value ? 'src=' . asset($value) : '' }}
                            style="margin-top:15px;max-height:100px;"></a>
                </p>
            @endif
    @endif

    <div class='help-block'>{{ @$form['help'] }}</div>
    <div class="text-danger">{!! $errors->first($name) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name) : '' !!}</div>
</div>
</div>

<div class="modal fade" id="modalInsertPhotosingle{{ $name }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="buttons">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <button type="button" class="resize" title="<?php echo cbLang('filemanager.resize'); ?>"><i class="fa fa-expand" aria-hidden="true"></i></button>
                </div>
                <div class="title-sec">
                    <h4 class="modal-title">Insert Image</h4>
                </div>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    function OpenInsertImagesingle(name) {
        // reback size of iframe to default
        $('.modal.in .modal-dialog').width(900);
        console.log("name = ", name)
        // check file manager type
        if($('#_'+name).attr("value") == 'file_type'){
            var link = `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=` + name +
            `" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll;"></iframe>`;
        }
        else{
            var link = `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=1&multiple=0&field_id=` + name +
                `" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll;"></iframe>`;
        }

        $("#modalInsertPhotosingle{{ $name }} .modal-body").html(link);
        $("#modalInsertPhotosingle{{ $name }}").modal();
        console.log($('.modal.in .modal-dialog').width())

    }

    function deleteImage() {
        let currUrl = @json(CRUDBooster::mainpath()) + '/update-single';
        let table = @json($table);
        let name = @json($name);
        let id = @json($id);
        let ajaxUrl = currUrl + '?table=' + table + '&column=' + name + '&value=&id=' + id;

        $.ajax({
            type: 'GET',
            url: ajaxUrl,
            success: function(data) {
                $('.filemanager-col').remove();
                $('.filemanager-form-group').append(`
                    <div class="{{ $col_width ? $col_width . ' empty-filemanager-col' : 'col-sm-10 filemanager-col' }}">
                        <div class="input-group">
                            <input id="{{ $name }}" class="form-control hide" type="text" value='{{ $value }}'
                                name="{{ $name }}">

                            <a data-lightbox="roadtrip" class="hide" id="link-{{ $name }}" href="">
                                <img style="width:150px;height:150px;" id="img-{{ $name }}"
                                    title="Add image for {{ $name }}" src="">
                            </a>

                            <span class="input-group-btn">
                                <a id="" onclick="OpenInsertImagesingle('{{ $name }}')" class="btn btn-primary">
                                    @if (@$form['filemanager_type'] == 'file')
                                        <i class="fa fa-file-o"></i> {{ cbLang('chose_an_file') }}
                                    @else
                                        <i class='fa fa-picture-o'></i> {{ cbLang('chose_an_image') }}
                                    @endif
                                </a>
                            </span>

                        </div>
                        <div class='help-block'>{{ @$form['help'] }}</div>
                        <div class="text-danger">{!! $errors->first($name) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name) : '' !!}</div>
                    </div>
                `);
            $('.showSweetAlert ').removeClass('visible').addClass('hide');
            $('.sweet-overlay').addClass('hide');
            },
            error: function(data) {
                console.log("and error while delete image", data);
            }
        });
    }

    var id = '#modalInsertPhotosingle{{ $name }}';
    $(function() {
        $(id).on('hidden.bs.modal', function() {
            var check = $('#{{ $name }}').val();
            if (check != "") {
                $("#img-{{ $name }}").attr("src", check);
                $("#link-{{ $name }}").attr("href", check);
                $("#link-{{ $name }}").removeClass("hide");
            }
        });
        $('.modal-header .resize').unbind().click(function(){
            if($('.modal.in .modal-dialog').width() == 900){
                console.log("resize yes ",$('.modal.in .modal-dialog').width());
                $('.modal.in .modal-dialog').width(1300);
                $('iframe').height(600);
            }else{
                console.log("resize no ",$('.modal.in .modal-dialog').width());
                $('.modal.in .modal-dialog').width(900);
                $('iframe').height(400);
            }
        });
    });
</script>
