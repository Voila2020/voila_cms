@push('bottom')
    <script type="text/javascript">
        document.getElementById('{{ $name }}').addEventListener('input', function (e) {
            let val = e.target.value;

            // 1. Convert to lowercase
            val = val.toLowerCase();

            // 2. Replace spaces and the listed symbols with a dash
            val = val.replace(/[!@#$%^&*()=+{}\[\]|\\:;\"'<>,?\/\s]+/g, '-');

            e.target.value = val;
        });
    </script>
@endpush