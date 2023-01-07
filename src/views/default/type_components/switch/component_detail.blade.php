@if ($value == 1)
    {{ @$form['true_label'] ?? 'Yes' }}
@else
    {{ @$form['false_label'] ?? 'No' }}
@endif
