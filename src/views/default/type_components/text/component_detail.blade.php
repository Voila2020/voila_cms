@php
    $form = is_array($form ?? null) ? $form : [];
    $form['translation'] = $form['translation'] ?? false;
    $form['value'] = $form['value'] ?? '';
@endphp

@if (!@$form['translation'])
    {{ $value }}
@else
    <div>
        @foreach ($websiteLanguages as $lang)
            @php
                @$value = $form['value'];
                @$value = isset($row->{$name . '_' . $lang->code}) ? $row->{$name . '_' . $lang->code} : $value;
            @endphp
            <p ><strong>{{ $lang->name }}</strong>: {{ $value }}</p>
        @endforeach
    </div>
@endif
