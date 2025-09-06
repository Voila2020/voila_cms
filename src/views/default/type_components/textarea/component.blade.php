@if (!@$form['translation'])
    @if($current_language->default != null && $current_language->default == 1)
        <div class='form-group {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
            id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
            @if($form_using_ai_actions)
            {!! CRUDBooster::generateAIActionsList($name,$form['type']) !!}
            @endif
            <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}
                @if ($required)
                    <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
                @endif
            </label>
            <div class="{{ $col_width ?: 'col-sm-10' }}">
                <textarea name="{{ $form['name'] }}" id="{{ $name }}" {{ $required }} {{ $readonly }}
                    {!! $placeholder !!} {{ $disabled }} {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }}
                    class='form-control' rows='5'>{{ $value }}</textarea>
                <div class="text-danger">{!! $errors->first($name) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name) : '' !!}</div>
                <p class='help-block'>{{ @$form['help'] }}</p>
            </div>
        </div>
    @endif
@else
    @php
        @$value = isset($form['value']) ? $form['value'] : '';
        @$value = isset($row->{$name. '_' . $current_language->code}) ? $row->{$name. '_' . $current_language->code} : $value;
        $old = old($name . '_' . $current_language->code);
        $value = !empty($old) ? $old : $value;
    @endphp
    <div class='form-group {{ $header_group_class }} {{ $errors->first($name ."_". $current_language->code) ? 'has-error' : '' }}'
        id='form-group-{{ $name ."_". $current_language->code }}' style="{{ @$form['style'] }}">
        @if($form_using_ai_actions)
            {!! CRUDBooster::generateAIActionsList($name ."_". $current_language->code,$form['type'],$current_language->code,$form['translation']) !!}
        @endif
        <label class='control-label col-sm-2'>{{ cbLang($form['label']) ." - ". $current_language->name }}
            @if ($required)
                <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
            @endif
        </label>
        <div class="{{ $col_width ?: 'col-sm-10' }}">
            <textarea name="{{ $name ."_". $current_language->code }}" id="{{ $name ."_". $current_language->code }}" {{ $required }} {{ $readonly }}
                {!! $placeholder !!} {{ $disabled }} {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }}
                class='form-control' rows='5'>{{ $value }}</textarea>
            <div class="text-danger">{!! $errors->first($name ."_". $current_language->code) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name ."_". $current_language->code) : '' !!}</div>
            <p class='help-block'>{{ @$form['help'] }}</p>
        </div>
    </div>
@endif
