@php
    $fonts = crocodicstudio\crudbooster\fonts\Fontawesome::getIcons();
@endphp
<div class='form-group {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
    id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
    <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}
        @if ($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{ $col_width ?: 'col-sm-10' }}">
        <select id='list-icon' class="form-control" name="icon" style="font-family: 'FontAwesome', Helvetica;">
            <option value="">** Select an Icon</option>
            @foreach ($fonts as $font)
                <option value='fa fa-{{ $font }}' {{ $row->icon == "fa fa-$font" ? 'selected' : '' }}
                    data-label='{{ $font }}'>{{ $font }}</option>
            @endforeach
        </select>
    </div>
</div>
