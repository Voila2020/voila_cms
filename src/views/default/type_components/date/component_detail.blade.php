{{-- Carbon instead of strftime() (deprecated in PHP 8.1); 'Y' not '%G' (ISO week year), and UTF-8 month names --}}
{{ !empty($value) ? ucfirst(\Carbon\Carbon::parse($value)->locale(App::getLocale())->translatedFormat('F, d Y')) : null }}
