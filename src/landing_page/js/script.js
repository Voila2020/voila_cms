var x = document.querySelectorAll('.control-panel.cp-1');
var y = document.querySelector('#formGo');
var z = document.getElementById('2a');
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
}

var current_label = null;
var current_input = null;
var current_chkbox = null;
var current_formGroup = null;
var chk_box = document.getElementById("required");

function topFunction() {
    $(".scrollbar").scrollTop(0);
}

function changeBackgroundColor(element) {
    console.log(element);
    var color = $(element).val();
    if ($(element).is('.form-color')) {
        console.log('form color');
        $("#formGo").css("background", color);
    } else {
        console.log('input color');
        $(current_input).css("background-color", color);
        $(current_btn).css("background-color", color);
    }

}

function changeFontColor(element) {
    if ($(element).is('.form-font-color')) {
        console.log('form color');
        var color = $(element).val();
        $("#formGo").css("color", color);
        // console.log(document.getElementById("formGo").style.background = color);
    } else {
        console.log('input color');
        var color = $(element).val();
        $(current_input).css("color", color);
        $(current_btn).css("color", color);

    }
}

function changeBorderSize(element) {
    var border_size = $(element).val();
    if (border_size > 99) {
        console.log("max val is" + border_size);
        return false;
    }
    if ($(element).is('.form-border-size')) {
        if (border_size >= 1) $("#formGo").css("border-width", border_size + 'px');
        else { $("#formGo").css("border-width", "1px"); }
    } else {
        $(element).text($(current_input).css("border"));
        $(current_input).css("border-width", "");
        $(current_input).css("border-width", border_size + 'px');
        $(current_btn).css("border-width", border_size + 'px');
    }
}

function changeBorderColor(element) {
    if ($(element).is('.form-border-color')) {
        console.log('form color');
        var color = $(element).val();
        $("#formGo").css("border-color", color);
        // console.log(document.getElementById("formGo").style.background = color);
    } else {
        console.log('input color');
        var color = $(element).val();
        $(current_input).css("border-color", color);
        $(current_btn).css("border-color", color);
    }
}

function changeWidth(element) {
    var width = $(element).val();
    if ($(element).is('.form-width')) {
        $("#formGo").css("width", width + '%');
    }
    else {
        if ($(current_input).is("input[type='checkbox']") || $(current_input).is("input[type='radio']")) {
            $(current_formGroup).removeClass();
            $(current_formGroup).addClass("form-group chk " + width);
        }
        else {
            $(current_formGroup).removeClass();
            $(current_formGroup).addClass("form-group " + width);
        }
    }
}


function changeWidthForm() {

    var class_col = $("#column-form").val();
    $("#form-id").removeClass();
    $("#form-id").addClass(class_col);

}

function changeTextSize(element) {
    var txt_size = $(element).val();
    if (txt_size > 99) {
        console.log("max val is" + txt_size);
        return false;
    }
    if (txt_size != 0) {
        $(current_label).css("font-size", txt_size + 'px');
        $(current_btn).css("font-size", txt_size + 'px');
    } else {
        $(current_btn).css("font-size", 'initial');
        $(current_label).css("font-size", "initial");
    }
}

function changeBorderRadius(element) {
    var border_radius = $(element).val();
    if (border_radius > 99) {
        console.log("max val is" + border_radius);
        return false;
    }
    if ($(element).is(".form-radius")) {
        if (border_radius > 0) $("#formGo").css("border-radius", border_radius + 'px');
        else { $("#formGo").css("border-radius", ""); }
    }
    else {
        $(current_input).css("border-radius", "");
        $(current_input).css("border-radius", border_radius + 'px');
        $(current_btn).css("border-radius", border_radius + 'px');
    }
}

function changePadding(element) {
    var padding = $(element).val();
    if (padding > 99) {
        console.log("max val is" + padding);
        return false;
    }
    $(current_input).css("padding", padding + 'px');
    $(current_btn).css("padding", padding + 'px');
}

function requiredField() {
    if (chk_box.checked) {
        current_input.attr("required", "true");
        // $("<span id='required-star'>*</span>").insertAfter(current_label);
        $(current_label).append("<span id='required-star'>*</span>");
    }
    else {
        current_input.removeAttr("required");
        $(current_label).children("span").remove();
    }
}

function centerField() {
    var cenetr_element = document.getElementById("center-ele");
    if (cenetr_element.checked) {
        $(current_formGroup).addClass("center-element");
    }
    else {
        $(current_formGroup).removeClass("center-element");
    }
}

function changeFontFamily() {
    var font = $("#font-family").val();
    $("#formGo").css("font-family", font);

}

// function rangeSliderFunction() {

// }
// var elementChangeCss=null;
// $("#form-id  *").on("click",function(){
//     elementChangeCss=$(this);
// debugger
// });

// $("#custom-css").on("change",function(){
//     var x = document.getElementById("custom-css").value;
//     elementChangeCss.attr("style", x);

// })
window.addEventListener('click', function (event) {
    
    var rgbToHex = function (rgb) {
        var hex = Number(rgb).toString(16);
        if (hex.length < 2) {
            hex = "0" + hex;
        }
        return hex;
    };
    var fullColorHex = function (r, g, b) {
        var red = rgbToHex(r);
        var green = rgbToHex(g);
        var blue = rgbToHex(b);
        var f= "#"+ red + green + blue;
        console.log(f);
        return f;
    };

    if ($(event.target).is('#form-id .form-group')) {
        topFunction();  
        current_formGroup = $(event.target);
        current_form = $(event.target).parent();
        console.log("this input", current_form);
        current_label = $(event.target).children("label");
        current_input = $(event.target).children(".form-control");
        current_btn = $(event.target).children(".btn");
        current_span = $(event.target).children("span");
        var o_selected = $(".col-w").find('option:selected').text();
        document.getElementsByClassName("col-w")[0].value = current_formGroup[0].classList[1];
        console.log(current_formGroup[0].classList[1]);

        var form_group = document.getElementsByClassName("form-group");
        for (var i = 0; i <= form_group.length; i++) {
            $("a[href='#3a']").parent().addClass('active');
            $('#3a').addClass("active");
            $("a[href='#2a']").parent().removeClass('active');
            $('#2a').removeClass("active");
            $(event.target).addClass("border-form-group");
            $("<i class='fa fa-remove' id='remove-icon' style='cursor: pointer;'></i>").insertAfter(current_label);
            var old_label = $(event.target).children("label").text().trim();
            $("#label").val(old_label);
            var old_placeholder = $(event.target).children("input").attr('placeholder');
            $("#placeholder").val(old_placeholder);
            var old_btn_text = $(current_btn).attr('value');
            $("#placeholder").val(old_btn_text);


            if (form_group[i] !== $(event.target)) {
                $(form_group[i]).removeClass("border-form-group");
                $(form_group[i]).children("i").remove();
            }
        }
        if (current_label.is(".header")) {
            console.log("eh header");
            $(".form-check-center").css("display", "block");
            $(".form-check-req").css("display", "none");

        } else {
            $(".form-check-center").css("display", "none");
            console.log("no header");
            $(".form-check-req").css("display", "block");
        }
        if (current_input.attr("required")) {
            console.log("eh reqqq");
            document.getElementById("required").checked = true;
        } else { document.getElementById("required").checked = false; }
        if (current_input.css("border-raduis")) {
            document.querySelector(".b-raduis").value = parseInt($(current_input).css("border-radius"));
        } else {
            document.querySelector(".b-raduis").value = '';
        }
        if (current_input.css("border")) {
            $(".b-size").val(parseInt($(current_input).css("border")));
            console.log(parseInt(parseInt($(".b-size").val())));
        } else {
            document.querySelector(".b-size").value = '';
        }
        if (current_label.css("font-size")) {
            document.querySelector(".font-size").value = parseInt($(current_label).css("font-size"));
        } else {
            document.querySelector(".font-size").value = '';
        }
        if (current_input.css("padding")) {
            document.querySelector(".pad-size").value = parseInt($(current_input).css("padding"));
        } else if (current_btn.css("padding")) {
            document.querySelector(".pad-size").value = parseInt($(current_btn).css("padding"));
        } else {
            document.querySelector(".pad-size").value = '';
        }
        if ($(current_input).css("background-color")) {
            var rgb = $(current_input).css("background-color");
            rgb = rgb.substring(4, rgb.length - 1)
                .replace(/ /g, '')
                .split(',');
            var r = rgb[0];
            var g = rgb[1];
            var b = rgb[2];

            $(".field-color").val(fullColorHex(r, g, b));
        } else {
            document.querySelector(".field-color").value = '#ffffff';
        }

        if ($(current_input).css("color")) {
            var rgb = $(current_input).css("color");
            rgb = rgb.substring(4, rgb.length - 1)
                .replace(/ /g, '')
                .split(',');
            var r = rgb[0];
            var g = rgb[1];
            var b = rgb[2];

            $(".field-font-color").val(fullColorHex(r, g, b));
        } else {
            document.querySelector(".field-font-color").value = '';
        }

        if ($(current_input).css("border-color")) {
            var rgb = $(current_input).css("border-color");
            rgb = rgb.substring(4, rgb.length - 1)
                .replace(/ /g, '')
                .split(',');
            var r = rgb[0];
            var g = rgb[1];
            var b = rgb[2];
            // $(".field-border-color").val($(current_input).css("border-color"));
            $(".field-border-color").val(fullColorHex(r, g, b));

        } else {
            document.querySelector(".field-border-color").value = "#CACACA";
        }
        if (current_input.is('.select1')) {
            $(".generate").css("display", "block");
            $(".txt-area1").val("");
            var text_area = "";

            $(current_input).find("option").each(function (e) {
                text_area += $(this).val() + "\n";

            });
            $(".txt-area1").val(text_area);


            $(".txt-default").css("display", "none");
            $(".txt-default").siblings("label").css("display", "inline-block");
            $(".txt-area2").css("display", "none");
            $("#options1").css("display", "inline-block");
            $(".txt-header").css("display", "inline-block");
            $(".txt-header").siblings("label").css("display", "inline-block");
            // $("#options1").keyup(function (event) {
            //     if ($.trim($(this).val())) {
            //         // textarea is empty or contains only white-space
            //         if (event.keyCode === 13) {
            //             var lines = [];
            //             $.each($('#options1').val().split(/\n/), function (i, line) {
            //                 if (line) {
            //                     lines.push(line);
            //                 }
            //             });
            //             // console.log(lines);
            //             lines.forEach(function (element) {
            //                 console.log(options1);
            //                 console.log(element);
            //                 // if(element !== current_input.children("option").val()){
            //                 options1 = '';
            //                 options1 += "<option value=" + element + ">" + element + "</option>"
            //                 // }
            //             });
            //             // console.log(options1);
            //             $(current_input).append(options1);
            //         }
            //     }
            //     else {
            //         $(current_input).find('option').remove();
            //     }
            // });
            //     }
            // }
        }
        else if (current_input.is(":not('select')")) {
            $(".txt-default").css("display", "inline-block");
            $(".txt-area1").css("display", "none");
            $(".txt-area2").css("display", "none");
            $(".generate").css("display", "none");
            $(".txt-default").siblings("label").css("display", "inline-block");
            $(".txt-header").css("display", "inline-block");
            $(".txt-header").siblings("label").css("display", "inline-block");
        }
        if (current_input.is(".slider-input")) {
            $(".stylebar-section.ff .form-group:not(.form-group-column)").css("display","none");
            $(".txt-default").css("display", "none");
            $(".txt-default").siblings("label").css("display", "none");
        }
        else if (current_input.is(":not(.slider-input)")) {
            $(".stylebar-section.ff .form-group:not(.form-group-column)").css("display","block");
            // $(".txt-default").css("display", "inline-block");
            // $(".txt-default").siblings("label").css("display", "inline-block");
        }
        else if (current_input.is("input[type=checkbox]") || current_input.is("input[type=radio]")) {
            $(".txt-header").css("display", "inline-block");
            $(".txt-header").siblings("label").css("display", "inline-block");
            $(".txt-area1").css("display", "none");
            $(".generate").css("display", "none");
            $(".txt-default").css("display", "none");
            $(".txt-default").siblings("label").css("display", "none");
        }
        else if (current_input.length == 0) {
            if (current_input.prevObject[0].lastElementChild.value == "Submit") {
                $(".txt-default").css("display", "inline-block");
                $(".txt-area1").css("display", "none");
                $(".txt-header").css("display", "none");
                $(".txt-header").siblings("label").css("display", "none");
                $(".generate").css("display", "none");
                $(".txt-default").siblings("label").css("display", "inline-block");
            }
            else {
                $(".txt-header").css("display", "inline-block");
                $(".txt-header").siblings("label").css("display", "inline-block");
                $(".txt-area1").css("display", "none");
                $(".generate").css("display", "none");
                $(".txt-default").css("display", "none");
                $(".txt-default").siblings("label").css("display", "none");

            }
        }

    }
    else if ($(event.target).is("input[type=range]")) {
        var outerH = current_input[0];
        console.log(outerH);
        outerH.oninput = function () {
            // console.log(output.innerHTML = this.value);
            current_span.text("value:" + this.value);
        }
    }
    // else if ($(event.target).is(":not(input[type=range])")) {
    //     $(".stylebar-section.ff .form-group:not(.form-group-column)").css("display","inline-block");
    // }
    else if ($(event.target).is('.fa.fa-remove')) {
        $(current_formGroup).remove();
    }
    else if ($(event.target).is('.slider.round')) {
        if (document.getElementsByClassName("ac-branding")[0].style.display == "none"){
            console.log("noooooone");
            $(" #formGo div:last-of-type").css("margin-bottom","40px");
         }else{
            $(" #formGo div:last-of-type").css("margin-bottom","0px");
         }
        console.log("brand");
        $(".ac-branding").toggle('show');

       
    }

    else if ($(event.target).is('#go-to-panel')) {
        console.log("go panel");
        $(".op-form").hide();
        $('#modal-type-form').hide();
    }

    else if ($(event.target).is('.popup')) {
        $(".op").hide();
        $("#formGo .form-group").empty();
        // $("#formGo .form-group").remove();
        // $("#form-id").hide();
    }
});



$('.generate').click(function () {
    var options = $('.txt-area1').val().split('\n').map(function (value) {
        return '<option value=' + value + '>' + value + '</option>';
    });
    $(current_input).html(options.join(''))
});

$(document).ready(function () {
    // $(".tab-panel").click(function(){
    //     if($("#4a").is('.active')){
    //         console.log("var z = document.getElementById('2a')");
    //             }
    // });

    $("#modal-type-form").css("display", "block");
    $(".op-form").show();
    $(".response-msg").text($("#message span").html().trim());
    $(".response-msg").css("color", "#ddd");
    $(".response-msg").keyup(function () {
        document.getElementById("message").innerHTML = $(".response-msg").val();
    });

    $("#label,#placeholder").keyup(function () {
        if (current_label != null)
            current_label.text($("#label").val());
        if (current_input != null)
            current_input.attr("placeholder", $("#placeholder").val());
        if (current_input.is("input[type='checkbox']") || current_input.is("input[type='radio']"))
            current_input.val($("#label").val());
        if (current_btn != null)
            current_btn.prop('value', $("#placeholder").val());
        if (chk_box.checked) {
            $(current_label).append("<span id='required-star'>*</span>");
        }
    });

    $("#formGo").click(function () {
        $("a[href='#3a']").parent().addClass('active');
        $('#3a').addClass("active");
        $("a[href='#2a']").parent().removeClass('active');
        $('#2a').removeClass("active");
    });

    $(".fa.fa-remove").click(function () {
        $(current_formGroup).remove();
    });

    // $("#custom-css").keyup(function () {
    // var x = document.getElementById("custom-css").value;
    //     if (th.is(current_label)) {
    //         console.log("label",th);
    //         current_label.attr("style", x);
    //     }
    //     else if (th.is(current_input)) {
    //         console.log("imput",th);
    //         current_input.attr("style", x);
    //     }
    // });

    // $(".position-form input").change(function () {
    //     var form_type = document.getElementsByName("radio");
    //     var body_op = document.querySelector("body");
    //     if ($(this).is(form_type[0])) {
    //         console.log("left");
    //         $(".op").hide();
    //         $(".popup").hide();
    //         // $("#form-id").css({ "float": "left" });
    //     }
    //     else if ($(this).is(form_type[1])) {
    //         console.log("right");
    //         // $("#form-id").css({ "float": "right" });
    //         $(".op").hide();
    //         $(".popup").hide();

    //     }
    //     else if ($(this).is(form_type[2])) {
    //         // console.log("float");
    //         // $("#form-id").css({"float": "none"});
    //         // // $("#form-id").css({ "position": "fixed", "left": "50%", "transform": "translateX(-50%)", "float": "none" });
    //         // if ($(':not(#form-id:has(.popup))')) {
    //         //     $('#formGo').prepend("<span class='close popup'>&times;</span>");
    //         // }
    //         // $("body").prepend("<div class='op' id='op'></div>");
    //         // $(".op").show();
    //     }
    // });
});

$('#select-mail option').mousedown(function (e) {
    e.preventDefault();
    $(this).prop('selected', !$(this).prop('selected'));
    $(this).css({ "background": "#3298FD;!important" });
    var selection = $('#select-mail').val();
    console.log(selection);
    return false;
});