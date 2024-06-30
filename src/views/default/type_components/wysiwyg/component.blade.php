@push('bottom')
    <script type="text/javascript">
        var selectId = null;
        var $id = '';
        var $template = '';
        $(document).ready(function() {
            $id = "{{ $id }}";
            $template = `"{{ $row->template }}"`;
        })

        $('#modalInsertPhotoEditor').on('hidden.bs.modal', function() {
            $("#input_{{ $name }}").trigger("change")
        })

        $('#modalInsertEmailTemplate').on('hidden.bs.modal', function() {
            $("#input_{{ $name }}").trigger("change")
        })
        @if (!@$form['translation'])
            $("#input_{{ $name }}").on("change", function() {
                var is_empty = $(this).val();
                if (is_empty) {
                    let slash = is_empty.charAt(0);
                    if (slash == '/') is_empty = is_empty.substring(1);
                    tinymce.get('textarea_{{ $name }}').insertContent('<img src="' +
                        '{{ URL::asset('') }}' +
                        is_empty +
                        '" data-mce-src="' + '{{ URL::asset('') }}' + is_empty +
                        '" style="width:100px;height:100px;">');
                }
                $(this).val("");
            });
        @else
            @foreach ($websiteLanguages as $lang)
                $("#input_{{ $name }}_{{ $lang->code }}").on("change", function() {
                    var is_empty = $(this).val();
                    if (is_empty) {
                        let slash = is_empty.charAt(0);
                        if (slash == '/') is_empty = is_empty.substring(1);
                        tinymce.get('textarea_{{ $name }}_{{ $lang->code }}').insertContent(
                            '<img src="' +
                            '{{ URL::asset('') }}' +
                            is_empty +
                            '" data-mce-src="' + '{{ URL::asset('') }}' + is_empty +
                            '" style="width:100px;height:100px;">');
                    }
                    $(this).val("");
                });
            @endforeach
        @endif
    </script>
@endpush
@if (!@$form['translation'])
    <div class='form-group' id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
        <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}
            @if ($required)
                <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
            @endif
        </label>

        <div class="{{ $col_width ?: 'col-sm-10' }}">
            <input type="hidden" id="input_{{ $name }}">
            <textarea id='textarea_{{ $name }}' {{ $readonly }} {{ $disabled }} name="{{ $form['name'] }}"
                class='form-control' rows='5'>{!! $value !!}</textarea>
            <div class="text-danger">{{ $errors->first($name) }}</div>
            <p class='help-block'>{{ cbLang(@$form['help']) }}</p>
        </div>
    </div>
@else
    @foreach ($websiteLanguages as $lang)
        @php
            @$value = isset($form['value']) ? $form['value'] : '';
            @$value = isset($row->{$name . '_' . $lang->code}) ? $row->{$name . '_' . $lang->code} : $value;
            $old = old($name . '_' . $lang->code);
            $value = !empty($old) ? $old : $value;
        @endphp
        <div class='form-group' id='form-group-{{ $name . '_' . $lang->code }}' style="{{ @$form['style'] }}">
            <label class='control-label col-sm-2'>{{ cbLang($form['label']) . ' - ' . $lang->name }}
                @if ($required)
                    <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
                @endif
            </label>

            <div class="{{ $col_width ?: 'col-sm-10' }}">
                <input type="hidden" id="input_{{ $name . '_' . $lang->code }}">
                <textarea id='textarea_{{ $name . '_' . $lang->code }}' {{ $readonly }} {{ $disabled }}
                    name="{{ $name . '_' . $lang->code }}" class='form-control' rows='5'>{!! $value !!}</textarea>
                <div class="text-danger">{{ $errors->first($name . '_' . $lang->code) }}</div>
                <p class='help-block'>{{ cbLang(@$form['help']) }}</p>
            </div>
        </div>
    @endforeach
@endif


<div class="modal fade" id="modalInsertPhotoEditor">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="buttons">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <button type="button" class="resize" title="<?php echo cbLang('filemanager.resize'); ?>"><i class="fa fa-expand"
                            aria-hidden="true"></i></button>
                </div>
                <div class="title-sec">
                    <h4 class="modal-title">Insert Image</h4>
                </div>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    function openIfram(field_name, table_name, current_id) {

        var url = "{{ URL::to('/') }}" + "/modules/fromscratch?filed_name=" + field_name + "&table_name=" +
            table_name + "&current_id=" + current_id;
        $("#full-screen-me").attr("src", url);

    }
</script>

<?php
$editorCss = Crudbooster::getSetting('editor_css_links');
$editorCssFiles = explode(',', $editorCss);
$editorCssArray = [];
foreach ($editorCssFiles as $file) {
    $editorCssArray[] = "'" . trim($file) . "'";
}
?>
@push('bottom')
    <script>
        $(function() {
            let selector = '#textarea_{{ $name }}';
            let options = {
                selector: selector,
                valid_elements: '*[*]',
                extended_valid_elements: 'code',
                plugins: 'preview code importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons  ',
                mobile: {
                    plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons '
                },
                menubar: 'file edit view insert format tools table tc help',
                toolbar: 'FileManager | EmailBuilder | fontfamily fontsize blocks | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview print | template link anchor codesample | code | ltr rtl',
                content_css: [<?php echo implode(',', $editorCssArray); ?>],
                setup: function(editor) {
                    //Add a custom validator to the form
                    $('form').on('submit', function(e) {
                        var $tinyMceEditor = $('#textarea_{{ $name }}');
                        if ($tinyMceEditor.length && !editor.getContent().trim() &&
                            "{{ $required }}") {

                            editor.notificationManager.open({
                                text: 'This field is required.',
                                type: 'warning',
                                position: 'top-right'
                            });

                            $('html, body').animate({
                                scrollTop: $tinyMceEditor.offset().top - 300
                            }, 100);

                            e.preventDefault();
                        }
                    });
                    //////********************************************
                    editor.ui.registry.addButton('FileManager', {
                        text: 'File Manager',
                        onAction: function(_) {
                            $("#modalInsertPhotoEditor .modal-body").html(
                                `<iframe width="100%" height="400" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=input_{{ $name }}" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                            );
                            $("#modalInsertPhotoEditor").modal();
                        }
                    });

                    //////********************************************
                    editor.ui.registry.addButton('mymodalbutton', {
                        text: 'My Modal Button',
                        onAction: function() {
                            // Open the custom modal window
                            editor.windowManager.open({
                                title: 'My Modal Window',
                                body: {
                                    type: 'panel',
                                    items: [{
                                        type: 'textbox',
                                        name: 'textbox',
                                        label: 'Textbox'
                                    }]
                                },
                                buttons: [{
                                        type: 'cancel',
                                        text: 'Cancel'
                                    },
                                    {
                                        type: 'submit',
                                        text: 'Submit',
                                        primary: true,
                                        onAction: function() {
                                            // Custom button behavior goes here
                                            editor.insertContent(
                                                'Hello, ' +
                                                editor.windowManager
                                                .getWindows()[0]
                                                .getData().textbox +
                                                '!'
                                            );
                                            editor.windowManager.close();
                                        }
                                    }
                                ]
                            });
                        }
                    });
                    //////********************************************
                    editor.on('change', function(e) {
                        editor.save();

                    });
                },
                // init_instance_callback: insert_contents,
                font_size_formats: "12pt 6px 7px 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 29px 30px 31px 32px 33px 34px 35px 36px 37px 38px 39px 40px",
                importcss_append: true,
                templates: [{
                        title: 'New Table',
                        description: 'creates a new table',
                        content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
                    },
                    {
                        title: 'Starting my story',
                        description: 'A cure for writers block',
                        content: 'Once upon a time...'
                    },
                    {
                        title: 'New list with dates',
                        description: 'New List with dates',
                        content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
                    }
                ],
                template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                height: 400,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                tinycomments_mode: 'embedded',
                contextmenu: 'link image table configurepermanentpen',
            };
            @if (!@$form['translation'])
                var tinyEditor = tinymce.init(options);
            @else
                @foreach ($websiteLanguages as $lang)
                    selector = '#textarea_{{ $name }}_{{ $lang->code }}';
                    options.selector = selector;
                    tinymce.init(options);
                @endforeach
            @endif
        })

        function insert_contents(inst) {

        }
    </script>
@endpush
