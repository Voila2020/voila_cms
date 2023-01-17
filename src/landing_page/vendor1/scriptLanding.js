//const var
var thisText = null;
var thisImage = null;
var thislink = null;
var myColorElmnt = "#my-color-text";
var myBgColor = "#my-link-bg";
var myTextElmnt = ".note-editable.panel-body";
var panelElmnt = "#my-panel";
var mySizeElmnt = "#my-size-text";
var myLineHeightElmnt = "#my-line-text"

var closePanel = "#close-panel";
var panelText = ".text-panel";
var panelImage = ".image-panel";

var heightImage = "#height-image";
var widthImage = "#width-image";
var pathImage = "#path";
var bImage = "";
var all = null;
//End const var

$(closePanel).click(function () {
    $(panelElmnt).addClass("hide");
    $("p, h1, h2, h3, h5,.ul").removeClass("element-border");

})
$("p, h1, h2, h3, h5,.ul").click(function (event) {
    if ($("#sidebar").is($(".active"))) {
        $("#sidebar").toggleClass("active");
        $("#my-panel").toggle();
    }
    $("#my-panel").css("top", "30%");
    thisText = $(event.target)[0];
    console.log(thisText);
    var text = $(this).html();
    $("p, h1, h2, h3, h5,.ul").removeClass("element-border");
    $("img").removeClass("element-border")
    $("section,.navbar-inverse").removeClass("element-border");
    $("a.a-link").removeClass("element-border");
    $("#my-link-text,#my-link-bg").css("display", "none");
    $(".link").css("display", "none");

    $(this).addClass("element-border")
    $(myTextElmnt).html(text);
    $(panelElmnt).removeClass("hide");
    $(panelText).removeClass("hide");

    $(panelImage).addClass("hide");
    $(myColorElmnt).val("#ffffff");

    $(mySizeElmnt).val(parseInt($(thisText).css('font-size')));
    $(myLineHeightElmnt).val(parseInt($(thisText).css('line-height')));
});

$("section,.navbar-inverse").click(function () {
    $("#slide-sidebar").prop("checked", false);
    $("section,.navbar-inverse").removeClass("element-border");
    $("section,.navbar-inverse").children("#color-section").remove();

    $(this).addClass("element-border");
    $(this).prepend("<input class='form-controle' type='color' style='position: absolute;left: 0;top: 0;' id='color-section' onchange='changeBG(this)'>");
    $("#color-section").val("#ffffff");
});

$("a.a-link").click(function (event) {
    if ($("#sidebar").is($(".active"))) {
        $("#sidebar").toggleClass("active");
        $("#my-panel").toggle();
    }
    $("#my-panel").css("top", "10%");
    thislink = $(event.target)[0];
    // $(thislink).css("pointer-events","none");
    var text = $(this).text();
    $("section,.navbar-inverse").removeClass("element-border");
    $("a").removeClass("element-border");
    $("#my-link-text,#my-link-bg").css("display", "block");
    $(".link").css("display", "block");
    $("p, h1, h2, h3, h5,.ul").removeClass("element-border");
    $(this).addClass("element-border");
    $(panelElmnt).removeClass("hide");
    $(panelText).removeClass("hide");
    $(myTextElmnt).html(text);
    $(panelImage).addClass("hide");
    $(myColorElmnt).val("#ffffff");
    $(myBgColor).val($(thislink).css("background"));
    $(mySizeElmnt).val(parseInt($(thisText).css('font-size')));
    $(myLineHeightElmnt).val(parseInt($(thisText).css('line-height')));
    $("#my-link-text").val($(thislink).attr("href"));
});

$("img").click(function () {
    if ($("#sidebar").is($(".active"))) {
        $("#sidebar").toggleClass("active");
        $("#my-panel").toggle();
    }
    $("#my-panel").css("top", "30%");
    thisImage = this;
    var text = $(this).text();
    $("img").removeClass("element-border");
    $("a.a-link").removeClass("element-border");
    $("#my-link-text,#my-link-bg").css("display", "none");
    $(".link").css("display", "none");
    $("p, h1, h2, h3, h5,.ul").removeClass("element-border");
    $("section,.navbar-inverse").removeClass("element-border");

    $(this).addClass("element-border")
    $(myTextElmnt).val(text);
    $(panelElmnt).removeClass("hide");
    $(panelImage).removeClass("hide");
    $(panelText).addClass("hide");
    $(myColorElmnt).val("");
    $(mySizeElmnt).val(parseInt($(thisText).css('font-size')));
    $(myLineHeightElmnt).val(parseInt($(thisText).css('line-height')));

});

$(document).scroll(function () {
    if ($(window).scrollTop() >= 74) {
        $(".main-wrap .sidebar").css({ "top": "0" });
    } else if ($(window).scrollTop() <= 0) {
        $(".main-wrap .sidebar").css("top", "74px");
    }
});

$(document).ready(function () {
    
    var x = document.querySelectorAll('.ge-widget-section a');
    var y = document.querySelectorAll('.ge-content-type-tinymce');

    for (var i = 0; i < x.length; i++) {
        dragula([x[i], y], {
            copy: true,
            copySortSource: true,
            // accepts: function (el, target, source) {
            //     if (target === y)
            //         return true;
            //         else {return false}
            // }
        });
        // .on('drop',function(){
        //     if($(".gm-content").attr("contenteditable") == "true"){
        //         console.log("Byeye");
        //     }
        //     document.getElementsByClassName('bs-container')[0].focus();
        //     console.log(el);
        //     showImages();
        //     console.log("y val is",y);
        // });
    }

    $('#summernote').summernote({
        callbacks: {
            onChange: function (contents, $editable) {
                    if(thisText!=null)
                    thisText.innerHTML = $('#summernote').summernote("code");

                    if(thislink!=null)
                    thislink.innerHTML = $('#summernote').summernote("code");
            }
        },
        tabsize: 2,
        height: 100
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(".container").css("width: auto");
        $("#my-panel").toggle();
    });

});

function showPanel() {
    $(panelElmnt).toggleClass("hide");
    $("p, h1, h2, h3, h4, h5,.ul").removeClass("element-border");
}

// function changeText() {
//     debugger
//     if ($(thisText).hasClass("element-border")) {

//         $(thisText).text($(myTextElmnt).text());
//     }
//     else if ($(thislink).hasClass("element-border")) {
//         $(thislink).text($(myTextElmnt).val());
//     }
// }

function changeColorText() {
    if ($(thisText).hasClass("element-border")) {
        $(thisText).css("color", $(myColorElmnt).val());
    }
    else if ($(thislink).hasClass("element-border")) {
        $(thislink).css("color", $(myColorElmnt).val());
    }
}

function changeSizeText() {
    if ($(mySizeElmnt).val() > 72) {
        console.log("max val is" + $(mySizeElmnt).val());
        return false;
    }
    console.log($(event));
    if ($(thisText).hasClass("element-border")) {
        if ($(mySizeElmnt).val() != 0) {
            $(thisText).css("font-size", $(mySizeElmnt).val() + "px")
        } else {
            $(thisText).css("font-size", "initial");
        }
    }
    else if ($(thislink).hasClass("element-border")) {
        if ($(mySizeElmnt).val() != 0) {
            $(thislink).css("font-size", $(mySizeElmnt).val() + "px")
        } else {
            $(thislink).css("font-size", "initial");
        }
    }
}

function changeLink() {
    // var result = confirm("Want to delete?");
    // if (result) {

    // }
    $(thislink).attr("href", $("#my-link-text").val());
    $(thislink).attr("target", "_blank");
}


function changeLineHeightText() {
    if ($(myLineHeightElmnt).val() > 150) {
        console.log("max val is" + $(myLineHeightElmnt).val());
        return false;
    }
    if ($(thisText).hasClass("element-border")) {
        if ($(myLineHeightElmnt).val() != 0) {
            $(thisText).css("line-height", $(myLineHeightElmnt).val() + "px")
        } else {
            $(thisText).css("line-height", "initial");
        }
    }
    else if ($(thislink).hasClass("element-border")) {
        if ($(myLineHeightElmnt).val() != 0) {
            $(thislink).css("line-height", $(myLineHeightElmnt).val() + "px")
        } else {
            $(thislink).css("line-height", "initial");
        }
    }
}

function remove() {
    if ($(thisText).hasClass("element-border")) {
        $(thisText).empty();
        $(".note-editable").empty();
        $(thisText).addClass("element-remove");
        $(thisText).addClass("element-text-remove");
    }
    else if ($(thislink).hasClass("element-border")) {
        $(thislink).text($(myLineHeightElmnt).val());
        $(thislink).empty();
        $(".note-editable").empty();
        $(thislink).addClass("element-remove");
        $(thislink).addClass("element-text-remove");
    }
}

function changeHeightImage() {
    if ($(heightImage).val() > 700) {
        console.log("max val is" + $(heightImage).val());
        return false;
    }
    if ($(heightImage).val() != 0) {
        $(thisImage).css("height", $(heightImage).val() + "px");
    } else {
        $(thisImage).css("height", "initial");
    }
}
function changeWidthImage() {
    if ($(widthImage).val() > 700) {
        console.log("max val is" + $(widthImage).val());
        return false;
    }
    if ($(widthImage).val() != 0) {
        $(thisImage).css("width", $(widthImage).val() + "px");
    } else {
        $(thisImage).css("width", "initial");
    }
}

function changeImage() {
    var src = $(pathImage).val();
    $(thisImage).attr("src", src);
}

function removeImage() {
    $(thisImage).attr('src', 'https://placehold.it/150x80?text=IMAGE');
    $(thisImage).addClass("element-remove");

}

function changeBG(ele) {
    console.log(ele);
    if ($(ele).is("#color-body")) {
        $('body').css('background', $("#color-body").val());
        bColor = $('body').css('background-color');
    }
    else if ($(ele).is("#color-section") && $("section,.navbar-inverse").hasClass("element-border")) {
        var dd = $("#color-section").parent().css('background', $(ele).val());
        console.log($(dd).css("background-color"));
        $(ele).val($(dd).css("background-color"));
        // var dd = $(".element-border").css('background', $(ele).val());
        // bColor = $("section").css('background');
    }
    else if ($(ele).is("#my-link-bg") && $("a.a-link").hasClass("element-border")) {
        var dd = $(".a-link.element-border").css('background', $(ele).val());
        // $(ele).val($(thislink).css("background"));
        // ele.value = $(thislink).css("background");
        console.log($(this));
        console.log(dd);
        // bColor = $("section").css('background');
    }
}
