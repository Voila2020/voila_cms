@php
    $images = DB::table('model_images')
        ->where('model_type', $table)
        ->where('model_id', $row->id)
        ->get();
    $image_paths = '[';
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="module-images-border border"></div>
<div class='form-group header-group-0' id='form-group-list_images' style="{{ @$form['style'] }}">
    <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}
        <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>
            {{ cbLang('module_images') }}
        </span>
    </label>
    <div class="{{ $col_width ? $col_width . 'filemanager-col' : 'col-sm-10 filemanager-col' }}"
        style="{{ $value ? 'display: none' : '' }}">
        <div class="input-group">
            <input type="hidden" id="list_images" name="list_images[]">

            <a onclick="openInsertImages()" class="btn btn-primary" value="img_type" style="margin-bottom:10px;">
                <i class='fa fa-picture-o'></i> {{ cbLang('module_images') }}
            </a>
            <div id="show-images" class="" style="display:flex; flex-wrap:wrap;">

                @foreach ($images as $key => $element)
                    @php
                        $image_paths .= '"' . $element->path . '"' . ',';
                    @endphp
                    <div class="img_box img_box_{{ $key }}" value="{{ $element->path }}">
                        <a data-lightbox="roadtrip" id="image{{ $key }}"
                            href="{{ url('' . $element->path) }} ">
                            <img style="width:150px;height:150px;" title="Image For Image"
                                src="{{ url('' . $element->path) }}">
                        </a>
                        <span class="img-del-span" onclick="deleteImageFromList({{ $key }})"
                            id="img-{{ $key }}"
                            style='color:red;position: relative;cursor:pointer;bottom: 65px; right:18px;'><i
                                class='fa fa-close'></i>
                        </span>
                    </div>
                @endforeach
                @php
                    $image_paths = rtrim($image_paths, ',');
                    $image_paths = $image_paths . ']';
                    $image_paths_json = json_encode($image_paths);
                @endphp
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInsertPhoto">
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
            <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@if ($images->count() > 0)
    <script>
        $('#list_images').val(<?php echo $image_paths_json; ?>);
    </script>
@endif


<script type="text/javascript">
    function openInsertImages() {
        var name = "list_images";
        var link =
            `<iframe class="filemanager-iframe" width="100%" height="600" src="{{ Route('dialog') }}?type=1&multiple=1&field_id=` +
            name +
            `" frameborder="0"></iframe>`;
        $("#modalInsertPhoto .modal-body").html(link);
        $("#modalInsertPhoto").modal();
    }

    $(function() {
        $('#modalInsertPhoto').on('hidden.bs.modal', function() {
            var images_value = $('#list_images').val();
            if (images_value.indexOf('[') !== -1)
                var images = JSON.parse(images_value);
            else if (images_value) {
                var images = JSON.parse('["' + images_value + '"]')
            }
            var child_count = $('#show-images').children().length;
            // $('#show-images').html('');
            for (let i in images) {
                if (images[i].charAt(0) === "/") {
                    images[i] = images[i].substring(1);
                }
                if ($('#show-images').find('.img_box[value="' + images[i] + '"]').length > 0) {
                    continue;
                }
                let img_src = '{{ URL::asset('') }}' + images[i];
                let id = parseInt(i) + child_count;
                $('#show-images').append(`
                    <div class="img_box img_box_` + (id) + `" value="` + images[i] + `" >
                        <a data-lightbox="roadtrip" id="image` + (id) + `" href="` + img_src + `">
                            <img style="width:150px;height:150px;padding-top:10px;" title="Image For Image" src="` +
                    img_src + `">
                        </a>
                        <span class="img-del-span" onclick="deleteImageFromList(` + (id) + `)" id="image` + (id) + `" style='color:red;position: relative;cursor:pointer;bottom: 55px; right:18px;'><i class='fa fa-close'></i></span>
                    </div>
                `);
            }
            var selectedImages = [];
            $('#show-images').children().each(function() {
                selectedImages.push(($(this).attr('value')));
            });
            $('#list_images').val(JSON.stringify(selectedImages));
        });
        resizeFilemanagerPopout();
    });

    function deleteImageFromList(id) {
        var images_value = $('#list_images').val();
        var images = JSON.parse(images_value);
        $('.img_box_' + id).remove();
        images.splice(id, 1);
        $('#list_images').val(JSON.stringify(images));
    }

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
