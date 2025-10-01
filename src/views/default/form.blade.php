@extends('crudbooster::admin_template')
@section('content')

    <div>

        @if (CRUDBooster::getCurrentMethod() != 'getProfile' && $button_cancel)
            @if (g('return_url'))
                <p><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp; {{ cbLang('form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
                </p>
            @else
                <p><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp; {{ cbLang('form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
                </p>
            @endif
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class='{{ CRUDBooster::getCurrentModule()->icon }}'></i> {!! $page_title !!}</strong>
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <?php
                $action = @$row ? CRUDBooster::mainpath("edit-save/$row->id") : CRUDBooster::mainpath('add-save');
                $return_url = $return_url ?: g('return_url');
                ?>
                <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data"
                    action='{{ $action }}'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='hidden' name='return_url' value='{{ @$return_url }}' />
                    <input type='hidden' name='ref_mainpath' value='{{ CRUDBooster::mainpath() }}' />
                    <input type='hidden' name='ref_parameter' value='{{ urldecode(http_build_query(@$_GET)) }}' />
                    @if ($hide_form)
                        <input type="hidden" name="hide_form" value='{!! serialize($hide_form) !!}'>
                    @endif
                    <div class="box-body" id="parent-form-area">

                        @if ($command == 'detail')
                            @include('crudbooster::default.form_detail')
                        @else
                            @if(CRUDBooster::getCurrentModule()->translation_table != null && CRUDBooster::getCurrentModule()->translation_table != '')
                                <style>
                                    
                                    .tabs-buttons {
                                        display: flex;
                                        flex-direction: column;
                                        min-width: 15%;
                                    }

                                    .tabs-buttons button {
                                        background: none;
                                        border: none;
                                        padding: 15px;
                                        border-radius: 0;
                                        border: 1px solid #ddd;
                                        margin-bottom: 5px;
                                        transition: all .3s;
                                    }

                                    .tabs-buttons button.active,
                                    .tabs-buttons button:hover {
                                        color: white;
                                        background: #dd4b39;
                                        border: 1px solid #dd4b39;
                                    }

                                    .tabs-buttons button.active {
                                        position: relative;
                                    }

                                    .tabs-buttons button.active::after {
                                        font-family: 'FontAwesome';
                                        content: '\f104';
                                        display: inline-block;
                                        position: absolute;
                                        right: -13px;
                                        color: #ddd;
                                        font-size: 25px;
                                        top: 50%;
                                        transform: translateY(-50%);
                                        background: white;
                                        line-height: 10px;
                                    }

                                    .tabs-container {
                                        display: flex;
                                        flex-wrap: nowrap;
                                    }

                                    .tab-content {
                                        padding-left: 40px;
                                        margin-left: 10px;
                                        border-left: 2px solid #ddd;
                                        border-radius: 10px; 
                                        width: 100%;
                                    }
                                </style>

                                <div class="tabs-container">
                                <div class="tabs-buttons" style="">
                                    @foreach ($websiteLanguages as $index => $lang)
                                        <button type="button" class="{{$index == 0 ? 'active' : ''}}" id="btn-{{$lang->name}}" onclick="openTab('{{$lang->code}}', 'btn-{{$lang->name}}')">{{$lang->name}}</button>
                                    @endforeach
                                </div>
                                <div class="tab-content">
                                @foreach ($websiteLanguages as $index => $lang)
                                <div id="{{$lang->code}}" class="tab" style="{{$index == 0? '' : 'display:none'}}">

                                    @include('crudbooster::default.form_body')
                           
                                </div>
                                @endforeach
                                </div>
                                </div>
                                <script>
                                    function openTab(tabName, btnName) {
                                        var i;
                                        var x = document.getElementsByClassName("tab");
                                        for (i = 0; i < x.length; i++) {
                                            x[i].style.display = "none";  
                                        }
                                        document.getElementById(tabName).style.display = "block"; 

                                        $('.tabs-buttons button').removeClass('active');
                                        $('#' + btnName).addClass('active');
                                        
                                    }
                                </script>
                            @else
                                @php 
                                $lang = $websiteLanguages->where('default',1)->first();
                                @endphp
                              @include('crudbooster::default.form_body')
                            @endif


                            @if (CRUDBooster::getCurrentModule()->has_images == 1)
                                @include('crudbooster::model_images')
                            @endif
                        @endif

                    </div><!-- /.box-body -->

                    <div class="box-footer" style="background: #F5F5F5">

                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">
                                @if ($button_cancel && CRUDBooster::getCurrentMethod() != 'getDetail')
                                    @if (g('return_url'))
                                        <a href='{{ g('return_url') }}' class='btn btn-default'><i
                                                class='fa fa-chevron-circle-left'></i> {{ cbLang('button_back') }}</a>
                                    @else
                                        <a href='{{ CRUDBooster::mainpath('?' . http_build_query(@$_GET)) }}'
                                            class='btn btn-default'><i class='fa fa-chevron-circle-left'></i>
                                            {{ cbLang('button_back') }}</a>
                                    @endif
                                @endif
                                @if (CRUDBooster::isCreate() || CRUDBooster::isUpdate())
                                    @if (CRUDBooster::isCreate() && $button_addmore == true && $command == 'add')
                                        <input type="submit" name="submit" value='{{ cbLang('button_save_more') }}'
                                            class='btn btn-success'>
                                    @endif

                                    @if ($button_save && $command != 'detail')
                                        <input type="submit" name="submit" value='{{ cbLang('button_save') }}'
                                            class='btn btn-success'>
                                    @endif
                                @endif
                            </div>
                        </div>


                    </div><!-- /.box-footer-->

                </form>

            </div>
        </div>
    </div>
    <!--END AUTO MARGIN-->

@endsection
