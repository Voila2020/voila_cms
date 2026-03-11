@php
    $form = is_array($form ?? null) ? $form : [];
    $form['translation'] = $form['translation'] ?? false;
    $form['value'] = $form['value'] ?? '';
@endphp

@if (!($form['translation'] ?? false))
    {!! $value !!}
@else
    <div>
        @foreach ($websiteLanguages as $lang)
            @php
                $value = $form['value'];
                $value = $row->{$name . '_' . $lang->code} ?? $value;
            @endphp
            <div><strong>{{ $lang->name }}</strong>: {!! $value !!}</div>
        @endforeach
    </div>
@endif
