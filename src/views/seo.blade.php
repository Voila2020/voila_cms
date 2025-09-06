@extends('crudbooster::admin_template')
@section('title', 'SEO')
@section('content')

<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>


<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-language"></i> Update SEO </strong>
        @if(CRUDBooster::checkUsingAIFeaturesPermission())
            <a class="btn btn-primary btn-sm pull-right" style="margin-top: -5px;" href="javascript:void(0)" id="GenerateSEOByAiBtn" data-post-url="{{ route('AIContentGeneratorControllerGenerateSEOByAi') }}" data-page="{{request()->input('page')}}" data-page-id="{{ request()->input('page_id') }}"> Generate Seo With AI <i class="fa fa-magic"></i></a>
        @endif
    </div>

    <div class="panel-body" style="padding:20px 0px 0px 0px">

@php
    $languages = DB::table('languages')->where('active',1)->get();
@endphp

<div class="row">
    <div class="col-lg-12">
        @if(CRUDBooster::checkUsingAIFeaturesPermission())
        <div class="seo-btns-sect">
            <a class="btn btn-primary btn-sm pull-right" href="javascript:void(0)" id="GenerateSEOByAiBtn" data-post-url="{{ route('AIContentGeneratorControllerGenerateSEOByAi') }}" data-page="{{request()->input('page')}}" data-page-id="{{ request()->input('page_id') }}"> Generate Seo With AI <i class="fa fa-magic"></i></a>
        </div>
        @endif
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                @foreach ($errors->all() as $error)
                    <li style="text-align: center" class="alert alert-danger">{{ $error }}</li>
                @endforeach
                <form enctype="multipart/form-data"
                    action='{{ CRUDBooster::mainPath('seo-store') . '?page=' . request()->input('page') . '?page_id=' . request()->input('page_id') }}'
                    class="form-horizontal" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="page" value="{{ $type }}">
                    <input type="hidden" name="page_id" value="{{ $id }}">
                    <input type="hidden" name="back_url" value="{{ url()->previous() }}">
                    <div class="box-body" style="padding:0px 10px;">
                    @foreach ($languages ?? [] as $key => $lang)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'Title ' . $lang->code }}</label>
                            <div class="col-sm-10">
                                <input type="text" name="{{ 'title_' . $lang->code }}"
                                    title="{{ 'Title ' . $lang->code }}" value="{{ $data[$lang->code]->title }}"
                                    class="form-control">
                            </div>
                        </div>
                    @endforeach
                    @foreach ($languages ?? [] as $key => $lang)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'Description ' . $lang->code }}</label>
                            <div class="col-sm-10">
                                <input type="text" name="{{ 'description_' . $lang->code }}"
                                    title="{{ 'Description ' . $lang->code }}"
                                    value="{{ $data[$lang->code]->description }}" class="form-control">
                            </div>
                        </div>
                    @endforeach
                    @foreach ($languages ?? [] as $key => $lang)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'Keywords ' . $lang->code }}</label>
                            <div class="col-sm-10">
                                <input type="text" id="keywords_{{ $lang->code }}"
                                    name="{{ 'keywords_' . $lang->code }}" title="{{ 'keywords ' . $lang->code }}"
                                    value="{{ $data[$lang->code]->keywords }}" class="form-control">
                                <a class="btn btn-info add-key-{{ 'keywords_' . $lang->code }}"><span
                                        class="fa fa-plus"></span></a><input type="text"
                                    id="text-key-{{ 'keywords_' . $lang->code }}"
                                    style="padding: 5px;margin-left: 16px;">
                            </div>
                        </div>
                    @endforeach
                    {{-- @foreach ($languages ?? [] as $key => $lang)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'Author ' . $lang->code }}</label>
                            <div class="col-sm-10">
                                <input type="text" name="{{ 'author_' . $lang->code }}"
                                    title="{{ 'author ' . $lang->code }}" value="{{ $data[$lang->code]->author }}"
                                    class="form-control">
                            </div>
                        </div>
                    @endforeach --}}

                    <!-- Start Image -->
                    <div class="form-group filemanager-form-group_image header-group-0 " id="form-group-image" style="">
                        <label class="control-label col-sm-2">Image
                                </label>
                        @if($seo_image == '')
                            <div class="col-sm-10 empty-filemanager-col_image" >
                                <div class="input-group">
                                    <input id="image" filemanager_type="image" class="form-control hide" type="text" value="{{$seo_image}}" name="image">

                                    <a data-lightbox="roadtrip" class="hide" id="link-image" href="" style="">
                                        <img style="height:100px; " id="img-image" title="Add image for Image" src="">
                                        <p class="file-roadtrip" id="file-image" style="display:none;"></p>
                                    </a>

                                        <span class="input-group-btn" >
                                                                    <a id="_image" onclick="OpenInsertImagesingle('image')" class="btn btn-primary" value="img_type">
                                                        <i class="fa fa-picture-o"></i> Choose an image
                                                            </a>
                                        </span>

                                </div>
                                <div class="text-danger"></div>
                                <div class="help-block"></div>
                            </div>
                        @else
                            <div class="col-sm-10 empty-filemanager-col_image" style="display: none">
                                <div class="input-group">
                                    <input id="image" filemanager_type="image" class="form-control hide" type="text" value="{{$seo_image}}" name="image">

                                    <a data-lightbox="roadtrip" class="hide" id="link-image" href="" style="">
                                        <img style="height:100px; " id="img-image" title="Add image for Image" src="">
                                        <p class="file-roadtrip" id="file-image" style="display:none;"></p>
                                    </a>

                                        <span class="input-group-btn" >
                                                                    <a id="_image" onclick="OpenInsertImagesingle('image')" class="btn btn-primary" value="img_type">
                                                        <i class="fa fa-picture-o"></i> Choose an image
                                                            </a>
                                        </span>

                                </div>
                                <div class="text-danger"></div>
                                <div class="help-block"></div>
                            </div>
                            <div class="col-sm-10 filemanager-col_image" style="">
                                <input id="thumbnail-image" class="form-control" type="hidden" value="{{$seo_image}}" name="image">
                                            <p><a id="roadtrip-image" class="p-roadtrip" data-lightbox="roadtrip" href="{{ $seo_image ? asset($seo_image) : '' }}"><img id="holder-image" src="{{ $seo_image ? asset($seo_image) : '' }}" style="margin-top:15px;max-height:100px;"></a>
                                    </p>
                                                    <p>
                                        <a class="btn btn-danger btn-delete btn-sm btn-del-filemanager" onclick="showDeletePopout('image')"><i class="fa fa-ban"></i>
                                            Delete </a>
                                    </p>
                                        <div class="help-block"></div>
                            </div>
                        @endif

                    </div>
                    <div class="modal fade" id="modalInsertPhotosingle_image">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="buttons">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <button type="button" class="resize" title="filemanager.resize"><i class="fa fa-expand" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="title-sec">
                                        <h4 class="modal-title">Insert Image</h4>
                                    </div>
                                </div>
                                <div class="modal-body">

                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!-- End Image -->

                    <!-- Start Optional Meta Tags -->
                    <div class="form-group header-group-0 " id="form-group-optional_tags" style="">
                        <label class="control-label col-sm-2">Optional Tags
                                </label>
                        <div class="col-sm-10">
                            <textarea name="optional_tags" id="optional_tags" class="form-control" rows="5"> {{$seo_optional_tags}}</textarea>
                            <div class="text-danger"></div>
                            <p class="help-block">Additional tags must be entered correctly as html code. To learn more about the mechanism of adding tags for SEO and conforming to the Open Graph protocol <a href='https://developers.facebook.com/docs/sharing/webmasters/?locale=en_US' target='_blank'>Click Here</a>.</p>
                        </div>
                    </div>
                    </div>
                    <!-- End Optional Meta Tags -->
                    <div class="hr-line-dashed"></div>
                    <div class="box-footer" style="background: #F5F5F5">
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-default" href="{{ url()->previous() }}" >Back</a>
                                <input class="btn btn-success" type="submit" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>

    <script src="{!! asset('js/imageScript.js') !!}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('.alert-').hide();

        })
        var langs_arr = @json($languages);
        Object.keys(langs_arr).forEach(function(key) {
            $(".add-key-keywords_" + langs_arr[key].code).click(function() {
                var oldVal = $("#keywords_" + langs_arr[key].code).val();
                var newVal = $("#text-key-keywords_" + langs_arr[key].code).val();
                if (newVal.length) {
                    $("#keywords_" + langs_arr[key].code).val(oldVal + " , " + newVal);
                    $("#text-key-keywords_" + langs_arr[key].code).val("");
                }
            });
        });

        $(".form-horizontal").keypress(function(e) {
            //Enter key
            if (e.which == 13 && event.target.tagName != 'TEXTAREA') {
                return false;
            }
        });

    </script>


    </div> <!-- end panel body -->
</div> <!-- end panel -->

@endsection

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
            $("#modalInsertPhotosingle_image .modal-body").html(link);
            $("#modalInsertPhotosingle_image").modal();
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
            let ajaxUrl = currUrl + '?table=cms_seo&column=image&value=&id={{$record_id}}';

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

        $(document).ready(function(){
            var id = '#modalInsertPhotosingle_image';
            $(id).on('hidden.bs.modal', function() {
                var check = $('#image').val();
                if (check != "") {
                    check = check.substring(1);
                    if ($('#_image').attr("value") == 'file_type') {
                        $("#file-image").html(check);
                    } else
                        $("#img-image").attr("src", '{{ URL::asset('') }}' + check);
                    $("#link-image").attr("href", '{{ URL::asset('') }}' + check);
                    $("#link-image").removeClass("hide");
                    $("#thumbnail-image").attr("src", '{{ URL::asset('') }}' + check);
                    $("#thumbnail-image").attr("value", check);
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