@extends('crudbooster::admin_template')
@section('content')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{ Route('ModulsControllerGetStep1') . '/' . $id }}"><i class='fa fa-info'></i>
                {{ cbLang('Step 1') }} - {{ cbLang('Module Information') }}</a></li>
        <li role="presentation"><a href="{{ Route('ModulsControllerGetStep2') . '/' . $id }}"><i class='fa fa-table'></i>
                {{ cbLang('Step 2') }} - {{ cbLang('Table Display') }}</a></li>
        <li role="presentation"><a href="{{ Route('ModulsControllerGetStep3') . '/' . $id }}"><i
                    class='fa fa-plus-square-o'></i>
                {{ cbLang('Step 3') }} - {{ cbLang('Form Display') }}</a></li>
        <li role="presentation" class="active"><a href="{{ Route('ModulsControllerGetStep4') . '/' . $id }}"><i
                    class='fa fa-wrench'></i> {{ cbLang('Step 4') }} - {{ cbLang('Configuration') }}</a></li>
    </ul>

    <div class="box box-default">
        <div class="box-header">
            <h1 class="box-title">{{ cbLang('Configuration') }}</h1>
        </div>
        <form method='post' action='{{ Route('ModulsControllerPostStepFinish') }}'>
            {{ csrf_field() }}
            <input type="hidden" name="id" value='{{ $id }}'>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">

                            <label>{{ cbLang('title_field_candidate') }}</label>
                            <input type="text" name="title_field" value="{{ $cb_title_field }}" class='form-control'>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group">
                            <label>{{ cbLang('Limit Data') }}</label>
                            <input type="number" name="limit" value="{{ $cb_limit }}" class='form-control'>
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <div class="form-group">
                            <label>{{ cbLang('Order By') }}</label>
                            <?php
                            if (is_array($cb_orderby)) {
                                $orderby = [];
                                foreach ($cb_orderby as $k => $v) {
                                    $orderby[] = $k . ',' . $v;
                                }
                                $orderby = implode(';', $orderby);
                            } else {
                                $orderby = $cb_orderby;
                            }
                            ?>
                            <input type="text" name="orderby" value="{{ $orderby }}" class='form-control'>
                            <div class="help-block">E.g : column_name,desc</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Sortable') }}</label>
                                    <label class='radio-inline'>
                                        <input type='radio' name='sortable_table'
                                            {{ $cb_sortable_table ? 'checked' : '' }} value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_sortable_table ? 'checked' : '' }} type='radio'
                                            name='sortable_table' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Global Privilege') }}</label>
                                    <label class='radio-inline'>
                                        <input type='radio' name='global_privilege'
                                            {{ $cb_global_privilege ? 'checked' : '' }} value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_global_privilege ? 'checked' : '' }} type='radio'
                                            name='global_privilege' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Table Action') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_table_action ? 'checked' : '' }} type='radio'
                                            name='button_table_action' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_table_action ? 'checked' : '' }} type='radio'
                                            name='button_table_action' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Bulk Action Button') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_bulk_action ? 'checked' : '' }} type='radio'
                                            name='button_bulk_action' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_bulk_action ? 'checked' : '' }} type='radio'
                                            name='button_bulk_action' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Button Action Style') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_action_style == 'button_icon' ? 'checked' : '' }}
                                            type='radio' name='button_action_style' value='button_icon' /> Icon
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_action_style == 'button_icon_text' ? 'checked' : '' }}
                                            type='radio' name='button_action_style' value='button_icon_text' /> Icon &
                                        Text
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_action_style == 'button_text' ? 'checked' : '' }}
                                            type='radio' name='button_action_style' value='button_text' /> Button Text
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_action_style == 'button_dropdown' ? 'checked' : '' }}
                                            type='radio' name='button_action_style' value='button_dropdown' /> Dropdown
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Record SEO') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_record_seo ? 'checked' : '' }} type='radio' name='record_seo'
                                            value="true" /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_record_seo ? 'checked' : '' }} type='radio' name='record_seo'
                                            value="false" /> FALSE
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Add') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_add ? 'checked' : '' }} type='radio' name='button_add'
                                            value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_add ? 'checked' : '' }} type='radio' name='button_add'
                                            value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Edit') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_edit ? 'checked' : '' }} type='radio' name='button_edit'
                                            value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_edit ? 'checked' : '' }} type='radio' name='button_edit'
                                            value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Delete') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_delete ? 'checked' : '' }} type='radio'
                                            name='button_delete' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_delete ? 'checked' : '' }} type='radio'
                                            name='button_delete' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Detail') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_detail ? 'checked' : '' }} type='radio'
                                            name='button_detail' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_detail ? 'checked' : '' }} type='radio'
                                            name='button_detail' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('PDF Print Direction') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_pdf_direction ? 'checked' : '' }} type='radio'
                                            name='pdf_direction' value="ltr" /> LTR
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_pdf_direction ? 'checked' : '' }} type='radio'
                                            name='pdf_direction' value="rtl" /> RTL
                                    </label>
                                </div>
                            </div>


                        </div>


                    </div>

                    <div class="col-sm-4">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Show Data') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_show ? 'checked' : '' }} type='radio' name='button_show'
                                            value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_show ? 'checked' : '' }} type='radio' name='button_show'
                                            value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Filter & Sorting') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_filter ? 'checked' : '' }} type='radio'
                                            name='button_filter' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_filter ? 'checked' : '' }} type='radio'
                                            name='button_filter' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Import') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_import ? 'checked' : '' }} type='radio'
                                            name='button_import' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_import ? 'checked' : '' }} type='radio'
                                            name='button_import' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Show Button Export') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_button_export ? 'checked' : '' }} type='radio'
                                            name='button_export' value='true' /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_button_export ? 'checked' : '' }} type='radio'
                                            name='button_export' value='false' /> FALSE
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ cbLang('Page SEO') }}</label>
                                    <label class='radio-inline'>
                                        <input {{ $cb_page_seo ? 'checked' : '' }} type='radio' name='page_seo'
                                            value="true" /> TRUE
                                    </label>
                                    <label class='radio-inline'>
                                        <input {{ !$cb_page_seo ? 'checked' : '' }} type='radio' name='page_seo'
                                            value="false" /> FALSE
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="box-footer">
                <div align="right">
                    <button type="button" onclick="location.href='{{ CRUDBooster::mainpath('step3') . '/' . $id }}'"
                        class="btn btn-default">&laquo; Back</button>
                    <input type="submit" name="submit" class='btn btn-primary' value='{{ cbLang('Save Module') }}'>
                </div>
            </div>
        </form>
    </div>
@endsection
