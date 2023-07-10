editor = grapesjs.init({

    height: '100%',
    container: '#email_gjs',

    storageManager: {
        type: 'remote',
        stepsBeforeSave: 20,
        options: {
            remote: {
                headers: {
                    'Content-Type': 'application/json'
                },

                urlLoad: $_SITE + "/admin/email_templates/email-builder-content/" + $id + "?lang=" + $lang,
                urlStore: $_SITE + "/admin/email_templates/save-template/" + $id + "?lang=" + $lang,

                onStore: data => ({
                    lang: $lang,
                    _token: $_token,
                    id: $id,
                    html: editor.getHtml(),
                    css: editor.getCss(),
                    components: editor.getComponents(),
                }),
                onLoad: result => result.data,
            }
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
                });
            },
            close(props) { },
        },
    },

    plugins: ['grapesjs-preset-newsletter', 'grapesjs-plugin-ckeditor'],

});



//save button
editor.Panels.addButton("options", [{
    id: "save",
    className: "fa fa-floppy-o icon-blank fa-3x",
    command: function (editor1, sender) {
        var rr = editor.store();
    },
    attributes: {
        title: "Save Landing Page"
    },
},]);



//remove the import button.
editor.Panels.removeButton('options', 'gjs-open-import-template');

var pfx = editor.getConfig().stylePrefix;
var modal = editor.Modal;
var cmdm = editor.Commands;
var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
var pnm = editor.Panels;
var container = document.createElement('div');
var btnEdit = document.createElement('button');

//set the window that appears when we pree the import button
codeViewer.set({
    codeName: 'htmlmixed',
    readOnly: 0,
    theme: 'hopscotch',
    autoBeautify: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    lineWrapping: true,
    styleActiveLine: true,
    smartIndent: true,
    indentWithTabs: true
});

btnEdit.innerHTML = 'Import';
btnEdit.className = pfx + 'btn-prim ' + pfx + 'btn-import';
btnEdit.onclick = function () {
    var code = codeViewer.editor.getValue();
    editor.DomComponents.getWrapper().set('content', '');
    editor.setComponents(code.trim());
    modal.close();
};

//Set the html and css code in the prev window
cmdm.add('html-edit', {
    run: function (editor, sender) {
        sender && sender.set('active', 0);
        var viewer = codeViewer.editor;
        modal.setTitle('Edit code');
        if (!viewer) {
            var txtarea = document.createElement('textarea');
            container.appendChild(txtarea);
            container.appendChild(btnEdit);
            codeViewer.init(txtarea);
            viewer = codeViewer.editor;
        }
        var InnerHtml = editor.getHtml();
        var Css = editor.getCss();
        modal.setContent('');
        modal.setContent(container);
        codeViewer.setContent(InnerHtml + "<style>" + Css + '</style>');
        modal.open();
        viewer.refresh();
    }
});

//Put an import code button
pnm.addButton('options', [{
    id: 'import',
    className: 'fa fa-download',
    command: 'html-edit',
    attributes: {
        title: 'Import'
    }
}]);

// Let's add in this demo the possibility to test our newsletters
var pnm = editor.Panels;
var cmdm = editor.Commands;
var md = editor.Modal;


// Beautify tooltips
[
    ['sw-visibility', 'Show Borders'],
    ['preview', 'Preview'],
    ['fullscreen', 'Fullscreen'],
    ['export-template', 'Export'],
    ['undo', 'Undo'],
    ['redo', 'Redo'],
    ['gjs-toggle-images', 'Toggle images'],
    ['canvas-clear', 'Clear canvas']
].forEach(function (item) {
    pnm.getButton('options', item[0]).set('attributes', {
        title: item[1],
        'data-tooltip-pos': 'bottom'
    });
});


var titles = document.querySelectorAll('*[title]');
for (var i = 0; i < titles.length; i++) {
    var el = titles[i];
    var title = el.getAttribute('title');
    title = title ? title.trim() : '';
    if (!title)
        break;
    el.setAttribute('data-tooltip', title);
    el.setAttribute('title', '');
}

// Update canvas-clear command
cmdm.add('canvas-clear', function () {
    if (confirm('Are you sure to clean the canvas?')) {
        editor.runCommand('core:canvas-clear')
        setTimeout(function () {
            localStorage.clear()
        }, 0)
    }
});

//on editor load.
editor.on('load', function () {
    // Show borders by default
    pnm.getButton('options', 'sw-visibility').set('active', 1);
});

//link the file manger to voila file manager.
function responsive_filemanager_callback(field_id, value) {
    $("#" + field_id, $(".gjs-frame").contents()).prop("src", value);
    if (editor.getSelected().attributes.tagName == "div")
        editor
            .getSelected()
            .addStyle({
                "background-image": `url("${$_SITE + value}")`
            });
    else editor.getSelected().set("src", $_SITE + value);
    editor.stopCommand("open-assets");
}
