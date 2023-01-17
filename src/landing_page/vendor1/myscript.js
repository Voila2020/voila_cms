

var counter =parseInt($("#counter-value").text()); 
var formResulte = [];
var classBtn = "";
var allNames=[];
//add new element input
function generateElement() {
    var column = $("#column").val();
    var type = $("#type").val();

    var label = $("#label").val();
    var placeholder = $("#placeholder").val();
    var className = $("#className").val();
    var requierd = ($("#required").prop("checked")) ? "required" : "";
    className = "form-control";
    if (type == "radio" || type == "checkbox") {
        className = "";
        column = "col-lg-2"

    }
    var form = {
        'id': counter + 1,
        'label': label,
        'placeholder': placeholder,
        'column': column,
        'className': className,
        'requierd': requierd,
        'type': type,
        'formHtml': ''

    };

    if (type == "textarea") {
        form.formHtml =
            ` <div class="form-group ` + form.column + `" style="margin-bottom: 15px;" id="div-` + form.id + `">
        <label for="input-text-`+ form.id + `" id="label-` + form.id + `">` + form.label + `</label>
        <textarea  id="input-` + form.id + `" rows="5" name="input`+counter+`" name="input`+counter+`"  onclick='focusInput("` + form.id + `","` + form.className + `","` + form.requierd + `")' id="` + form.id + `" placeholder="` + form.placeholder + `" class="` + form.className + `" ` + form.requierd + `></textarea>

        <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px; 
        position: absolute;" id="btn-delete-`+ form.id + `" onclick="deleteItem(` + form.id + `)">x</button>
</div> 
        
        `;
    } else {
        form.formHtml = `
        <div class="form-group `+ form.column + `" style="margin-bottom: 15px;" id="div-` + form.id + `">
                <label for="input-text-`+ form.id + `" id="label-` + form.id + `">` + form.label + `</label>
                <input type="`+ form.type + `" id="input-` + form.id + `"  name="input`+counter+`" onclick='focusInput("` + form.id + `","` + form.className + `","` + form.requierd + `")' id="` + form.id + `" placeholder="` + form.placeholder + `" class="` + form.className + `" ` + form.requierd + `>
                
                <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px;
                position: absolute;" id="btn-delete-`+ form.id + `" onclick="deleteItem(` + form.id + `)">x</button>
        </div> 
        
`;
        form.htmlResulte = `
        <div class="form-group `+ form.column + `" id="div-` + form.id + `">
                <label for="input-text-`+ counter + `">` + form.label + `</label>
                <input type="text"  onclick="focusInput(`+ form.id + `)" id="` + form.id + `" placeholder="` + form.placeholder + `" class="` + form.className + `" ` + form.requierd + `>   
        </div> 
`;
    }



    formResulte.push(form);
    console.log(formResulte);
    $("#formGo").append(form.formHtml);
    counter++;
}


//clear form
function clearForm() {
    $("#formGo").empty();
}

//delelte item 
function deleteItem(id) {

    var idToRemove = "#div-" + id;
    $(idToRemove).remove();
}

//when focus in input
function focusInput(id, className, requierd) {

    localStorage.setItem("id", id);
    var idBtnDelete = "#btn-delete-" + id;
    $(".btn-dlt").addClass("hide");
    $(idBtnDelete).removeClass("hide");

    var label = "#label-" + id;
    var column = "#div-" + id;
    var placeholder = "#input-" + id;
    var type = $(placeholder).attr('type');

    $("#label").val($(label).text());
    $("#placeholder").val($(placeholder).attr('placeholder'));

    $("#className").val(className);
    if (requierd == "required") {
        $("#required").prop('checked', true);
    } else {
        $("#required").prop('checked', false);

    }
    $("#column").val($(column).attr('class').split(' ')[1]);
    $("#type").val(type);

    $("#save-prop").removeClass("hide");
}

//close and open menu
$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

//delete all controls in form
function removeAllControl() {
    $(".btn-dlt").addClass("hide");

}

//change class width column 
function changeClass() {
    var id = localStorage.getItem("id");
    var divGroup = "#div-" + id;
    $(divGroup).removeClass();
    $(divGroup).addClass("form-group " + $("#column").val());
}


function changeType() {
    var id = localStorage.getItem("id");
    var input = "#input-" + id;
    $(input).attr('type', $("#type").val());
}

function changeLabel() {
    var id = localStorage.getItem("id");
    var label = "#label-" + id;
    $(label).text($("#label").val());
}


function changePlaceHolder() {
    var id = localStorage.getItem("id");
    var placeholder = "#input-" + id;
    $(placeholder).attr("placeholder", $("#placeholder").val());
}


function newElement() {

    localStorage.removeItem("id");
    $(".btn-dlt").addClass("hide");
    $("#column").val("col-md-12");
    $("#type").val("Text");
    $("#label").val("");
    $("#placeholder").val("");
    $("#className").val("");
    $("#required").prop('checked', true);

    $("#input-panel").removeClass("hide");
    $("#form-panel").addClass("hide");
    $("#form-button").addClass("hide");
    $("#form-select").addClass("hide");
    $("#form-image").addClass("hide");
    $("#form-text").addClass("hide");

}

function newImageForm() {

    $("#input-panel").addClass("hide");
    $("#form-panel").addClass("hide");
    $("#form-button").addClass("hide");
    $("#form-select").addClass("hide");
    $("#form-image").removeClass("hide");
    $("#form-text").addClass("hide");
    
}



function formSetting() {
    $("#input-panel").addClass("hide");
    $("#form-panel").removeClass("hide");
    $("#form-button").addClass("hide");
    $("#form-select").addClass("hide");
    $("#form-image").addClass("hide");
    $("#form-text").addClass("hide");

}

function newButton() {
    $("#input-panel").addClass("hide");
    $("#form-panel").addClass("hide");
    $("#form-button").removeClass("hide");
    $("#form-select").addClass("hide");
    $("#form-image").addClass("hide");

    $("#form-text").addClass("hide");
}

function newSelect() {
    $("#input-panel").addClass("hide");
    $("#form-panel").addClass("hide");
    $("#form-button").addClass("hide");
    $("#form-select").removeClass("hide");
    $("#form-image").addClass("hide");
    $("#form-text").addClass("hide");


}


function  newText(){
    $("#input-panel").addClass("hide");
    $("#form-panel").addClass("hide");
    $("#form-button").addClass("hide");
    $("#form-select").addClass("hide");
    $("#form-image").addClass("hide");
    $("#form-text").removeClass("hide");

    
}

function changeBackgroundColor() {


    var color = $("#back-form").val();
    $("#formGo").css("background", color);
}

function changeBorderColor() {
    var color = $("#back-border").val();
    $("#formGo").css("border", "3px solid " + color);
}


function setShadow() {
    var color = $("#shadow").val();
    $("#formGo").css("box-shadow", "4px 4px 4px " + color);
}
function changeFontColor() {
    var color = $("#font-form").val();
    $("#formGo").css("color", color);
}

function changeLang() {
    var lang = $("#lang").val();
    $("#formGo").css("direction", lang);
}

function changeFontFamily() {
    var font = $("#font-family").val();
    $("#formGo").css("font-family", font);

}

$(".clickable-class").click(function () {
    $(this).removeClass('clickable-class');
    classBtn = $(this).attr("class");
    
    $(this).addClass('.clickable-class');
    $('.clickable-class').removeAttr("disabled");
    $(this).attr("disabled", "disabled");
})

var columnButton = "col-lg-12";
function changeClassButton() {
    columnButton = $("#column-btn").val();
}

function addButton() {
    var value = $("#title-btn").val();
    counter++;
    var button = `
            
            <div id="div-`+ counter + `" class="form-group ` + columnButton + `" style="margin-top: 13px;">
            <input class="`+ classBtn + ` submitclass"id="input-` + counter + `"   onclick='clickOnButton("` + counter + `")'  type="submit" value="` + value + `">
            
            <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px;
            position: absolute;" id="btn-delete-`+ counter + `" onclick="deletebutton(` + counter + `)">x</button>
            </div>
            `
        ;
    $("#formGo").append(button);

}

function clickOnButton(idBtn) {
    var id = "#btn-delete-" + idBtn;
    $(".btn-dlt").addClass("hide");
    $(id).removeClass("hide");
}


function deletebutton(id) {
    var idBtn = "#div-" + id;
    $(".btn-dlt").addClass("hide");

    $(idBtn).remove();
}


function addSelect() {
    var lines = [];
    $.each($('#options').val().split(/\n/), function (i, line) {
        if (line) {
            lines.push(line);
        }
    });
    counter++;
    var label = $("#label-select").val();
    var columnButton = $("#column-select").val();
    options = `<div id="div-` + counter + `"  
    class="form-group `+ columnButton + `">
    <label for="input-text-`+ counter + `" id="label-` + counter + `">` + label + `</label>
    
    <select class="form-control" name="input`+counter+`" id="input-` + counter + `" onclick=clickSelect(` + counter + `)>`
    lines.forEach(element => {
        options += `<option value=` + element + `>` + element + `</option>`
    });
    options += `</select>
    <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px;
    position: absolute;" id="btn-delete-`+ counter + `" onclick="deletebutton(` + counter + `)">x</button>

    </div>`;

    console.log(options);

    $("#formGo").append(options);

}

function clickSelect(idBtn) {
    var id = "#btn-delete-" + idBtn;
    $(".btn-dlt").addClass("hide");
    $(id).removeClass("hide");

}




function changeColumnForm() {
    column = $("#column-form").val();

    $("#formGo").removeClass();
    $("#formGo").addClass("well " + column);

}


function changeBackGroundHeadar() {

    var color = $("#bg-headar").val();
    $("#headar").css("background", color);
}


function changeFontColorHeadar() {
    var color = $("#color-headar").val();
    $("#headar").css("color", color);
}


function changeTextHeadar() {
    var text = $("#title-headar").val();
    $("#headar").text(text);
}

function changePositionHeadar() {
    var pos = $("#position-header").val();
    $("#headar").css("text-align", pos);

}


function newImage() {
    debugger
    var path = $("#path").val();
    var colImage = $("#column-image").val();
    var height = $("#height-image").val();
    var width = $("#width-image").val();
    var pos = $("#position-image").val();
    counter++;

    if (path == "") return;
    var newImage = `
    <div id="div-` + counter + `"  
    class="form-group `+ colImage + `" style="padding-top: 11px;">
    <img class="img-responsive"  align="`+pos+`"  valign="`+pos+`" id="input-` + counter + `" src="` + path + `" height="` + height + `px" width="` + width + `px" onclick=clickSelectImage(` + counter + `)>
    </div>
    <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px;
    position: absolute;" id="btn-delete-`+ counter + `" onclick="deletebutton(` + counter + `)">x</button>

    `;
    $("#formGo").append(newImage);
    $("#path").val("");
    $("#column-image").val("");
    $("#height-image").val("");
    $("#width-image").val("");

}


function addText() {
    
    var typeText = $("#path").val();
    var colText = $("#column-text").val();
    var pos = $("#position-text").val();
    var paragraph = $("#p-text").val();
    var size=$("#type-text").val();
    var color=$("#p-color").val();
    counter++;

    var newImage = `
    <div id="div-` + counter + `"  
    class="form-group `+ colText + `" style="padding-top: 11px;">
    <p id="input-` + counter + `" style="text-align:`+pos+`;font-size:`+size+`px;color:`+color+`" onclick=clickSelectImage(` + counter + `)>
    `+paragraph+`
    </p>
    <button class="btn-dlt  btn-sm btn-danger hide" style="bottom: 0px;
    position: absolute;" id="btn-delete-`+ counter + `" onclick="deletebutton(` + counter + `)">x</button>
    `;
    $("#formGo").append(newImage);
    $("#path").val("");
    $("#column-image").val("");
    $("#height-image").val("");
    $("#width-image").val("");

}

function clickSelectImage(idBtn) {
    var id = "#btn-delete-" + idBtn;
    $(".btn-dlt").addClass("hide");
    $(id).removeClass("hide");
    
}

var titleForm="some title";
function changeTitleform(){
    titleForm=$("#title-form").val();
}

var messageForm="thank you for submit";
function changeMessageForm (){
    messageForm=$("#message-form").val();
    $("#message").text(messageForm)
}

$(document).ready(function(){
    messageForm=$("#message-form").val();
    $("#message").text(messageForm);
    titleForm=$("#title-form").val();

    

})



