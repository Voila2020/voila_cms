@push('bottom')
    <script type="text/javascript">
        @foreach($forms as $slug_form)
        @if(($slug_form['type'] ?? '') == 'slug' && !empty($slug_form['name']))
        (function () {
            // Loaded once for all slug fields, also on the detail page where there is no input
            var input = document.getElementById(@json($slug_form['name']));
            if (!input) {
                return;
            }
            input.addEventListener('input', function (e) {
                let val = e.target.value;

                // 1. Convert to lowercase
                val = val.toLowerCase();

                // 2. Replace spaces and the listed symbols with a dash
                val = val.replace(/[!@#$%^&*()=+{}\[\]|\\:;\"'<>,?\/\s]+/g, '-');

                e.target.value = val;
            });
        })();
        @endif
        @endforeach
    </script>
@endpush
