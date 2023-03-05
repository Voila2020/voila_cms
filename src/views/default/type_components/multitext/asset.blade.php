@push('bottom')
    <script>
        $(document).ready(function() {
            var max_fields_{{ $name }} = "{{ @$form['max_fields'] }}";
            var max_fields_{{ $name }} = parseInt(max_fields_{{ $name }}) ?
                max_fields_{{ $name }} : 5; //maximum input boxes allowed
            var wrapper_{{ $name }} = $(".input_fields_wrap").filter(
            ".{{ $name }}"); //Fields wrapper
            var add_button_{{ $name }} = $(".add_field_button").filter(
            ".{{ $name }}"); //Add button ID


            var count_{{ $name }} = 1; //initlal text box count
            $(add_button_{{ $name }}).click(function(e) { //on add input button click
                e.preventDefault();
                if (count_{{ $name }} < max_fields_{{ $name }}) { //max input box allowed
                    count_{{ $name }}++; //text box increment
                    $(wrapper_{{ $name }}).append(
                        '<div><input class="form-control" {{ $required }} {{ $readonly }} {!! $placeholder !!} {{ $disabled }} {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }} type="text" name="{{ $name }}[]"/><a href="#" class="remove_field {{ $name }}"><i class="fa fa-minus"></a></div>'
                        ); //add input box
                }
            });

            $(wrapper_{{ $name }}).on("click", ".remove_field ", function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                count_{{ $name }}--;
            })

            function Load() {
                var val = "{{ $value }}";
                val = val.split("|");
                $(".first_value").filter(".{{ $name }}").val(val[0]);
                for (i = 1; i < val.length; i++) {
                    $(wrapper_{{ $name }}).append(
                        ' <div > <input class="form-control" {{ $required }} {{ $readonly }} {!! $placeholder !!} {{ $disabled }} {{ $validation['max'] ? 'maxlength=' . $validation['max'] : '' }}  type="text" name="{{ $name }}[]" value="' +

                val[i] +
                        '"/><a href="#" class="remove_field {{ $name }}"><i class="fa fa-minus"></a></div>'
                        ); //add input box
                }
            }

            Load();
        });
    </script>
@endpush
