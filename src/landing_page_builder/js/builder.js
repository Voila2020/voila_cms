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
        stepsBeforeSave: 100,
        options: {
            remote: {
                headers: {
                    'Content-Type': 'application/json'
                },
                urlLoad: $_SITE + "/admin/landing-pages/page-builder-content/" + $id,
                urlStore: $_SITE + "/admin/landing-pages/page-builder/" + $id,

                onStore: (data, editor) => {
                    removeComponentsNulled(editor);
                    return {
                        _token: $_token,
                        id: $id,
                        html: editor.getHtml(),
                        css: editor.getCss(),
                        components: editor.getComponents(),
                        variables: JSON.stringify(variables)
                    };
                },

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
                let imageId;
                if (props.options.target)
                    imageId = props.options.target.ccid;
                else
                    imageId = editor.getSelected().ccid;
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
        'grapesjs-plugin-export',
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
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-shims.min.css",
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css",
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


//put a plus button on selected element to add new block.
editor.on('component:selected', (editor) => {

    if (!editor) {
        editor = editor
    }

    const selectedComponent = this.editor.getSelected();
    if (selectedComponent && selectedComponent.attributes) {
        const commandBlockTemplateIcon = 'fa fa-plus fa-2xs'
        const commandBlockTemplate = () => {
            $('#staticBackdrop1').modal('show');
        }
        const defaultToolbar = selectedComponent.get('toolbar');
        const commandExists = defaultToolbar.some((item) => item.command.name === 'commandBlockTemplate');
        if (!commandExists) {
            selectedComponent.set({
                toolbar: [...defaultToolbar, { attributes: { class: commandBlockTemplateIcon }, command: commandBlockTemplate }]
            });
        }
    }
});


$('#custom-block-form').submit(function (evt) {
    evt.preventDefault();
    const selected = editor.getSelected();
    var name = $("#block_name").val();
    let blockId = 'customBlockTemplate_' + name.split(' ').join('_')
    let name_blockId = {
        'name': name,
        'blockId': blockId
    }
    createBlockTemplate(editor, selected, name_blockId);
    $('#staticBackdrop1').modal('hide');

});


const getCss = (editor, id) => {
    const style = editor.CssComposer.getRule(`#${id}`);
    const hoverStyle = editor.CssComposer.getRule(`#${id}:hover`);
    if (style) {
        if (hoverStyle) {
            return style.toCSS() + ' ' + hoverStyle.toCSS()
        }
        return style.toCSS()
    } else {
        return ''
    }
}

const findComponentStyles = (editor, selected) => {
    let css = ''
    if (selected) {
        const childModel = selected.components().models
        if (childModel) {
            for (const model of childModel) {
                css = css + findComponentStyles(editor, model)
            }
            return css + getCss(editor, selected.getId());
        } else {
            return getCss(editor, selected.getId());
        }
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const createBlockTemplate = (editor, selected, name_blockId) => {
    const bm = editor.BlockManager
    const blockId = name_blockId.blockId;
    const name = name_blockId.name;

    let elementHTML = selected.getEl().outerHTML;
    let first_partHtml = elementHTML.substring(0, elementHTML.indexOf(' '));
    let second_partHtml = elementHTML.substring(elementHTML.indexOf(' ') + 1);
    first_partHtml += ` custom_block_template=true block_id="${blockId}" `
    let finalHtml = first_partHtml + second_partHtml
    const blockCss = findComponentStyles(editor, selected)
    const css = `<style>${blockCss}</style>`
    const elementHtmlCss = finalHtml + css

    bm.add(`${blockId}`, {
        category: 'Custom Blocks',
        attributes: { custom_block_template: true },
        label: `${name}`,
        media: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M16.725 19.5q-.3-.125-.563-.263t-.537-.337l-1.075.325q-.175.05-.325-.013t-.25-.212l-.6-1q-.1-.15-.05-.325t.175-.3l.825-.725q-.05-.3-.05-.65t.05-.65l-.825-.725q-.125-.125-.175-.3t.05-.325l.6-1q.1-.15.25-.212t.325-.013l1.075.325q.275-.2.537-.337t.563-.263l.225-1.1q.05-.175.163-.288t.312-.112h1.2q.2 0 .313.113t.162.287l.225 1.1q.3.125.563.263t.537.337l1.075-.325q.175-.05.325.013t.25.212l.6 1q.1.15.05.325t-.175.3l-.825.725q.05.3.05.65t-.05.65l.825.725q.125.125.175.3t-.05.325l-.6 1q-.1.15-.25.213t-.325.012l-1.075-.325q-.275.2-.537.338t-.563.262l-.225 1.1q-.05.175-.163.288t-.312.112h-1.2q-.2 0-.313-.113t-.162-.287l-.225-1.1Zm1.3-1.5q.825 0 1.413-.588T20.025 16q0-.825-.587-1.413T18.025 14q-.825 0-1.412.588T16.024 16q0 .825.588 1.413t1.412.587ZM5 20q-.825 0-1.412-.588T3 18V4q0-.825.588-1.413T5 2h14q.825 0 1.413.588T21 4v5.65q-.475-.225-.975-.363T19 9.075V4H5v9h3.55q.3 0 .525.138t.35.362q.35.575.775.9t.925.475q-.225 1.35.063 2.675T12.275 20H5Z"/></svg>',
        content: elementHtmlCss,
    })

    $.ajax({
        data: {
            custom_block_data: elementHtmlCss,
            name: name,
            blockId: blockId,
        },
        type: 'POST',
        success: function (data) {
            $('html, body').css("cursor", "auto");
        },
        error: function (data) {
            $('html, body').css("cursor", "auto");
        }
    });
}

if (blocks) {
    blocks.forEach(element => {
        editor.BlockManager.add(`${element.blockID}`, {
            category: 'Custom Blocks',
            attributes: {
                custom_block_template: true
            },
            label: `${element.block_name}`,
            media: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M16.725 19.5q-.3-.125-.563-.263t-.537-.337l-1.075.325q-.175.05-.325-.013t-.25-.212l-.6-1q-.1-.15-.05-.325t.175-.3l.825-.725q-.05-.3-.05-.65t.05-.65l-.825-.725q-.125-.125-.175-.3t.05-.325l.6-1q.1-.15.25-.212t.325-.013l1.075.325q.275-.2.537-.337t.563-.263l.225-1.1q.05-.175.163-.288t.312-.112h1.2q.2 0 .313.113t.162.287l.225 1.1q.3.125.563.263t.537.337l1.075-.325q.175-.05.325.013t.25.212l.6 1q.1.15.05.325t-.175.3l-.825.725q.05.3.05.65t-.05.65l.825.725q.125.125.175.3t-.05.325l-.6 1q-.1.15-.25.213t-.325.012l-1.075-.325q-.275.2-.537.338t-.563.262l-.225 1.1q-.05.175-.163.288t-.312.112h-1.2q-.2 0-.313-.113t-.162-.287l-.225-1.1Zm1.3-1.5q.825 0 1.413-.588T20.025 16q0-.825-.587-1.413T18.025 14q-.825 0-1.412.588T16.024 16q0 .825.588 1.413t1.412.587ZM5 20q-.825 0-1.412-.588T3 18V4q0-.825.588-1.413T5 2h14q.825 0 1.413.588T21 4v5.65q-.475-.225-.975-.363T19 9.075V4H5v9h3.55q.3 0 .525.138t.35.362q.35.575.775.9t.925.475q-.225 1.35.063 2.675T12.275 20H5Z"/></svg>',
            content: element.custom_block_data,
        });
    });
}


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
    className: "fa fa-floppy-o icon-blank d-flex align-items-center justify-content-center",
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
    className: "fa fa-tint icon-blank open-modal d-flex align-items-center justify-content-center",
    command: "",
    attributes: {
        title: "Select Theme Colors"
    },
},]);

// Go To Page Button
editor.Commands.add('redirect-to-page', {
    run(editor, sender) {
        const url = $_SITE + "/" + $url;
        window.open(url, '_blank');
        sender && sender.set('active', false);
    }
});
editor.Panels.addButton("options", [{
    id: "go-to",
    className: "fa fa-search icon-blank open-modal d-flex align-items-center justify-content-center",
    command: "redirect-to-page",
    attributes: {
        title: "Go To Page"
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

    $(".gjs-frame").contents().on("paste", '[contenteditable="true"]', function (e) {
        e.preventDefault();
        var text = e.originalEvent.clipboardData.getData('text');
        e.target.ownerDocument.execCommand("insertText", false, text);
    });
});

//link the version 2 builder with the voila file manager.
function responsive_filemanager_callback(field_id, value) {

    if (editor.getSelected().attributes.tagName != "img") {
        editor
            .getSelected()
            .addStyle({
                "background-image": `url("${$_SITE + value}")`
            });
        $("#" + field_id, $(".gjs-frame").contents()).css("background-image", `url("${$_SITE + value}")`);
    }
    else {
        editor.getSelected().set("src", $_SITE + value);
        $("#" + field_id, $(".gjs-frame").contents()).prop("src", value);
    }
    editor.stopCommand("open-assets");
}

function removeComponentsNulled(editor) {
    const components = editor.getComponents();

    // Recursive function to search and remove components
    function searchAndRemoveComponents(components) {
        components.forEach(component => {
            try {
                if (component.get('content') && component.get('content') == "null") {
                    component.remove();
                } else if (component.components().length) {
                    searchAndRemoveComponents(component.components());
                }
            } catch (e) {
            }
        });
    }

    searchAndRemoveComponents(components);
}
