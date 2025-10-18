@push('bottom')
    <script src="{{ asset('vendor/crudbooster/assets/js/customizeTinymce.js') }}"></script>
    <script type="text/javascript">
        var selectId = null;
        var $id = '';
        var $template = '';
        $(document).ready(function () {
            $id = "{{ $id }}";
            $template = `"{{ $row->template }}"`;
        })

        $('#modalInsertPhotoEditor').on('hidden.bs.modal', function () {
            $("#input_{{ $name }}").trigger("change");
            @if (@$form['translation'])
                $("#input_{{ $name }}_{{ $current_language->code }}").trigger("change");

            @endif
        })

        $('#modalInsertEmailTemplate').on('hidden.bs.modal', function () {
            $("#input_{{ $name }}").trigger("change")
        })
        @if (!@$form['translation'])
            $("#input_{{ $name }}").on("change", function () {
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
                $("#input_{{ $name }}_{{ $current_language->code }}").on("change", function () {
                    var is_empty = $(this).val();
                    if (is_empty) {
                        let slash = is_empty.charAt(0);
                        if (slash == '/') is_empty = is_empty.substring(1);
                        tinymce.get('textarea_{{ $name }}_{{ $current_language->code }}').insertContent(
                            '<img src="' +
                            '{{ URL::asset('') }}' +
                            is_empty +
                            '" data-mce-src="' + '{{ URL::asset('') }}' + is_empty +
                            '" style="width:100px;height:100px;">');
                    }
                    $(this).val("");
                });
        @endif
    </script>
@endpush
@push('head')
    <style>
        .tox-shadowhost.tox-fullscreen,
        .tox.tox-tinymce.tox-fullscreen {
            z-index: 1049!important;
        }
    </style>
@endpush
@if (!@$form['translation'])
    @if($current_language->default != null && $current_language->default == 1)
        <div class='form-group' id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
            @if($form_using_ai_actions)
            {!! CRUDBooster::generateAIActionsList($name,$form['type']) !!}
            @endif
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
    @endif
@else
    @php
        @$value = isset($form['value']) ? $form['value'] : '';
        @$value = isset($row->{$name . '_' . $current_language->code}) ? $row->{$name . '_' . $current_language->code} : $value;
        $old = old($name . '_' . $current_language->code);
        $value = !empty($old) ? $old : $value;
    @endphp
    <div class='form-group' id='form-group-{{ $name . '_' . $current_language->code }}' style="{{ @$form['style'] }}">
        @if($form_using_ai_actions)
        {!! CRUDBooster::generateAIActionsList($name ."_". $current_language->code,$form['type'],$current_language->code,$form['translation']) !!}
        @endif
        <label class='control-label col-sm-2'>{{ cbLang($form['label']) . ' - ' . $current_language->name }}
            @if ($required)
                <span class='text-danger' title='{!! cbLang('this_field_is_required') !!}'>*</span>
            @endif
        </label>

        <div class="{{ $col_width ?: 'col-sm-10' }}">
            <input type="hidden" id="input_{{ $name . '_' . $current_language->code }}">
            <textarea id='textarea_{{ $name . '_' . $current_language->code }}' {{ $readonly }} {{ $disabled }}
                name="{{ $name . '_' . $current_language->code }}" class='form-control' rows='5'>{!! $value !!}</textarea>
            <div class="text-danger">{{ $errors->first($name . '_' . $current_language->code) }}</div>
            <p class='help-block'>{{ cbLang(@$form['help']) }}</p>
        </div>
    </div>
@endif


<div class="modal fade" id="modalInsertPhotoEditor">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="buttons">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <button type="button" class="resize" title="<?php echo cbLang('filemanager.resize'); ?>"><i
                            class="fa fa-expand" aria-hidden="true"></i></button>
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
    $editorCss = [];
    $editorCssFiles = [];
    $editorCssArray = [];

    $editorFonts = [];
    foreach ($websiteLanguages as $lang) {
        $editorCss[$lang->code] = Crudbooster::getSetting('editor_css_links_' . $lang->direction);
        $editorCssFiles[$lang->code] = explode('|', $editorCss[$lang->code]);
        $editorCssArray[$lang->code] = [];
        foreach ($editorCssFiles[$lang->code] as $file) {
            $editorCssArray[$lang->code][] = "'" . trim($file) . "'";
        }

        $fonts_values = Crudbooster::getSetting('editor_fonts_' . $lang->direction);
        if($fonts_values != ''){
            $editorFonts[$lang->code] = str_replace("'","",str_replace('"',"",$fonts_values));
        }else{
            $editorFonts[$lang->code] = '';
        }
    }
    

?>
<?php
    $editorJs = Crudbooster::getSetting('editor_js_links');
    $editorJsArray = [];
    if($editorJs != '' && $editorJs != null){
        $editorJsFiles = explode(',', $editorJs);
         foreach ($editorJsFiles as $file) {
            $editorJsArray[] = "'" . trim($file) . "'";
        }
    }

    $default_lang_code = DB::table('languages')->where('default',1)->first()->code ?? 'en';
    if(CRUDBooster::getCurrentModule()->table_name != '' && CRUDbooster::getCurrentId() != '' && CRUDBooster::getCurrentMethod() == 'getEdit'){
        $record_info = DB::table(CRUDBooster::getCurrentModule()->table_name)->where('id',CRUDbooster::getCurrentId())->first();   
         $lang_id = $record_info->lang;
         if($lang_id){
            $default_lang_code = DB::table('languages')->where('id',$lang_id)->first()->code;
         }
    }
?>

@push('bottom')
    <script>
     
        editorJsArray = <?php echo json_encode($editorJsArray); ?>;
        $(function () {
            let selector = '#textarea_{{ $name }}';

            cardsMenu = {
                            cards: {
                                title: 'Cards',
                                items: ''
                            }
                        }
            if ('customMenu' in window) {
                cardsMenu = customMenu;
            }

            let options = {
                selector: selector,
                valid_elements: '*[*]',
                extended_valid_elements: 'code',
                remove_trailing_brs: false,
                plugins: 'preview code importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons  ',
                mobile: {
                    plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons '
                },
                menubar: 'file edit view insert format tools table tc help cards',
                toolbar: 'FileManager | EmailBuilder | fontfamily fontsize blocks | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat all-clear-format selected-clear-format | pagebreak | charmap emoticons | fullscreen  preview print | template link anchor codesample | code | ltr rtl',
                @if($editorFonts[$default_lang_code] != '')
                    font_family_formats:"{{ $editorFonts[$default_lang_code] }}" ,
                @endif
                content_css: [<?php echo implode(',', $editorCssArray[$default_lang_code]); ?>],
                content_js: [<?php echo implode(',', $editorJsArray); ?>],
                setup: function (editor) {
                    //Add a custom validator to the form
                    $('form').on('submit', function (e) {
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
                        onAction: function (_) {
                            $("#modalInsertPhotoEditor .modal-body").html(
                                `<iframe width="100%" height="400" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=input_`+selector.replace("#textarea_","")+`" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                            );
                            $("#modalInsertPhotoEditor").modal();
                        }
                    });

                    //////********************************************
                    editor.ui.registry.addButton('mymodalbutton', {
                        text: 'My Modal Button',
                        onAction: function () {
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
                                    onAction: function () {
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
                    editor.on('change', function (e) {
                        editor.save();

                    });
                    //*************************************
                    editor.ui.registry.addButton('selected-clear-format', {
                        text: 'Clear (Selected)',
                        onAction: function () {

                            var selectedNode = editor.selection.getNode();
                            if (selectedNode) {
                                // Create a new element with the desired HTML content (e.g., a new paragraph)
                                var newElement = document.createElement('p');
                                newElement.innerHTML = selectedNode.innerHTML;
                                // Replace the selected node with the new element
                                selectedNode.parentNode.replaceChild(newElement, selectedNode);
                                editor.setContent(editor.getContent());
                            }
                        }
                    });
                    editor.ui.registry.addButton('all-clear-format', {
                        text: 'Clear (All)',
                        onAction: function () {
                            var content = editor.getContent();
                            var text = content.replace(/<\/?(?!(p|br)\b)[^>]*>/g, ''); // Remove all HTML tags except for <p> and <br>
                            editor.setContent(text);
                        }
                    });

                    //*************************************
                    /*open file manager when double click on image*/

                    editor.on('DblClick', function (e) {
                        const target = e.target;
                        if (target.nodeName === 'IMG') {
                            $("#modalInsertPhotoEditor .modal-body").html(
                                `<iframe width="100%" height="400" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=input_`+selector.replace("#textarea_","")+`" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                            );
                            $("#modalInsertPhotoEditor").modal();
                        }
                    });
                    //*************************************
                    /*menu*/
                    if (typeof registerMenu === 'function') {
                        registerMenu(editor);
                    }
                    /*Add Javascript Files*/
                    editor.on('init', function () {
                        doc = editor.getDoc();
                        editorJsArray.forEach(element => {
                            script1 = doc.createElement("script");
                            script1.src = element;
                            doc.head.appendChild(script1);
                        });
                    });

                },
                // init_instance_callback: insert_contents,
                font_size_formats: "12pt 6px 7px 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 29px 30px 31px 32px 33px 34px 35px 36px 37px 38px 39px 40px",
                importcss_append: true,
                height: 400,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                tinycomments_mode: 'embedded',
                contextmenu: 'link image table configurepermanentpen',
                menu: cardsMenu
            };

            if (customWebsiteColors && customWebsiteColors.length > 0) {
                options.color_map = customWebsiteColors;
                options.color_cols = 3;
            }

            @if (!@$form['translation'])
                @if($current_language->default != null && $current_language->default == 1)
                    var tinyEditor = tinymce.init(options);
                @endif
            @else
                options = {
                    selector: selector,
                    valid_elements: '*[*]',
                    extended_valid_elements: 'code',
                    remove_trailing_brs: false,
                    plugins: 'preview code importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons  ',
                    mobile: {
                        plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons '
                    },
                    menubar: 'file edit view insert format tools table tc help cards',
                    toolbar: 'FileManager | EmailBuilder | fontfamily fontsize blocks | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat all-clear-format selected-clear-format | pagebreak | charmap emoticons | fullscreen  preview print | template link anchor codesample | code | ltr rtl',
                    @if($editorFonts[$current_language->code] != '')
                        font_family_formats:"{{ $editorFonts[$current_language->code] }}" ,
                    @endif
                    content_css: [<?php        echo implode(',', $editorCssArray[$lang->code]); ?>],
                    setup: function (editor) {
                        //Add a custom validator to the form
                        $('form').on('submit', function (e) {
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
                            onAction: function (_) {
                                $("#modalInsertPhotoEditor .modal-body").html(
                                    `<iframe width="100%" height="400" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=input_`+selector.replace("#textarea_","")+`" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                                );
                                $("#modalInsertPhotoEditor").modal();
                            }
                        });

                        //////********************************************
                        editor.ui.registry.addButton('mymodalbutton', {
                            text: 'My Modal Button',
                            onAction: function () {
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
                                        onAction: function () {
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
                        editor.on('change', function (e) {
                            editor.save();

                        });
                        //*************************************
                        editor.ui.registry.addButton('selected-clear-format', {
                            text: 'Clear (Selected)',
                            onAction: function () {

                                var selectedNode = editor.selection.getNode();
                                if (selectedNode) {
                                    // Create a new element with the desired HTML content (e.g., a new paragraph)
                                    var newElement = document.createElement('p');
                                    newElement.innerHTML = selectedNode.innerHTML;
                                    // Replace the selected node with the new element
                                    selectedNode.parentNode.replaceChild(newElement, selectedNode);
                                    editor.setContent(editor.getContent());
                                }
                            }
                        });
                        editor.ui.registry.addButton('all-clear-format', {
                            text: 'Clear (All)',
                            onAction: function () {
                                var content = editor.getContent();
                                var text = content.replace(/<\/?(?!(p|br)\b)[^>]*>/g, ''); // Remove all HTML tags except for <p> and <br>
                                editor.setContent(text);
                            }
                        });
                        //*************************************
                        //*************************************
                        /*open file manager when double click on image*/

                        editor.on('DblClick', function (e) {
                            const target = e.target;
                            if (target.nodeName === 'IMG') {
                                $("#modalInsertPhotoEditor .modal-body").html(
                                    `<iframe width="100%" height="400" src="{{ Route('dialog') }}?type=2&multiple=0&field_id=input_`+selector.replace("#textarea_","")+`" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                                );
                                $("#modalInsertPhotoEditor").modal();
                            }
                        });
                        //*************************************
                        /*menu*/
                        if (typeof registerMenu === 'function') {
                            registerMenu(editor, '{{ $current_language->direction }}');
                        }
                        /*Add Javascript Files*/
                        editor.on('init', function () {
                            doc = editor.getDoc();
                            doc.documentElement.setAttribute('dir', '{{ $current_language->direction }}');
                            editorJsArray.forEach(element => {
                                script1 = doc.createElement("script");
                                script1.src = element;
                                doc.head.appendChild(script1);
                            });
                        });
                    },
                    // init_instance_callback: insert_contents,
                    font_size_formats: "12pt 6px 7px 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 29px 30px 31px 32px 33px 34px 35px 36px 37px 38px 39px 40px",
                    importcss_append: true,
                    height: 400,
                    image_caption: true,
                    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                    noneditable_noneditable_class: 'mceNonEditable',
                    toolbar_mode: 'sliding',
                    tinycomments_mode: 'embedded',
                    contextmenu: 'link image table configurepermanentpen',
                    menu: cardsMenu,
                };

                if (customWebsiteColors && customWebsiteColors.length > 0) {
                    options.color_map = customWebsiteColors;
                    options.color_cols = 3;
                }
                    
                selector = '#textarea_{{ $name }}_{{ $current_language->code }}';
                options.selector = selector;
                tinymce.init(options);
            @endif
            })

        function insert_contents(inst) {

        }
    </script>
@endpush
