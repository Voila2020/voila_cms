variables = {
    "@first-color": "#ec1e24",
    "@second-color": "#0d6efd",
    "@third-color": "#ff3399",
    "@fourth-color": "#ffff00",
};
editor = grapesjs.init({
    container: "#gjs",
    height: '100%',

    storageManager: {
        type: 'remote',
        stepsBeforeSave: 20,
        options: {
            remote: {
                headers: {
                    'Content-Type': 'application/json'
                },
                urlLoad: $_SITE + "/admin/landing-pages/page-builder-content/" + $id,
                urlStore: $_SITE + "/admin/landing-pages/page-builder/" + $id,

                onStore: data => ({
                    _token: $_token,
                    id: $id,
                    html: editor.getHtml(),
                    css: editor.getCss(),
                    components: editor.getComponents(),
                    variables: JSON.stringify(variables)
                }),

                onLoad: result => result.data,
                params: {
                    variables: JSON.stringify(variables)
                },
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
                    afterClose: function () {
                        editor.stopCommand("open-assets");
                    },
                });
            },
            close(props) { },
        },
    },
    styleManager: {
        clearProperties: 1,
    },

    selectorManager: {
        componentFirst: true
    },
    plugins: [
        'grapesjs-preset-webpage',
        'components',
        'traits',
        'define_new_traits',
        'basic_blocks',
        'structures',
        'layouts_blocks',
        'card_blocks',
        'typography_blocks',
        'navigation_blocks',
        'features_blocks',
        'headings_blocks',
        'footers_blocks',
        'voila_forms'
    ],
    pluginsOpts: {},
    canvas: {
        styles: [
            "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css",
            $_SITE + "/landing_page_builder/css/builder.css",
            $_SITE + "/landing_page_builder/css/canvas.css",
            $is_rtl == "1" ?
                $_SITE + "/landing_page_builder/css/rtl_styles.css" :
                $_SITE + "/landing_page_builder/css/ltr_styles.css",

        ],
        scripts: [
            "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js",



        ],
    },
});


//make the new color palette when we modify it appears to canvas on load.
editor.on("storage:end:load", (vars) => {
    if (vars.variables) variables = JSON.parse(vars.variables);
});




//Make the dotted boxes always visible
editor.Panels.getButton('options', 'sw-visibility').set('active', 1);
//Remove the default import button
editor.Panels.removeButton('options', 'gjs-open-import-webpage');

//On select foe an element open the settings tab.
editor.on("component:selected", model => {
    editor.Panels.getButton('views', 'open-tm').set('active', 1);
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

//color theme button
editor.Panels.addButton("options", [{
    id: "less",
    className: "fa fa-tint icon-blank open-modal",
    command: "",
    attributes: {
        title: "Select Theme Colors"
    },
},]);

//open the color picker modal
$(".open-modal").click(function () {
    $("#less-modal").modal("show");
});


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

editor.on('load', function () {

    //import the less styles and scripts to the canvas.
    const link1 = document.createElement("link");
    link1.rel = "stylesheet/less";
    link1.href = "" + $_SITE + "/landing_page_builder/less/styles.less";
    editor.Canvas.getDocument().head.appendChild(link1);

    const script1 = document.createElement("script");
    script1.src = "https://cdn.jsdelivr.net/npm/less@4.1";
    editor.Canvas.getDocument().head.appendChild(script1);

    const script2 = document.createElement("script");
    script2.src = "" + $_SITE + "/landing_page_builder/less/less.js";
    editor.Canvas.getDocument().head.appendChild(script2);


    $(".color-inputs").on("input", function () {
        let varName = $(this).attr("var");
        let varValue = $(this).val();
        variables[varName] = varValue;
        var f = $(".gjs-frame");
        f.get(0).contentWindow.firstInputColor(variables);
        less.modifyVars(variables);
        const data = ({
            id: $id,
            html: editor.getHtml(),
            css: editor.getCss(),
            variables: JSON.stringify(variables)
        });
        storageManager = editor.StorageManager.get("remote");
        storageManager.store(data);
    });

    setTimeout(function () {
        var f = $(".gjs-frame");
        f.get(0).contentWindow.firstInputColor(variables);
        less.modifyVars(variables);
    }, 500);

    for (let [key, value] of Object.entries(variables)) {
        $('.color-inputs[var="' + key + '"]').attr("value", value);
        $('.hexcolor[var="' + key + '"]').attr("value", value);
    }

    $(".hexcolor").on("input", function () {
        let varName = $(this).attr("var");
        let varValue = $(this).val();
        variables[varName] = varValue;
        var f = $(".gjs-frame");
        f.get(0).contentWindow.firstInputColor(variables);
        less.modifyVars(variables);

    });


    //Put a data-id in each "tab div" of the blocks we have
    let categories = editor.BlockManager.getCategories();
    categories.each((category) => {
        $(category.view.el).attr("data-id", category.id);
        category.set("open", false);
    });
});

//link the version 2 builder with the voila file manager.
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