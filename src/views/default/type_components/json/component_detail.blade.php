<div id="{{$name}}"></div>

@push("bottom")

    <script type="text/javascript">
        $(document).ready(function () {
            // Set an option globally
            // JSONEditor.defaults.options.theme = 'bootstrap2';
            // JSONEditor.plugins.select2.enable = false;
            // JSONEditor.plugins.selectize.enable = true;//to avoid select2

            // Set an option during instantiation
            // var editor = new JSONEditor(document.getElementById('{{$name}}'), {
            //     theme: 'bootstrap2'
            // });
            // const container = document.getElementById('{{$name}}')
            // const options = {
            //     mode: 'tree',
            //     modes: ['tree'],
            //     onEditable: function (node) {
            //         if (!node.path) {
            //             // In modes code and text, node is empty: no path, field, or value
            //             // returning false makes the text area read-only
            //             return false;
            //         }
            //     },
            //     onModeChange: function (newMode, oldMode) {
            //         console.log('Mode switched from', oldMode, 'to', newMode)
            //     }
            // }

            // const editor = new JSONEditor(container, options, @json($value))
            const container = document.getElementById('{{$name}}')

            const options = {
                mode: 'view'
            }

            json = JSON.parse('{!! $value !!}');
            const editor = new JSONEditor(container, options, json)


            $('[name="{{$name}}"]').parents('form').on('submit', function () {
                $('[name="{{$name}}"]').val(JSON.stringify(editor.getValue()));
                return true;
            })
        });

    </script>
@endpush
