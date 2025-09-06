@if($current_language->default != null && $current_language->default == 1)
    @php
        $fonts = crocodicstudio\crudbooster\fonts\Fontawesome::getIcons();
    @endphp
    <div class='form-group {{ $header_group_class }} {{ $errors->first($name) ? 'has-error' : '' }}'
        id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
        <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}
            @if ($required)
                <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
            @endif
        </label>

        <div class="{{ $col_width ?: 'col-sm-10' }}">
            <select id='list-icon_{{ $name }}' class="form-control" name="{{ $name }}"
                style="font-family: 'FontAwesome', Helvetica;">
                <option value="">** Select an Icon</option>
                @foreach ($fonts as $font)
                    @if($font == 'x-twitter')
                        <option value='fa-brands fa-{{ $font }}' {{ $value == "fa-brands fa-$font" ? 'selected' : '' }} data-label='{{ $font }}'>{{ $font }}</option>
                    @else
                        <option value='fa fa-{{ $font }}' {{ $value == "fa fa-$font" ? 'selected' : '' }} data-label='{{ $font }}'>{{ $font }}</option>
                    @endIf
                @endforeach
            </select>
        </div>
    </div>

    @push('bottom')
        <script src="{{ asset('vendor/crudbooster/assets/select2/dist/js/select2.full.min.js') }}"></script>
        <script>
            $(function() {
                function format(icon) {
                    debugger
                    var originalOption = icon.element;
                    var label = $(originalOption).text();
                    var val = $(originalOption).val();
                    if (!val) return label;
                    var $resp = $('<span><i style="margin-top:5px" class="pull-right ' + $(originalOption).val() +
                        '"></i> ' +
                        $(originalOption).data('label') + '</span>');
                    return $resp;
                }

                $('#list-icon_{{ $name }}').select2({
                    width: "100%",
                    templateResult: format,
                    templateSelection: format
                });
            });
        </script>
    @endpush
@endif