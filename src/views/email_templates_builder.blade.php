<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/css/grapes.min.css">
</head>

<body>
    <div id="gjs">
        <mjml>
            <mj-body background-color="#DCEDF6" width="600px">
                <mj-wrapper padding="0px" background-color="#ffffff">
                    <mj-section>
                        <mj-column>
                            <mj-text font-size="14px" color="rgb(112, 107, 109)"
                                font-family="Arial, Helvetica, sans-serif" font-weight="400" line-height="1.5"
                                font-style="normal" text-decoration="initial">
                                <div
                                    style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:rgb(112, 107, 109);font-weight:400;line-height:1.5;font-style:normal;text-decoration:initial">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    Nisl tincidunt eget nullam non nisi est sit.
                                </div>
                            </mj-text>
                        </mj-column>
                    </mj-section>
                </mj-wrapper>
                <mj-wrapper padding="0px">
                    <mj-section type="footer" version="0.1.0">
                        <mj-column data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                            data-gjs-droppable="false" data-gjs-highlightable="false" data-gjs-hoverable="false"
                            data-gjs-badgable="false" data-gjs-selectable="false">
                            <mj-text data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                                data-gjs-droppable="false" data-gjs-editable="false" data-gjs-hoverable="false"
                                data-gjs-selectable="false" font-size="12px" color="#626262"
                                font-family="Arial, Helvetica, sans-serif" align="center" type="text"
                                padding="0 25px">
                                Copyright &copy; <span class="escala-element-custom-field"
                                    data-field="year">[YEAR]</span> [ALL_RIGHTS_RESERVED]
                            </mj-text>
                            <mj-text data-gjs-copyable="false" data-gjs-removable="false" data-gjs-draggable="false"
                                data-gjs-droppable="false" data-gjs-editable="false" data-gjs-hoverable="false"
                                data-gjs-selectable="false" font-size="12px" color="#626262"
                                font-family="Arial, Helvetica, sans-serif" align="center" type="text"
                                padding="0 25px">
                                <span class="escala-element-custom-field"
                                    data-field="company_data">[COMPANY_DATA]</span>
                            </mj-text>
                        </mj-column>
                    </mj-section>
                </mj-wrapper>
            </mj-body>
        </mjml>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/grapes.min.js"></script>
    <script src=""></script>
    <script src="https://unpkg.com/grapesjs-mjml@0.5.2/dist/grapesjs-mjml.min.js"></script>
    <script>
        var editor = grapesjs.init({
            clearOnRender: true,
            fromElement: 1,
            container: '#gjs',
            storageManager: false,
            plugins: ['grapesjs-mjml'],
            pluginsOpts: {
                'grapesjs-mjml': {
                    columnsPadding: ''
                }
            }
        });
        //show modal save..
        editor.Panels.addButton("options", [{
            id: "save",
            className: "fa fa-floppy-o icon-blank",
            command: function(editor1, sender) {
                editor.store();
            },
            attributes: {
                title: "Save Email Template"
            },
        }, ]);
    </script>
</body>

</html>
