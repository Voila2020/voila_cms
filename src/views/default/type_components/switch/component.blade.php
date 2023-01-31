<div class='form-group {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
    id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
    <label class='control-label col-sm-2'>
        {{ cbLang($form['label']) }}
        @if ($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{ $col_width ?: 'col-sm-10' }}">
        <input class='form-control cms_switch_input' type='checkbox' title="{{ $form['label'] }}" {{ $readonly }}
            {!! $placeholder !!} {{ $disabled }} name="{{ $name }}" id="switch_{{ $name }}"
            value="{{ $value ? $value : ($form['default_value'] && $command != 'edit' ? $form['default_value'] : '') }}"
            {{ $value == 1 ? 'checked' : ($form['default_value'] == 1 && $command != 'edit' ? 'checked' : '') }} />
        <label class='cms_switch_label' for='switch_{{ $name }}'>Toggle</label>
        <div class="text-danger">{!! $errors->first($name) ? "<i class='fa fa-info-circle'></i> " . $errors->first($name) : '' !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>

    </div>
</div>
