variables = {
    "@first-color": "#e48438",
    "@second-color": "#2a3d48",
    "@third-color": "#9d8966",
    "@fourth-color": "#f1eeea",
};
let liveHtml, liveComponents;
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
                urlLoad: $_SITE + "/admin/" + $modulePath + "/content-builder-content/" + $id + "?" + $extra_params,
                urlStore: $_SITE + "/admin/" + $modulePath + "/content-builder/" + $id + "?" + $extra_params,

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

                onLoad: (data, editor) => {
                    liveComponents = "[]";
                    let html = data.html;
                    // html = wrapTextNodesInHtmlString(html);
                    liveHtml = html;
                    return {
                        html: html,
                        css: data.styles,
                        components: data.components,
                        variables: data.variables
                    };
                },
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
        'features_blocks',
        'headings_blocks',
        'grapesjs-rte-extensions',
    ],
    pluginsOpts: {
        'grapesjs-rte-extensions': {
            base: {
                bold: true,
                italic: true,
                underline: true,
                strikethrough: true,
                link: true,
            },
            format: {
                heading1: true,
                heading2: true,
                heading3: true,
                heading4: true,
                heading5: true,
                heading6: true,
                paragraph: true,
                quote: false,
                clearFormatting: true,
            },
            fonts: {
                fontColor: false,
                hilite: false,
            },
            subscriptSuperscript: true,
            indentOutdent: true,
            list: true,
            align: true,
            actions: {
                copy: true,
                cut: true,
                paste: true,
                delete: true,
            },
            actions: true,
            undoredo: true,
            extra: true,
            darkColorPicker: true,
            maxWidth: '100%'
        }
    },
    canvas: {
        styles: [
            "https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css",
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-shims.min.css",
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css",
            $_SITE + "/content_builder/css/builder.css",
            $_SITE + "/content_builder/css/canvas.css?_t="+(Date.now()),
            $_SITE + "/content_builder/css/styles.css",

            /* Please adjust the following styles to match the your website's style. */
            
            $_SITE + "/assets/css/main.css" + `?t=${Date.now()}`,
            $_SITE + "/assets/css/master-portfolio-material.css",
            "https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap",
            "https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap",
            $is_rtl == "1" ? $_SITE + "/content_builder/css/rtl_styles.css" : $_SITE + "/content_builder/css/ltr_styles.css",
            $is_rtl == "1" ? $_SITE + "/assets/css/rtl/rtl.css" : "",
            $_SITE + "/assets/css/spacing.css",

        ],
        scripts: [
            "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js",



        ],
    },
});

editor.on("load", () => {
    if (localStorage.getItem('grapesjs_notes_modal_shown_content_builder') !== 'true') {
        $('#notesModal').modal('show');
        localStorage.setItem('grapesjs_notes_modal_shown_content_builder', 'true');
    }
});

editor.RichTextEditor.add('color', {
    icon: '<input type="color" class="gjs-rte-color" style="width:70px;height:20px;padding:0;border:none;cursor:pointer;">',
    attributes: { title: 'Text Color' },
    result: (rte, action) => {
        const input = rte.actionbar.querySelector('.gjs-rte-color');
        if (input) {
            input.oninput = e => rte.exec('foreColor', e.target.value);
            input.click();
        }
    }
});


editor.on('load', () => {
    if (liveComponents == "[]") {
        editor.DomComponents.getWrapper().set('content', '');
        editor.setComponents(liveHtml);
    }

    const canvasBody = editor.Canvas.getBody();
    canvasBody.style.backgroundColor = "#f3ece4"; // غيّر الخلفية

    const htmlEl = editor.Canvas.getDocument().documentElement; // = <html>
    htmlEl.classList.add('agntix-light');
    htmlEl.classList.add('service-details');
});

//make the new color palette when we modify it appears to canvas on load.
editor.on("storage:end:load", (vars) => {
    if (vars.variables) variables = JSON.parse(vars.variables);
});

editor.Commands.add('wrap-with-div', {
  run(editor, sender, opts = {}) {
    const selected = editor.getSelected();
    if (!selected) {
      alert('Please select a component first.');
      return;
    }

    const parent = selected.parent();
    if (!parent) {
      alert('Cannot wrap the root wrapper.');
      return;
    }

    // Prompt for an id, auto-generated
    let wrapperId = `wrapper-${Date.now()}`;

    try {
      // find the index of the selected component in its parent's components collection
      const comps = parent.components();
      const index = Array.prototype.indexOf.call(comps.models || comps, selected);

      // create wrapper at the same position
      const wrapper = parent.components().add({
        tagName: 'div',
        attributes: { id: wrapperId },
        components: []
      }, { at: index });

      // Move the selected component into the wrapper.
      // prefer wrapper.append(selected) if available, otherwise fallback
      if (typeof wrapper.append === 'function') {
        wrapper.append(selected);
      } else {
        // fallback: remove from parent then add to wrapper
        parent.components().remove(selected);
        wrapper.components().add(selected);
      }

      // select the new wrapper so the user sees it
      editor.select(wrapper);
    } catch (err) {
      console.error('wrap-with-div error:', err);
      alert('Failed to wrap component. See console for details.');
    } finally {
      sender && sender.set && sender.set('active', false);
    }
  }
});

//Add toolbar button on component select (id prevents duplicates)
editor.on('component:selected', (model) => {
  const selectedComponent = model; // model is the selected component

  if (!selectedComponent || !selectedComponent.get) return;

  // Read existing toolbar (defensive)
  const defaultToolbar = Array.isArray(selectedComponent.get('toolbar')) ? [...selectedComponent.get('toolbar')] : [];

  // If button already present skip
  const exists = defaultToolbar.some(item => {
    if (!item) return false;
    // item.command can be string or function — check id or command name
    return (item.id && item.id === 'wrap-with-div')
      || (item.command === 'wrap-with-div')
      || (item.command && item.command.name === 'wrap-with-div');
  });

  if (!exists) {
    defaultToolbar.push({
      id: 'wrap-with-div',
      attributes: { class: 'fa fa-box', title: 'Wrap with Div' },
      command: 'wrap-with-div'
    });

    selectedComponent.set('toolbar', defaultToolbar);
  }
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
        let mediaContent;
        if (element.image) {
            if (element.image.trim().startsWith("<svg")) {
                mediaContent = element.image;
            } else {
                mediaContent = `<img src="${element.image}" alt="${element.block_name}" style="max-width:100%; height:auto;" />`;
            }
        } else {
            mediaContent = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M16.725 19.5q-.3-.125-.563-.263t-.537-.337l-1.075.325q-.175.05-.325-.013t-.25-.212l-.6-1q-.1-.15-.05-.325t.175-.3l.825-.725q-.05-.3-.05-.65t.05-.65l-.825-.725q-.125-.125-.175-.3t.05-.325l.6-1q.1-.15.25-.212t.325-.013l1.075.325q.275-.2.537-.337t.563-.263l.225-1.1q.05-.175.163-.288t.312-.112h1.2q.2 0 .313.113t.162.287l.225 1.1q.3.125.563.263t.537.337l1.075-.325q.175-.05.325.013t.25.212l.6 1q.1.15.05.325t-.175.3l-.825.725q.05.3.05.65t-.05.65l.825.725q.125.125.175.3t-.05.325l-.6 1q-.1.15-.25.213t-.325.012l-1.075-.325q-.275.2-.537.338t-.563.262l-.225 1.1q-.05.175-.163.288t-.312.112h-1.2q-.2 0-.313-.113t-.162-.287l-.225-1.1Zm1.3-1.5q.825 0 1.413-.588T20.025 16q0-.825-.587-1.413T18.025 14q-.825 0-1.412.588T16.024 16q0 .825.588 1.413t1.412.587ZM5 20q-.825 0-1.412-.588T3 18V4q0-.825.588-1.413T5 2h14q.825 0 1.413.588T21 4v5.65q-.475-.225-.975-.363T19 9.075V4H5v9h3.55q.3 0 .525.138t.35.362q.35.575.775.9t.925.475q-.225 1.35.063 2.675T12.275 20H5Z"/>
                            </svg>`;
        }
        editor.BlockManager.add(`${element.blockID}`, {
            category: 'Custom Blocks',
            attributes: {
                custom_block_template: true
            },
            label: `${element.block_name}`,
            media: mediaContent,
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
        command: async function (editor1, sender) {
            // confirm before saving
            if (!confirm("Are you sure you want to save this Content?")) {
                return;
            }

            // add loading state
            const btn = sender;
            btn.set("className", "fa fa-spinner fa-spin d-flex align-items-center justify-content-center");
            btn.set("attributes", { title: "Saving..." });

            try {
                // call GrapesJS store method
                await editor1.store();

                //alert("Content saved successfully!");
            } catch (err) {
                console.error("Save error:", err);
                alert("Failed to save the Content. Please try again.");
            } finally {
                // restore button state
                btn.set("className", "fa fa-floppy-o icon-blank d-flex align-items-center justify-content-center");
                btn.set("attributes", { title: "Save Content" });
            }
        },
        attributes: {
            title: "Save Content",
        },
    },
]);

//color theme button
editor.Panels.addButton("options", [{
    id: "less",
    className: "fa fa-tint icon-blank open-modal d-flex align-items-center justify-content-center",
    command: "",
    attributes: {
        title: "View Theme Colors"
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
    className: "fa fa-search icon-blank d-flex align-items-center justify-content-center",
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
/* Add Tour to Content builder */
var tourSteps = [
  {
    element: '.gjs-frame',
    intro: 'This is the main canvas — drag blocks here and edit content. Click an element to open its settings.'
  },
  {
    element: '.gjs-pn-views-container',
    intro: 'Block sidebar — drag blocks or custom components into the canvas.'
  },
  {
    element: '.gjs-blocks-cs',
    intro: 'Block categories — open a category to browse blocks grouped by type.'
  },
  {
    element: '.gjs-block-category[data-id="Custom Blocks"]',
    intro: 'Custom Blocks — drag a block into the canvas and edit its content.'
  },
  {
    element: '.gjs-pn-views',
    intro: 'Settings panel — shows attributes, styles, and traits for the selected element.'
  },
  {
    element: '.gjs-pn-commands',
    intro: 'Top navigation — quick access to actions like Save, Export, Undo, Redo, and more.'
  },
  {
    element: 'span.fa-info-circle',
    intro: 'Notes button — shows important guidance and usage notes for the builder.'
  },
  {
    element: '#startTour',
    intro: 'Start Tour — get a guided walkthrough of the builder’s features.'
  },
  {
    element: 'span.fa-download',
    intro: 'Import — bring in external code or edit the source HTML and CSS directly.'
  },
  {
    element: 'span.fa-search',
    intro: 'Open Page — preview the page live in a new tab.'
  },
  {
    element: 'span.fa-tint',
    intro: 'Theme Colors — view or adjust the theme colors used across the site.'
  },
  {
    element: 'span.fa-floppy-o',
    intro: 'Save button — saves your current work in the database.'
  },
  {
    element: 'span.gjs-pn-btn:nth-child(7)',
    intro: 'Clear Content — remove all elements and reset the canvas to start fresh.'
  },
  {
    element: 'span.gjs-pn-btn:nth-child(6)',
    intro: 'Redo button — Reapply the change you just reverted.'
  },
  {
    element: 'span.gjs-pn-btn:nth-child(5)',
    intro: 'Undo button — Revert the last change you made on the canvas.'
  },
  {
    element: 'span[title="View code"]',
    intro: 'View & Export — see the page’s HTML/CSS code and export it for use elsewhere.'
  },
  {
    element: 'span[title="Fullscreen"]',
    intro: 'Fullscreen — expand the builder to occupy the entire screen for a distraction-free editing experience.'
  },
  {
    element: 'span[title="Preview"]',
    intro: 'Preview — open a live view of your content as visitors will see it.'
  },
  {
    element: '[title="View components"]',
    intro: 'Toggle visibility — show or hide the dotted outlines around elements.'
  },{
    element: '.gjs-pn-panel.gjs-pn-devices-c',
    intro: 'Responsive View — preview how your content looks on different devices (desktop, tablet, mobile).'
  },
  {
    element: '.gjs-frame',
    intro: '<strong>Tip:</strong> You can select elements inside the canvas and use the toolbar to duplicate, delete, or save them as custom blocks.'
  }
];


editor.Panels.addButton('options', [{
    id: 'startTour',
    className: 'fa fa-compass icon-blank d-flex align-items-center justify-content-center',
    command: (editor) => {
        introJs().setOptions({
            steps: tourSteps,
            showProgress: true,
            exitOnOverlayClick: false,
            nextLabel: 'Next →',
            prevLabel: '← Back',
            doneLabel: 'Finish'
        }).start();
    },
    attributes: {
        title: 'Start Tour',
        id:'startTour'
    },
},
]);

editor.on('load', function () {

    //import the less styles and scripts to the canvas.
    const link1 = document.createElement("link");
    link1.rel = "stylesheet/less";
    link1.href = "" + $_SITE + "/content_builder/less/styles.less";
    editor.Canvas.getDocument().head.appendChild(link1);

    const script1 = document.createElement("script");
    script1.src = "https://cdn.jsdelivr.net/npm/less@4.1";
    editor.Canvas.getDocument().head.appendChild(script1);

    const script2 = document.createElement("script");
    script2.src = "" + $_SITE + "/content_builder/less/less.js";
    editor.Canvas.getDocument().head.appendChild(script2);


    for (let [key, value] of Object.entries(variables)) {
        $('.color-inputs[var="' + key + '"]').attr("value", value);
        $('.hexcolor[var="' + key + '"]').attr("value", value);
    }

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

    // editor.on("stop", (commandId) => {
    //     console.log("Stop", commandId);
    // });

    // editor.on("run", (commandId) => {
    //     console.log("run", commandId);
    // });


    $('#startTourBtn').click(function(){
        $('#notesModal').modal('hide');
        $('#startTour').click();
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
// disable Style Manager tab
editor.on('load', () => {
    editor.Panels.removeButton('views', 'open-sm');
});
editor.Panels.addButton('options', [{
    id: 'showNotes',
    className: 'fa fa-info-circle icon-blank d-flex align-items-center justify-content-center',
    command: (editor) => {
        $('#notesModal').modal('show');
    },
    attributes: {
        title: 'Show Notes'
    },
},
]);
// Edit RichTextEditor Position
editor.on('rte:enable', () => {
    setTimeout(setupToolbarObserver, 100);
});

function setupToolbarObserver() {
    const toolbar = document.querySelector('.gjs-rte-toolbar');
    if (!toolbar) return;

    const observer = new MutationObserver(() => {
        const height = toolbar.offsetHeight;
        toolbar.style.top = `-${height}px`;
    });

    observer.observe(toolbar, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });
}
// Provide eye icon control for showing or hiding RichTextEditor options
editor.on('load', () => {
    const rteToolbar = document.querySelector('.gjs-rte-actionbar');

    const formatButtons = Array.from(rteToolbar.querySelectorAll('.gjs-rte-action'));

    formatButtons.slice(7).forEach(button => button.style.display = 'none');

    const eyeButton = document.createElement('span');
    eyeButton.innerHTML = '<i class="fas fa-eye" title="More..."></i>';
    eyeButton.className = 'gjs-rte-eye-button';
    rteToolbar.appendChild(eyeButton);

    eyeButton.addEventListener('click', () => {
        const isExpanded = eyeButton.classList.toggle('active');
        formatButtons.slice(7).forEach(button => {
            button.style.display = isExpanded ? 'inline-block' : 'none';
        });
        eyeButton.innerHTML = isExpanded ? '<i class="fas fa-eye-slash" title="Less..."></i>' : '<i class="fas fa-eye" title="More..."></i>'; // Eye closed and open icons
        eyeButton.style.fontWeight = isExpanded ? 'bold' : 'normal'; // Optional: change style
    });
});

// function wrapTextNodesInHtmlString(htmlString) {
//     const parser = new DOMParser();
//     const doc = parser.parseFromString(htmlString, 'text/html');

//     function wrapTextNodes(node) {
//         [...node.childNodes].forEach(child => {
//             if (child.nodeType === Node.TEXT_NODE && child.textContent.trim() !== '') {
//                 const span = doc.createElement('span');
//                 span.textContent = child.textContent;
//                 child.replaceWith(span);
//             } else if (child.nodeType === Node.ELEMENT_NODE) {
//                 wrapTextNodes(child);
//             }
//         });
//     }

//     wrapTextNodes(doc.body);

//     return doc.body.innerHTML;
// }
