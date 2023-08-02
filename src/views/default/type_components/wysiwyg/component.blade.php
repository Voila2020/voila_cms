@push('bottom')
    <script type="text/javascript">
        var selectId = null;
        var $id = '';
        var $template = '';
        $(document).ready(function() {
            $id = "{{ $id }}";
            $template = `"{{ $row->template }}"`;

            function uploadImage{{ $name }}(image) {
                var data = new FormData();
                data.append("userfile", image);
                $.ajax({
                    url: '{{ CRUDBooster::mainpath('upload-summernote') }}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "post",
                    success: function(url) {
                        var image = $('<img>').attr('src', '{{ URL::asset('') }}' + url);
                        tinymce.get('textarea_{{ $name }}').insertContent(image[0]);
                    },
                    error: function(data) {}
                });
            }
        })

        $('#modalInsertPhotoEditor').on('hidden.bs.modal', function() {
            $("#input_{{ $name }}").trigger("change")
        })

        $('#modalInsertEmailTemplate').on('hidden.bs.modal', function() {
            $("#input_{{ $name }}").trigger("change")
        })

        $("#input_{{ $name }}").on("change", function() {
            var is_empty = $(this).val();
            if (is_empty) {
                let slash = is_empty.charAt(0);
                if (slash == '/') is_empty = is_empty.substring(1);
                tinymce.get('textarea_{{ $name }}').insertContent('<img src="' + '{{ URL::asset('') }}' +
                    is_empty +
                    '" data-mce-src="' + '{{ URL::asset('') }}' + is_empty +
                    '" style="width:100px;height:100px;">');
            }
            $(this).val("");

        });
    </script>
@endpush
<div class='form-group' id='form-group-{{ $name }}' style="{{ @$form['style'] }}">
    <label class='control-label col-sm-2'>{{ cbLang($form['label']) }}</label>

    <div class="{{ $col_width ?: 'col-sm-10' }}">
        <input type="hidden" id="input_{{ $name }}">
        <!-- <a class="btn btn-primary" data-toggle="modal" onclick="openIfram('{{ $name }}','{{ CRUDBooster::getCurrentModule()->table_name }}','{{ CRUDbooster::getCurrentId() }}')" data-target="#model_scrach">edit from scratch</a> -->
        <textarea id='textarea_{{ $name }}' {{ $required }} {{ $readonly }} {{ $disabled }}
            name="{{ $form['name'] }}" class='form-control' rows='5'>{!! $value !!}</textarea>
        <div class="text-danger">{{ $errors->first($name) }}</div>
        <p class='help-block'>{{ cbLang(@$form['help']) }}</p>
    </div>
</div>


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

@if (Crudbooster::getCurrentModule()->path == 'email_templates')
    <div class="modal fade" id="modalInsertEmailTemplate">
        <div class="modal-dialog modal-lg" style="width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="buttons">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="title-sec">
                        <h4 class="modal-title">Email Template Builder</h4>
                    </div>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endif

<div class="modal fade" id="model_scrach"style="height:100%;width:100%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Insert Image</h4>
            </div>
            <div class="modal-body" style="display: contents">
                <iframe id="full-screen-me" src="" style="overflow:hidden;height:100%;width:100%" height="100%"
                    width="100%" frameborder="0" wmode="transparent"></iframe>

            </div>

        </div><!-- /.modal-content -->
    </div>
    <!-- /.
        modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="modelForms">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Select Form</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                id
                            </th>
                            <th>
                                title
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody-forms">

                    </tbody>
                </table>
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

@push('bottom')
    <script>
        var tinyEditor = tinymce.init({
            selector: '#textarea_{{ $name }}',
            valid_elements: '*[*]',
            extended_valid_elements: 'code',
            plugins: 'preview code importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons  ',
            mobile: {
                plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons '
            },
            menubar: 'file edit view insert format tools table tc help',
            toolbar: 'FileManager | EmailBuilder | fontfamily fontsize blocks | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview print | template link anchor codesample | code | ltr rtl',
            setup: function(editor) {
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
                                            editor.windowManager.getWindows()[0]
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
        });

        function insert_contents(inst) {

        }
    </script>
@endpush
