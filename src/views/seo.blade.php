@extends('crudbooster::admin_template')
@section('title', 'SEO') @section('content')

<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
@php
    $languages = DB::table('languages')->get();
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                @foreach ($errors->all() as $error)
                    <li style="text-align: center" class="alert alert-danger">{{ $error }}</li>
                @endforeach

                <form enctype="multipart/form-data" action="{{ url(CRUDBooster::adminPath() . '/seo-store/' . $type) }}"
                    class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="model_id" value="{{ $id }}">
                    <input type="hidden" name="back_url" value="{{ url()->previous() }}">
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
                                    name="{{ 'keywords_' . $lang->code }}" title="{{ 'keyworkds ' . $lang->code }}"
                                    value="{{ $data[$lang->code]->keywords }}" class="form-control">
                                <a class="btn btn-info add-key-{{ 'keywords_' . $lang->code }}"><span
                                        class="fa fa-plus"></span></a><input type="text"
                                    id="text-key-{{ 'keywords_' . $lang->code }}"
                                    style="padding: 5px;margin-left: 16px;">
                            </div>
                        </div>
                    @endforeach
                    @foreach ($languages ?? [] as $key => $lang)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'Author ' . $lang->code }}</label>
                            <div class="col-sm-10">
                                <input type="text" name="{{ 'author_' . $lang->code }}"
                                    title="{{ 'author ' . $lang->code }}" value="{{ $data[$lang->code]->author }}"
                                    class="form-control">
                            </div>
                        </div>
                    @endforeach
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-danger" href="{{ url()->previous() }}">Back</a>
                            <input class="btn btn-primary" type="submit" value="Save">
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
                $("#keywords_" + langs_arr[key].code).val(oldVal + " , " + newVal);
                $("#text-key-keywords_" + langs_arr[key].code).val("");
            });
        });

        $(".form-horizontal").keypress(function(e) {
            //Enter key
            if (e.which == 13) {
                return false;
            }
        });
    </script>

@endsection
