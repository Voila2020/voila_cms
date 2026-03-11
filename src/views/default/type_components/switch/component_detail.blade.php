@php
    $form = is_array($form ?? null) ? $form : [];
    $form['true_label'] = $form['true_label'] ?? 'Yes';
    $form['false_label'] = $form['false_label'] ?? 'No';
@endphp

@if ($value == 1)
    {{ $form['true_label'] }}
@else
    {{ $form['false_label'] }}
@endif
