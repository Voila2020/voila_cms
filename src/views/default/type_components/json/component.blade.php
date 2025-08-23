<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='control-label col-sm-2'>{{cbLang($form['label'])}}
        @if($required)
            <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">

        <div id="{{$name}}"></div>
        <textarea name="{{$name}}" style="display:none"></textarea>

        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>

    </div>
</div>
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
        const container = document.getElementById('{{$name}}')
        const options = {
            mode: 'text',
            modes: ['text', 'code'],
            onEditable: function (node) {
                if (!node.path) {
                    // In modes code and text, node is empty: no path, field, or value
                    // returning false makes the text area read-only
                    return false;
                }
            },
            onModeChange: function (newMode, oldMode) {
                console.log('Mode switched from', oldMode, 'to', newMode)
            }-
        }

        const editor = new JSONEditor(container, options, '{!!$value!!}')


        $('[name="{{$name}}"]').parents('form').on('submit', function () {
            $('[name="{{$name}}"]').val(JSON.stringify(editor.getValue()));
            return true;
        })
    });

</script>
@endpush