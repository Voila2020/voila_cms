@php
    $form = is_array($form ?? null) ? $form : [];
    $form['translation'] = $form['translation'] ?? false;
    $form['value'] = $form['value'] ?? '';
@endphp

@if (!@$form['translation'])
    {!! nl2br($value) !!}
@else
    <div>
        @foreach ($websiteLanguages as $lang)
            @php
                @$value = $form['value'];
                @$value = isset($row->{$name . '_' . $lang->code}) ? $row->{$name . '_' . $lang->code} : $value;
            @endphp
            <div><strong>{{ $lang->name }}</strong>: {!! nl2br($value) !!}</div>
        @endforeach
    </div>
@endif
