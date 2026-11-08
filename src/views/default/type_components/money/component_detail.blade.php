{{-- An empty value is shown as is: number_format() only accepts numbers on PHP 8 --}}
{{ is_numeric($value) ? number_format((float) $value) : $value }}
