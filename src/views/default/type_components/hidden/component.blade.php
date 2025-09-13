@if (!@$form['translation'])
    @if($current_language->default != null && $current_language->default == 1)

    <input type='hidden' name="{{$name}}" value='{{$value}}'/>

    @endif
@else
    @php
        @$value = isset($form['value']) ? $form['value'] : '';
        @$value = isset($row->{$name. '_' . $current_language->code}) ? $row->{$name. '_' . $current_language->code} : $value;
        $old = old($name . '_' . $current_language->code);
        $value = !empty($old) ? $old : $value;
    @endphp
    <input type='hidden' name="{{ $name . '_' . $current_language->code }}" value='{{$value}}'/>
@endif