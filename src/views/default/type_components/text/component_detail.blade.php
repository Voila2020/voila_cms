@if (!@$form['translation'])
    {{ $value }}
@else
    <div>
        @foreach ($websiteLanguages as $lang)
            @php
                @$value = isset($form['value']) ? $form['value'] : '';
                @$value = isset($row->{$name . '_' . $lang->code}) ? $row->{$name . '_' . $lang->code} : $value;
            @endphp
            <p ><strong>{{ $lang->name }}</strong>: {{ $value }}</p>
        @endforeach
    </div>
@endif
