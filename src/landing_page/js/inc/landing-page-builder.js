var for_edit = false;
var edit_id = null;
$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)')
        .exec(window.location.search);

    return (results !== null) ? results[1] || 0 : false;
}

$(function() {
    var pathname = window.location.pathname;

    var filed_name = $.urlParam('filed_name');
    var current_id = $.urlParam('current_id');
    var table_name = $.urlParam('table_name');

    if (pathname.indexOf("/edit/") > -1) {
        var pathname_split = pathname.split("/");

        var id = parseInt(pathname_split[pathname_split.length - 1]);
        if (!isNaN(id)) {
            $.ajax({
                url: "/landingPage/get/" + id + "?filed_name=" + filed_name + "&table_name=" + table_name + "&current_id=" + current_id,
                success: function(res) {
                    if (res) {
                        if (res["background_color"] != "" && res["background_color"] != null) {
                            $("#color-body-temp").val(res["background_color"]);
                            $("#color-body").val(res["background_color"]);
                            $("#color-body-temp").trigger("change");
                        } else if (res["background_image"] != "" && res["background_image"] != null) {
                            $("#image-body").val(res["background_image"]);
                            $("#TempImageSelectedForBackgroundLanding").val(res["background_image"]);
                            $("#TempImageSelectedForBackgroundLanding").trigger("change");
                        }
                        for_edit = true;
                        edit_id = id;
                        $("#scratchGrid").html(res["code"]);
                        $('#scratchGrid').gridEditor({
                            new_row_layouts: [
                                [12],
                                [6, 6],
                                [9, 3],
                                [3, 9],
                                [5, 7],
                                [7, 5],
                                [4, 8],
                                [8, 4],
                                [3, 3, 3, 3],
                                [4, 4, 4]
                            ]
                        });
                    }
                }
            })
        }
    } else {
        $('#scratchGrid').gridEditor({
            new_row_layouts: [
                [12],
                [6, 6],
                [9, 3],
                [3, 9],
                [5, 7],
                [7, 5],
                [4, 8],
                [8, 4],
                [3, 3, 3, 3],
                [4, 4, 4]
            ]
        });
    }

});



tinymce.init({
    selector: "textarea.mceEditor",
    width: 796,
    height: 300,
    verify_html: false,
    forced_root_block: false,
    force_br_newlines: true,
    font_formats: "Andale Mono=andale mono,times;" +
        "Arial=arial,helvetica,sans-serif;" +
        "Arial Black=arial black,avant garde;" +
        "Book Antiqua=book antiqua,palatino;" +
        "Comic Sans MS=comic sans ms,sans-serif;" +
        "Courier New=courier new,courier;" +
        "Georgia=georgia,palatino;" +
        "Helvetica=helvetica;" +
        "Impact=impact,chicago;" +
        "Symbol=symbol;" +
        "Tahoma=tahoma,arial,helvetica,sans-serif;" +
        "Terminal=terminal,monaco;" +
        "Times New Roman=times new roman,times;" +
        "Trebuchet MS=trebuchet ms,geneva;" +
        "Verdana=verdana,geneva;" +
        "Webdings=webdings;" +
        "Wingdings=wingdings,zapf dingbats",
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    plugins: [
        " advlist autolink link image lists charmap save print preview hr anchor pagebreak ",
        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons paste textcolor colorpicker responsivefilemanager", "directionality", "code fontawesome noneditable"
    ],
    toolbar: "save| fontawesome",

    save_enablewhendirty: true,
    toolbar1: "  undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | ltr  rtl | blockquote | removeformat |  fontselect | fontsizeselect | styleselect | formatselect |  subscript superscript ",
    toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code   | mybutton1 | galleryButton | widgetButton  | fontawesome | Mail_tags | social-media-links",
    setup: function(editor) {

        editor.addButton('social-media-links', {
            type: 'listbox',
            text: 'Social Media',
            icon: "mce-ico mce-i-flag",
            onselect: function(e) {

                tinymce.activeEditor.execCommand('mceInsertContent', false, this.value());

            },
            values: [
                { text: 'Facebook', value: '<span style="font-size: 18pt;"><a href="http://facebook.com" title="facebook"><span style="color: #3b5998" class="fa">&#xf09a;</span></a></span>&nbsp;' },
                { text: 'Facebook-alt', value: '<span style="font-size: 18pt;"><a href="http://facebook.com"  title="facebook"><span style="color: #3b5998" class="fa">&#xf082;</span></a></span>&nbsp;' },
                { text: 'Twitter', value: '<span style="font-size: 18pt;"><a href="http://twitter.com"  title="Twitter"><span style="color: #00aced" class="fa">&#xf099;</span></a></span>&nbsp;' },
                { text: 'Twitter-alt', value: '<span style="font-size: 18pt;"><a href="http://twitter.com"  title="Twitter"><span style="color: #00aced" class="fa">&#xf081;</span></a></span>&nbsp;' },
                { text: 'Linkedin', value: '<span style="font-size: 18pt;"><a href="http://linkedin.com"  title="LinkedIn"><span style="color: #007bb6" class="fa">&#xf0e1;</span></a></span>&nbsp;' },
                { text: 'Linkedin-alt', value: '<span style="font-size: 18pt;"><a href="http://linkedin.com"  title="LinkedIn"><span style="color: #007bb6" class="fa">&#xf08c;</span></a></span>&nbsp;' },
                { text: 'Youtube', value: '<span style="font-size: 18pt;"><a href="http://youtube.com"  title="Youtube"><span style="color: #bb0000" class="fa">&#xf167;</span></a></span>&nbsp;' },
                { text: 'Youtube-alt', value: '<span style="font-size: 18pt;"><a href="http://youtube.com"  title="Youtube"><span style="color: #bb0000" class="fa">&#xf16a;</span></a></span>&nbsp;' },
                { text: 'Instagram', value: '<span style="font-size: 18pt;"><a href="https://www.instagram.com/"  title="Instagram"><span style="color: #517fa4" class="fa">&#xf16d;</span></a></span>&nbsp;' },
                { text: 'Snapchat', value: '<span style="font-size: 18pt;"><a href="https://www.snapchat.com/"  title="Snapchat"><span style="color: #fffc00" class="fa">&#xf2ac;</span></a></span>&nbsp;' },
                { text: 'Snapchat-alt', value: '<span style="font-size: 18pt;"><a href="https://www.snapchat.com/"  title="Snapchat"><span style="color: #fffc00" class="fa">&#xf2ad;</span></a></span>&nbsp;' },
            ],
            onPostRender: function() {
                this.value('Some text 2');
            }
        });
    },
    content_css: "../includes/css/bootstrap.min_ltr.css , ../includes/css/tiny.css , //netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",
    image_advtab: true,
    external_filemanager_path: "/js/includes/filemanager/",
    filemanager_title: "Voila Filemanager",
    external_plugins: {
        "responsivefilemanager": "../tinymce/plugins/responsivefilemanager/plugin.min.js",
        "filemanager": "../filemanager/plugin.min.js"
    }

});


function uploadForm() {
    showImages(1);
    $("#drop-one").slideToggle(500);
    $("#all-images").slideToggle(500, function() {
        if ($("#toggle-upload").text() == "back") {
            $("#toggle-upload").text("upload new image");
        } else {
            $("#toggle-upload").text("back");

        }
    });
}
var currentImage = null;
var form_id = null;

window.addEventListener('click', function(event) {
    var element = $(event.target);
});


function changeBGColor(ev) {
    $val = $("#color-body-temp").val();

    if ($val !== undefined && $val != "") {
        $('#image-background-div').css("background", "");
        $('#image-background-div').css({
            'background-color': $val,
        });
        $("#image-body").val("");
        $("#color-body").val($val);
    }
}

function showImagesBackground() {
    OpenInsertImageForBackground1();
}

function OpenInsertImageForBackground1() {
    $("#modalInsertPhotoForBackgroundLanding .modal-body").html(`<iframe width="100%" height="400" src="/js/includes/filemanager/dialog.php?type=2&field_id=TempImageSelectedForBackgroundLanding'&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`);
    $("#modalInsertPhotoForBackgroundLanding").modal();
}
$('#modalInsertPhotoForBackgroundLanding').on('hidden.bs.modal', function() {
    $("#TempImageSelectedForBackgroundLanding").trigger("change");
});
$("#TempImageSelectedForBackgroundLanding").change(function() {
    $val = $(this).val();

    if ($val && $val != "") {
        $('#image-background-div').css("background", "");
        $('#image-background-div').css({
            'backgroundImage': 'url(' + $val + ')',
            'background-repeat': 'no-repeat',
            'background-position': 'center center',
            'background-size': 'cover',
            "background-attachment": "fixed"

        });
        $("#image-body").val($val);
        $("#color-body").val("");
    }
    $("#TempImageSelectedForBackgroundLanding").val("");
})
var listform = [];


function saveLanding() {

    $(".element-remove").removeClass("element-remove");
    $(".element-text-remove").removeClass("element-text-remove");
    $(".p-loader").css("display", "block");
    $(".element-border").removeClass("element-border");
    $("#my-panel").hide();
    $("#color-section").hide();
    // $(".btn-dlt").css('display', 'none');
    $("#edit-form").css('display', 'none');
    var cc = $("#image-background-div").css("background");
    var titleLanding = document.getElementsByClassName("landing-title")[0].value;

    var code = $html = $('#scratchGrid').gridEditor('getHtml');
    var background_color = $("#color-body").val();
    var background_image = $("#image-body").val();

    if (background_color === null && background_color === undefined) {
        background_color = "";
    }
    if (background_image === null && background_image === undefined) {
        background_image = "";
    }
    var data = {
        code: code,
        title: titleLanding,
        background: cc,
        form_id: form_id,
        from_scratch: "1",
        background_image: background_image,
        background_color: background_color
    };
    var save_link = "/landingPage/save";
    if (for_edit === true && edit_id) {
        save_link = "/landingPage/save/" + edit_id;
    }
    $.ajax({
        type: "POST",
        url: save_link,
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: "json",
        success: function(data) {
            $("#edit-form").attr("data-target", "#myModal");
            $('.submitclass').attr("disabled", false);
            $(".p-loader").css("display", "none");
            var url = window.location.href;
            var arr = url.split("/");
            var result = "/" + arr[3] + "/edit/" + data.id;
            if (for_edit === true && edit_id) {
                result = "/" + arr[3] + "/edit/" + edit_id;
            }
            window.location.href = result;

        },
    });
}