@php
    $images = DB::table('module_images')
        ->where('module_id', CRUDBooster::getCurrentModule()->id)
        ->where('module_row_id', $row->id)
        ->get();
    $image_paths = '[';
@endphp
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

            <a onclick="openInsertImages()" class="btn btn-primary" value="img_type">
                <i class='fa fa-picture-o'></i> {{ cbLang('module_images') }}
            </a>
            <div id="show-images" class="" style="display:flex; flex-wrap:wrap; padding-top:15px;">

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
                        <span onclick="deleteImageFromList({{ $key }})" id="img-{{ $key }}"
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Insert Image</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@if ($images)
    <script>
        $('#list_images').val(<?php echo $image_paths_json; ?>);
    </script>
@endif
