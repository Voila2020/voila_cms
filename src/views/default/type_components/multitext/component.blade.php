<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='control-label col-sm-2'>{{cbLang($form['label'])}}
        @if($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}} input_fields_wrap {{$name}}">

        <div class="input-group">
            <input type='text' title="{{$form['label']}}"
                   {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=".$validation['max']:""}} class='form-control {{$name}} first_value'
                   name="{{$name}}[]" id="{{$name}}" value='{{$value}}'/> <span class="input-group-addon" style="padding: 1px;"><button
                        class="add_field_button {{$name}}  btn btn-danger  btn-xs"><i class='fa fa-plus'></i></button></span>
        </div>

        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>

    </div>
</div>
