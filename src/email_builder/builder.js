
editor = grapesjs.init({
    selectorManager: { componentFirst: true },
    clearOnRender: true,
    height: '100%',
    storageManager: {
        type: "remote",
        stepsBeforeSave: 20,
        options: {
            remote: {
                headers: {
                    "Content-Type": "application/json",
                },

                urlLoad:
                    $_SITE +
                    "/admin/email_templates/email-builder-content/" +
                    $id +
                    "?lang=" +
                    $lang,
                urlStore:
                    $_SITE +
                    "/admin/email_templates/save-template/" +
                    $id +
                    "?lang=" +
                    $lang,

                onStore: (data) => ({
                    lang: $lang,
                    _token: $_token,
                    id: $id,
                    content: editor.runCommand('gjs-get-inlined-html'),
                    html: editor.getHtml(),
                    css: editor.getCss(),
                    components: editor.getComponents(),
                }),
                onLoad: (result) => {
                    return result.data;
                },
            },
        },
    },
    assetManager: {
        custom: {
            open(props) {
                let imageId = props.options.target.ccid;
                let iframeUrl = `${$_SITE}/filemanager-dialog?type=1&multiple=0&crossdomain=0&popup=0&field_id=${imageId}`;
                let fancybox = $.fancybox.open({
                    width: 900,
                    height: 600,
                    type: "iframe",
                    src: iframeUrl,
                    autoScale: false,
                    autoDimensions: false,
                    fitToView: false,
                    autoSize: false,
                    afterClose: function () {
                        editor.stopCommand("open-assets");
                    },
                });
            },
            close(props) {
                editor.stopCommand("open-assets");
            },
        },
    },
    container: "#email_gjs",
    fromElement: true,
    plugins: ['grapesjs-preset-newsletter', 'grapesjs-plugin-ckeditor'],
    pluginsOpts: {
      'grapesjs-preset-newsletter': {
        modalLabelImport: 'Paste all your code here below and click import',
        modalLabelExport: 'Copy the code and use it wherever you want',
        codeViewerTheme: 'material',
        importPlaceholder: '<table class="table"><tr><td class="cell">Hello world!</td></tr></table>',
        cellStyle: {
          'font-size': '12px',
          'font-weight': 300,
          'vertical-align': 'top',
          color: 'rgb(111, 119, 125)',
          margin: 0,
          padding: 0,
        }
      },
      'grapesjs-plugin-ckeditor': {
        options: {
          startupFocus: true,
          extraAllowedContent: '*(*);*{*}', // Allows any class and any inline style
          allowedContent: true, // Disable auto-formatting, class removing, etc.
          enterMode: 2, // CKEDITOR.ENTER_BR,
          extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
          toolbar: [
            { name: 'styles', items: ['Font', 'FontSize' ] },
            ['Bold', 'Italic', 'Underline', 'Strike'],
            {name: 'paragraph', items : [ 'NumberedList', 'BulletedList']},
            {name: 'links', items: ['Link', 'Unlink']},
            {name: 'colors', items: [ 'TextColor', 'BGColor' ]},
          ],
        }
      }
    }
  });

//save button
editor.Panels.addButton("options", [
    {
        id: "save",
        className: "fa fa-floppy-o icon-blank fa-3x",
        command: function (editor1, sender) {
            var rr = editor.store();
        },
        attributes: {
            title: "Save Landing Page",
        },
    },
]);

//remove the import button.
editor.Panels.removeButton("options", "gjs-open-import-template");

var pfx = editor.getConfig().stylePrefix;
var modal = editor.Modal;
var cmdm = editor.Commands;
var codeViewer = editor.CodeManager.getViewer("CodeMirror").clone();
var pnm = editor.Panels;
var container = document.createElement("div");
var btnEdit = document.createElement("button");

//set the window that appears when we pree the import button
codeViewer.set({
    codeName: "htmlmixed",
    readOnly: 0,
    theme: "hopscotch",
    autoBeautify: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    lineWrapping: true,
    styleActiveLine: true,
    smartIndent: true,
    indentWithTabs: true,
});

btnEdit.innerHTML = "Import";
btnEdit.className = pfx + "btn-prim " + pfx + "btn-import";
btnEdit.onclick = function () {
    var code = codeViewer.editor.getValue();
    editor.DomComponents.getWrapper().set("content", "");
    editor.setComponents(code.trim());
    modal.close();
};

//Set the html code in the prev window
cmdm.add("html-edit", {
    run: function (editor, sender) {
        sender && sender.set("active", 0);
        var viewer = codeViewer.editor;
        modal.setTitle("Edit code");
        var InnerHtml = editor.runCommand('gjs-get-inlined-html');
        if (!viewer) {
            var txtarea = document.createElement("textarea");
            container.appendChild(txtarea);
            container.appendChild(btnEdit);
            codeViewer.init(txtarea);
            viewer = codeViewer.editor;
        }
        codeViewer.editor.setValue(InnerHtml);
        modal.setContent("");
        modal.setContent(container);
        modal.open();
        viewer.refresh();
    },
});

//Put an import code button
pnm.addButton("options", [
    {
        id: "import",
        className: "fa fa-download",
        command: "html-edit",
        attributes: {
            title: "Import",
        },
    },
]);

// Let's add in this demo the possibility to test our newsletters
var pnm = editor.Panels;
var cmdm = editor.Commands;
var md = editor.Modal;

// Beautify tooltips
[
    ["sw-visibility", "Show Borders"],
    ["preview", "Preview"],
    ["fullscreen", "Fullscreen"],
    ["export-template", "Export"],
    ["undo", "Undo"],
    ["redo", "Redo"],
    ["gjs-toggle-images", "Toggle images"],
    ["canvas-clear", "Clear canvas"],
].forEach(function (item) {
    pnm.getButton("options", item[0]).set("attributes", {
        title: item[1],
        "data-tooltip-pos": "bottom",
    });
});

var titles = document.querySelectorAll("*[title]");
for (var i = 0; i < titles.length; i++) {
    var el = titles[i];
    var title = el.getAttribute("title");
    title = title ? title.trim() : "";
    if (!title) break;
    el.setAttribute("data-tooltip", title);
    el.setAttribute("title", "");
}

// Update canvas-clear command
cmdm.add("canvas-clear", function () {
    if (confirm("Are you sure to clean the canvas?")) {
        editor.runCommand("core:canvas-clear");
        setTimeout(function () {
            localStorage.clear();
        }, 0);
    }
});

//on editor load.
editor.on("load", function () {
    // Show borders by default
    pnm.getButton("options", "sw-visibility").set("active", 1);
});

//link the file manger to voila file manager.
function responsive_filemanager_callback(field_id, value) {
    $("#" + field_id, $(".gjs-frame").contents()).prop("src", value);
    if (editor.getSelected().attributes.tagName == "div")
        editor.getSelected().addStyle({
            "background-image": `url("${$_SITE + value}")`,
        });
    else editor.getSelected().set("src", $_SITE + value);
    editor.stopCommand("open-assets");
}
