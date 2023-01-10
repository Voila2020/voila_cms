@extends('crudbooster::admin_template')
@section('title', 'SEO') @section('content')

<script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>


<div class="row">
    <div class="col-lg-12">

        <div class="ibox float-e-margins">

            <div class="ibox-content">

                @foreach ($errors->all() as $error)
                    <li style="text-align: center" class="alert alert-danger">{{ $error }}</li>
                @endforeach

                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif

                <form enctype="multipart/form-data" action="{{ url('/seo-store/' . $type) }}" class="form-horizontal"
                    method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="model_id" value="{{ $id }}">
                    <input type="hidden" name="back_url" value="{{ url()->previous() }}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title En</label>

                        <div class="col-sm-10">
                            <input type="text" name="title_en" title="title"
                                value="{{ $data ? $data->title_en : '' }}" class="form-control">
                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title Ar</label>

                        <div class="col-sm-10">
                            <input type="text" name="title_ar" title="title"
                                value="{{ $data ? $data->title_ar : '' }}" class="form-control">
                        </div>


                    </div>




                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description En</label>

                        <div class="col-sm-10">
                            <input type="text" name="description_en" title="description"
                                value="{{ $data ? $data->description_en : '' }}" class="form-control">
                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description Ar</label>

                        <div class="col-sm-10">
                            <input type="text" name="description_ar" title="description"
                                value="{{ $data ? $data->description_ar : '' }}" class="form-control">
                        </div>


                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label">Keywords En</label>

                        <div class="col-sm-10">
                            <input type="text" id="keywords_en" name="keywords_en" title="keywords"
                                value="{{ $data ? $data->keywords_en : '' }}" class="form-control">
                            <a class="btn btn-info add-key"><span class="fa fa-plus"></span></a><input type="text"
                                id="text-key" style="padding: 5px;margin-left: 16px;">
                        </div>


                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Keywords Ar</label>

                        <div class="col-sm-10">
                            <input type="text" name="keywords_ar" id="keywords_ar" title="keywords"
                                value="{{ $data ? $data->keywords_ar : '' }}" class="form-control">
                            <a class="btn btn-info add-key1"><span class="fa fa-plus"></span></a><input type="text"
                                id="text-key1" style="padding: 5px;margin-left: 16px;">

                        </div>


                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label">Author En</label>

                        <div class="col-sm-10">
                            <input type="text" name="author_en" title="author"
                                value="{{ $data ? $data->author_en : '' }}" class="form-control">
                        </div>


                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Author Ar</label>

                        <div class="col-sm-10">
                            <input type="text" name="author_ar" title="author"
                                value="{{ $data ? $data->author_ar : '' }}" class="form-control">
                        </div>


                    </div>


            </div>










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
</div>

<script src="{!! asset('js/imageScript.js') !!}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $('.alert-').hide();

    })
    $(".add-key").click(function() {

        var oldVal = $("#keywords_en").val();
        var newVal = $("#text-key").val();

        $("#keywords_en").val(oldVal + " , " + newVal);
        $("#text-key").val("");



    });

    $(".add-key1").click(function() {

        var oldVal = $("#keywords_ar").val();
        var newVal = $("#text-key1").val();

        $("#keywords_ar").val(oldVal + " , " + newVal);
        $("#text-key1").val("");



    });





    $(".form-horizontal").keypress(function(e) {
        //Enter key
        if (e.which == 13) {
            return false;
        }
    });
</script>

@endsection
