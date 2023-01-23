variables = {
    "@first-color": "#eb7623",
    "@second-color": "#0d6efd",
    "@third-color": "#ff3399",
    "@fourth-color": "#ffff00",
};
localStorage.clear();
$(function () {
    // $.ajaxSetup({
    //     cache: false,
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    $filemanager_value = "";
    $body = $("body");
    $template = "";
    //---- Loading Icons
    localStorage.clear();
    //-----------------------------------------------//
    //---- Init Editor
    editor = grapesjs.init({
        container: "#gjs",
        height: "100%",
        showOffsets: 1,
        noticeOnUnload: 1,
        avoidInlineStyle: 1,
        showToolbar: 1,
        selectorManager: {
            componentFirst: true,
        },
        allowScripts: 1,
        jsInHtml: true,
        storageManager: {
            type: "remote",
            urlStore:
                $_SITE +
                "/admin/landing-pages/landing-page?_token=" + $('meta[name="csrf-token"]').attr('content') + "&id=" +
                $id +
                "&template=" +
                $template,
            urlLoad:
                $_SITE +
                "/admin/landing-pages/landing-page?id=" +
                $id +
                "&template=" +
                $template,
            params: { variables: JSON.stringify(variables) },
            autosave: true,
            stepsBeforeSave: 50,
            storeComponents: 1,
            storeStyles: 1,
            storeHtml: 1,
            storeCss: 1,
            storeJs: 1,
            contentTypeJson: true,
        },
        fromElement: 0,
        keepUnusedStyles: 1,
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

                        },
                    });
                },
                close(props) {
                },
            },
        },
        styleManager: {
            clearProperties: 1,
        },
        plugins: [
            "grapesjs-preset-webpage",
            "grapesjs-custom-code",
            "grapesjs-touch",
            "grapesjs-parser-postcss",
            "grapesjs-tooltip",
            "grapesjs-tui-image-editor",
            "grapesjs-blocks-bootstrap4",
            "voila_form",
            "additional_basic_blocks",
            "features",
            "headings",
            "footers",
            "voila_theme",
            "voila_button",
        ],
        pluginsOpts: {
            "gjs-plugin-ckeditor": {
                position: "center",
                options: {
                    startupFocus: true,
                    extraAllowedContent: "*(*);*{*}",
                    allowedContent: true,
                    enterMode: CKEDITOR.ENTER_BR,
                    extraPlugins: "sharedspace,justify,colorbutton,panelbutton,font",
                    toolbar: [
                        { name: "styles", items: ["Font", "FontSize"] },
                        ["Bold", "Italic", "Underline", "Strike"],
                        { name: "paragraph", items: ["NumberedList", "BulletedList"] },
                        { name: "links", items: ["Link", "Unlink"] },
                        { name: "colors", items: ["TextColor", "BGColor"] },
                    ],
                },
            },
            "gjs-preset-webpage": {
                modalImportTitle: "Import Template",
                modalImportLabel:
                    '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                modalImportContent: function (editor) {
                    return editor.getHtml() + "<style>" + editor.getCss() + "</style>";
                },
                filestackOpts: null,
                aviaryOpts: false,
                blocksBasicOpts: { flexGrid: 1 },
            },
            "grapesjs-tui-image-editor": {
                script: [
                    "https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js",
                    "https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js",
                    "https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js",
                ],
                style: [
                    "https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css",
                    "https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css",
                ],
            },
        },
        canvas: {
            styles: [
                "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css",
                "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css",
                "https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css",
                "https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css",
                "https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css",
                "https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.6/swiper-bundle.css",
                "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css",
                "https://fonts.googleapis.com/css?family=Lobster|Tajawal|Vollkorn|Open+Sans|Cairo|Almarai|Changa|Lareza|Noto+Sans+Arabic|IBM+Plex+Sans+Arabic|Lato",
                $_SITE + "/landing_page_builder/css/builder.css",
                $is_rtl == "1"
                    ? $_SITE + "/landing_page_builder/css/rtl_styles.css"
                    : $_SITE + "/landing_page_builder/css/ltr_styles.css",
                $_SITE + "/landing_page_builder/less/styles.less",
            ],
            scripts: [
                "https://code.jquery.com/jquery-3.6.0.min.js",
                "https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.min.js",
                "https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.6/swiper-bundle.min.js",
                "https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js",
                "https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js",
                "https://cdn.jsdelivr.net/npm/less@4.1.1",
                $_SITE + "/landing_page_builder/less/less.js",
            ],
        },
    });
    //-----------------------------------------------//

    //-----------------------------------------------//
    editor.on("storage:start:store", function () {
        $body.addClass("loading");
    });
    editor.on("storage:load:end", function () {
        $body.removeClass("loading");
    });
    editor.on("storage:end", function () {
        $body.removeClass("loading");
    });
    //-----------------------------------------------//
    //---- Get File Manager Photos
    const am = editor.AssetManager;
    //-----------------------------------------------//
    editor.on("storage:end:load", (vars) => {
        if (vars.variables) variables = JSON.parse(vars.variables);
    });
    //-----------------------------------------------//

    var pn = editor.Panels;
    var cmdm = editor.Commands;
    var blockManager = editor.BlockManager;
    var storageManager = editor.StorageManager;

    //--- Add Clear Canvas Button TODO
    cmdm.add("canvas-clear", function () {
        if (confirm("Are you sure to clean the canvas?")) {
            editor.CssComposer.clear();
            editor.DomComponents.clear();
            // editor.store();
        }
    });
    //---------------------------------------------------//
    pn.getButton("options", "sw-visibility").set("active", 1);
    // Load and show settings and style manager
    var openTmBtn = pn.getButton("views", "open-tm");
    openTmBtn && openTmBtn.set("active", 1);
    var openSm = pn.getButton("views", "open-sm");
    openSm && openSm.set("active", 1);
    //-------------------------------------------------------//

    cmdm.add("set-device-desktop", {
        run: function (ed) {
            ed.setDevice("Desktop");
        },
        stop: function () { },
    });
    cmdm.add("set-device-tablet", {
        run: function (ed) {
            ed.setDevice("Tablet");
        },
        stop: function () { },
    });
    cmdm.add("set-device-mobile", {
        run: function (ed) {
            ed.setDevice("Mobile portrait");
        },
        stop: function () { },
    });

    // Simple warn notifier
    var origWarn = console.warn;
    toastr.options = {
        closeButton: true,
        preventDuplicates: true,
        showDuration: 250,
        hideDuration: 150,
    };
    console.warn = function (msg) {
        if (msg.indexOf("[undefined]") == -1) {
            toastr.warning(msg);
        }
        origWarn(msg);
    };

    // Add and beautify tooltips
    [
        ["sw-visibility", "Show Borders"],
        ["preview", "Preview"],
        ["fullscreen", "Fullscreen"],
        ["export-template", "Export"],
        ["undo", "Undo"],
        ["redo", "Redo"],
        ["gjs-open-import-webpage", "Import"],
        ["canvas-clear", "Clear canvas"],
    ].forEach(function (item) {
        pn.getButton("options", item[0]).set("attributes", {
            title: item[1],
            "data-tooltip-pos": "bottom",
        });
    });
    [
        ["open-sm", "Style Manager"],
        ["open-layers", "Layers"],
        ["open-blocks", "Blocks"],
    ].forEach(function (item) {
        pn.getButton("views", item[0]).set("attributes", {
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
    // Do stuff on load
    editor.on("load", function () {
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
        // if (variables) {
        setTimeout(function () {
            var f = $(".gjs-frame");
            f.get(0).contentWindow.firstInputColor(variables);
            less.modifyVars(variables);
            editor.StorageManager.get("remote").set({
                params: { variables: JSON.stringify(variables) },
            });
        }, 500);

        for (let [key, value] of Object.entries(variables)) {
            $('.color-inputs[var="' + key + '"]').attr("value", value);
        }

        for (let [key, value] of Object.entries(variables)) {
            $('.hexcolor[var="' + key + '"]').attr("value", value);
        }
        // }

        var iframeBody = editor.Canvas.getBody();
        $(iframeBody).on("paste", '[contenteditable="true"]', function (e) {
            e.preventDefault();
            var text = e.originalEvent.clipboardData.getData("text");
            e.target.ownerDocument.execCommand("insertText", false, text);
        });

        // remove unwanted blocks
        //-----------------------------------------------//
        blockManager = editor.BlockManager;
        const blocks = blockManager.getAll();
        var toRemove = blocks.filter(function (block) {
            return (
                block.get("category").id == "Forms" ||
                block.get("category").id == "Media" ||
                (block.get("category").id == "Basic" &&
                    $.inArray(block.get("id"), [
                        "column1",
                        "column2",
                        "column3",
                        "column3-7",
                        "quote",
                        "map",
                    ]) != -1) ||
                (block.get("category").id == "Layout" &&
                    $.inArray(block.get("id"), ["column_break", "media_object"]) != -1) ||
                (block.get("category").id == "Components" &&
                    $.inArray(block.get("id"), [
                        "tabs-tab",
                        "tabs",
                        "tabs-tab-pane",
                        "badge",
                        "alert",
                        "card_container",
                        "collapse",
                        "dropdown",
                    ]) != -1) ||
                (!block.get("category").id &&
                    $.inArray(block.get("id"), ["media_object"]) != -1) ||
                block.get("category").id == "Extra" ||
                ($.inArray(block.get("id"), [
                    "voila_form_ltr",
                    "nav_ltr",
                    "nav2_ltr",
                ]) != -1 &&
                    $is_rtl == "1") ||
                ($.inArray(block.get("id"), [
                    "voila_form_rtl",
                    "nav_rtl",
                    "nav2_rtl",
                ]) != -1 &&
                    $is_rtl == "0")
            );
        });
        toRemove.forEach((block) => blockManager.remove(block.get("id")));
        blockManager.render(!toRemove);

        // Load and show settings and style manager
        var openTmBtn = pn.getButton("views", "open-tm");
        openTmBtn && openTmBtn.set("active", 1);
        var openSm = pn.getButton("views", "open-sm");
        openSm && openSm.set("active", 1);

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton("views", "open-blocks");
        openBlocksBtn && openBlocksBtn.set("active", 1);
        /* hide layer manager tab */

        //-----------------------------------------------//
        //--- Set Blocks Default Close
        let categories = blockManager.getCategories();
        categories.each((category) => {
            $(category.view.el).attr("data-id", category.id);
            category.set("open", false);
        });
        //-----------------------------------------------//
        //--- Close Opened Blocks When One Open
        $(".gjs-title").click(function () {
            let block = $(this).closest(".gjs-block-category");
            if (!block.hasClass("gjs-open")) {
                $(".gjs-open").find(".gjs-blocks-c").css("display", "none");
                $(".gjs-open")
                    .find(".fa-caret-down")
                    .removeClass("fa-caret-down")
                    .addClass("fa-caret-right");
                $(".gjs-open").removeClass("gjs-open");
            }
        });
        //-------------------------------------------//

        const prop = editor.StyleManager.getProperty("typography", "font-family");

        prop.set("options", [
            { value: "'Lobster', sans-serif", name: "Lobster" },
            { value: "'Tajawal', sans-serif", name: "Tajawal" },
            { value: "'Vollkorn', sans-serif", name: "Vollkorn" },
            { value: "'Open Sans', sans-serif", name: "Open Sans" },
            { value: "'Cairo', sans-serif", name: "Cairo" },
            { value: "'Almarai', sans-serif", name: "Almarai" },
            { value: "'Changa', sans-serif", name: "Changa" },
            { value: "'Lareza', sans-serif", name: "Lareza" },
            { value: "'Noto Sans Arabic', sans-serif", name: "Noto Sans Arabic" },
            {
                value: "'IBM Plex Sans Arabic', sans-serif",
                name: "IBM Plex Sans Arabic",
            },
            { value: "'Lato', sans-serif", name: "Lato" },
            { value: "'Rockwell', sans-serif", name: "Rockwell" },
        ]);

        const tm = editor.TraitManager;
        // Select trait that maps a class list to the select options.
        // The default select option is set if the input has a class, and class list is modified when select value changes.
        tm.addType("class_select", {
            events: {
                change: "onChange", // trigger parent onChange method on input change
            },
            createInput({ trait }) {
                const md = this.model;
                const opts = md.get("options") || [];
                const input = document.createElement("select");
                const target_view_el = this.target.view.el;

                editor.StorageManager.get("remote").set({
                    params: { variables: JSON.stringify(variables) },
                });
                for (let i = 0; i < opts.length; i++) {
                    const option = document.createElement("option");
                    let value = opts[i].value;
                    if (value === "") {
                        value = "GJS_NO_CLASS";
                    } // 'GJS_NO_CLASS' represents no class--empty string does not trigger value change
                    option.text = opts[i].name;
                    option.value = value;
                    option.className = value;

                    // Convert the Token List to an Array
                    const css = Array.from(target_view_el.classList);

                    const value_a = value.split(" ");
                    const intersection = css.filter((x) => value_a.includes(x));

                    if (intersection.length === value_a.length) {
                        option.setAttribute("selected", "selected");
                    }
                    input.append(option);
                }
                return input;
            },
            onUpdate({ elInput, component }) {
                const classes = component.getClasses();
                const opts = this.model.get("options") || [];
                for (let i = 0; i < opts.length; i++) {
                    let value = opts[i].value;
                    if (value && classes.includes(value)) {
                        elInput.value = value;
                        return;
                    }
                }
                elInput.value = "GJS_NO_CLASS";
            },

            onEvent({ elInput, component, event }) {
                const classes = this.model.get("options").map((opt) => opt.value);
                for (let i = 0; i < classes.length; i++) {
                    if (classes[i].length > 0) {
                        const classes_i_a = classes[i].split(" ");
                        for (let j = 0; j < classes_i_a.length; j++) {
                            if (classes_i_a[j].length > 0) {
                                component.removeClass(classes_i_a[j]);
                            }
                        }
                    }
                }
                const value = this.model.get("value");
                // This piece of code removes the empty attribute name from attributes list
                const elAttributes = component.attributes.attributes;
                delete elAttributes[""];

                if (value.length > 0 && value !== "GJS_NO_CLASS") {
                    const value_a = value.split(" ");
                    for (let i = 0; i < value_a.length; i++) {
                        component.addClass(value_a[i]);
                    }
                }
                component.em.trigger("component:toggled");
            },
        });

        tm.addType("label", {
            createInput({ trait }) {
                const input = document.createElement("div");
                const target_view_el = this.target.view.el;
                const label = trait.attributes.label;

                input.innerHTML = label;

                return input;
            },
            onUpdate({ elInput, component }) { },

            onEvent({ elInput, component, event }) { },
        });
        //-------------------------------------------//
    });

    //show modal save..
    editor.Panels.addButton("options", [
        {
            id: "save",
            className: "fa fa-floppy-o icon-blank",
            command: function (editor1, sender) {
                editor.store();
            },
            attributes: { title: "Save Landing Page" },
        },
    ]);
    editor.Panels.addButton("options", [
        {
            id: "less",
            className: "fa fa-tint icon-blank open-modal",
            command: "",
            attributes: { title: "Select Theme Colors" },
        },
    ]);

    // Add Settings Sector
    var traitsSector = $(
        '<div class="gjs-sm-sector no-select">' +
        '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
        '<div class="gjs-sm-properties" style="display: none;"></div></div>'
    );
    var traitsProps = traitsSector.find(".gjs-sm-properties");
    traitsProps.append($(".gjs-trt-traits"));
    $(".gjs-sm-sectors").before(traitsSector);
    traitsSector.find(".gjs-sm-title").on("click", function () {
        var traitStyle = traitsProps.get(0).style;
        var hidden = traitStyle.display == "none";
        if (hidden) {
            traitStyle.display = "block";
        } else {
            traitStyle.display = "none";
        }
    });

    //-------------------------------------------//
    $(".open-modal").click(function () {
        $("#less-modal").modal("show");
    });

    $(".color-inputs").on("input", function () {
        let varName = $(this).attr("var");
        let varValue = $(this).val();
        variables[varName] = varValue;
        var f = $(".gjs-frame");

        f.get(0).contentWindow.firstInputColor(variables);
        less.modifyVars(variables);
        editor.StorageManager.get("remote").set({
            params: { variables: JSON.stringify(variables) },
        });
    });

    $(".hexcolor").on("input", function () {
        let varName = $(this).attr("var");
        let varValue = $(this).val();
        variables[varName] = varValue;
        var f = $(".gjs-frame");
        f.get(0).contentWindow.firstInputColor(variables);
        less.modifyVars(variables);
        editor.StorageManager.get("remote").set({
            params: { variables: JSON.stringify(variables) },
        });
    });

    //   editor.on("run", (commandId) => {
    //     console.log("Run", commandId);
    //   });

    //   editor.on("stop", (commandId) => {
    //     console.log("Stop", commandId);
    //   });
});

function voila_form(editor) {
    $.get(
        $_SITE + "/admin/forms/all-forms?landing_page_id=" + $id,
        function (data) {
            data.forEach((e) => {
                editor.DomComponents.addType("voila_form" + e.id, {
                    // Make the editor understand when to bind `my-input-type`
                    isComponent: (el) => el.tagName === "form",
                    // Model definition
                    model: {
                        // Default properties
                        defaults: {
                            tagName: "form",
                            draggable: "*", // Can be dropped only inside `form` elements
                            droppable: false, // Can't drop other elements inside
                        },
                    },
                });

                var blockManager = editor.BlockManager;
                //----------------------------------------------------------//
                blockManager.add("voila_form" + e.id, {
                    activate: true,
                    category: "Voila Forms",
                    label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
              <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
              <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
          </svg>
          <p>${e.name}</p>
          `,
                    content: e.html,
                });
                //----------------------------------------------------------//
                blockManager.add("voila_form_column" + e.id, {
                    activate: true,
                    category: "Voila Forms",
                    label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
              <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
              <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
          </svg>
          <p>${e.name} - Two Columns</p>
          `,
                    content: `
          <div class="row flex-lg-row-reverse">
              <div class="col-10 col-sm-8 col-lg-6">
                  ${e.html}
              </div>
              <div class="col-lg-6 center-all text-center">
                  <h1 class="display-5">Responsive left-aligned hero with image</h1>
                  <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful
                      JavaScript plugins.</p>
                  <div class="d-grid gap-2 d-md-flex left-all text-left">
                  <a href="#formLandingPage" style="color:white" class="second-background btn-voila-custom btn-lg">
                  Primary
              </a>
                  </div>
              </div>
          </div>
          `,
                });
            });
            //-------------------------------------------//
            editor.DomComponents.addType("select", {
                model: {
                    defaults: {
                        tagName: "select",
                    },
                },
                isComponent(el) {
                    if (el.tagName === "SELECT") {
                        return { type: "select" };
                    }
                },
            });
            //-------------------------------------------//
        }
    );
}

function bootstrap_blocks(editor) {
    //--------------------------------------------------------------------//
    editor.DomComponents.addType("container", {
        model: {
            // Default properties
            defaults: {
                tagName: "div",
                draggable: "*", // Can be dropped only inside `form` elements
                droppable: false, // Can't drop other elements inside
            },
        },
    });
    //--------------------------------------------------------------------//
    editor.DomComponents.addType("row", {
        model: {
            // Default properties
            defaults: {
                tagName: "div",
                draggable: "*", // Can be dropped only inside `form` elements
                droppable: false, // Can't drop other elements inside
            },
        },
    });
    //--------------------------------------------------------------------//
    editor.DomComponents.addType("column", {
        model: {
            // Default properties
            defaults: {
                tagName: "div",
                draggable: "row", // Can be dropped only inside `form` elements
                droppable: false, // Can't drop other elements inside
            },
        },
    });
    //--------------------------------------------------------------------//
    var blockManager = editor.BlockManager;
    //----------------------------------------------------------//
    blockManager.add("container", {
        activate: true,
        category: "Layout",
        label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
                <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
                <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
            </svg>
            <p>Container</p>
            `,
        content: {
            tagName: "div",
            draggable: true,
            content: '<div class="container"></div>',
        },
    });
    //----------------------------------------------------------//
    blockManager.add("row", {
        activate: true,
        category: "Layout",
        label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
                <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
                <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
            </svg>
            <p>Row</p>
            `,
        content: {
            tagName: "div",
            draggable: true,
            components: [
                {
                    tagName: "div",
                    content: '<div class="row"></div>',
                },
            ],
        },
    });
    //----------------------------------------------------------//
}

function additional_basic_blocks(editor) {
    //--------------------------------------------------------------------//
    var blockManager = editor.BlockManager;
    //----------------------------------------------------------//
    let basicNav = `
        <nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="https://voila.digital/images/logo.png" width="200" height="50" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>`;
    //----------------------------------------------------------//
    blockManager.add("basic-nav", {
        activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/basic_nav1.svg" alt=""><div class="draggable"></div><br> Basic Nav',
        content: basicNav,
    });

    //----------------------------------------------------------//
    let fixedNav = `
        <nav class="navbar navbar-expand-md fixed-top" aria-label="Fourth navbar example">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="https://voila.digital/images/logo.png" width="200" height="50" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>`;
    //----------------------------------------------------------//
    blockManager.add("fixed-nav", {
        activate: true,
        order: 1,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/basic_nav1.svg" alt=""><div class="draggable"></div><br> Fixed Nav',
        content: fixedNav,
    });
    //----------------------------------------------------------//
    let nav1 = `
    <nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
        <div class="container">

            <div class="col">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </div>
            <div class="col text-right">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </div>

        </div>
    </nav>`;
    //----------------------------------------------------------//
    blockManager.add("nav_ltr", {
        // activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_2_logos.svg" alt=""><div class="draggable"></div><br> Navigation 2 Logos',
        content: nav1,
    });
    //----------------------------------------------------------//
    let navLogos3 = `
    <nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
        <div class="container">

            <div class="col">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </div>
            <div class="col text-center">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </div>
            <div class="col text-right">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </div>

        </div>
    </nav>`;
    //----------------------------------------------------------//
    blockManager.add("nav3_ltr", {
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/logos3.svg" alt=""><div class="draggable"></div><br> Navigation 3 Logos',
        content: navLogos3,
    });
    //----------------------------------------------------------//
    let nav3 = `
    <nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
        <div class="container">
        <div class="col">
           <img src="https://voila.digital/images/logo.png" width="200" height="50" />
        </div>
        <div class="col text-right">
           <img src="https://voila.digital/images/logo.png" width="200" height="50" />
        </div>
        </div>
    </nav>`;
    //----------------------------------------------------------//
    blockManager.add("nav_rtl", {
        // activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_2_logos.svg" alt=""><div class="draggable"></div><br> Navigation 2 Logos (Arabic)',
        content: nav3,
    });
    //----------------------------------------------------------//
    let nav2 = `
    <nav class="navbar navbar-expand-md " aria-label="Fourth navbar example">
        <div class="container">
            <a href="#" class="navbar-brand me-auto">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </a>
            <div class="row">
                <div class="col-lg-6 center-all text-center">
                    <div class="row">
                        <div class="col-sm-3 center-all text-center">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="col-sm-9 flex flex-direction-column">
                            <strong>Contact us</strong>
                            <a href="mailto:info@voila.digital">info@voila.digital</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 center-all text-center">
                    <div class="row">
                        <div class="col-sm-3 center-all text-center">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="col-sm-9 flex flex-direction-column">
                            <strong>Call us</strong>
                            <a href="tel:00966122619667">00966122619667</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>`;
    //----------------------------------------------------------//
    blockManager.add("nav2_ltr", {
        // activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_logo_contact.svg" alt=""><div class="draggable"></div><br> Navigation with Contact',
        content: nav2,
    });
    //----------------------------------------------------------//
    nav2 = `
    <nav class="navbar navbar-expand-md " aria-label="Fourth navbar example">
        <div class="container">
            <a href="#" class="navbar-brand ms-auto">
                <img src="https://voila.digital/images/logo.png" width="200" height="50" />
            </a>
            <div class="row">
                <div class="col-lg-6 center-all text-center">
                    <div class="row">
                        <div class="col-sm-3 center-all text-center">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="col-sm-9 flex flex-direction-column">
                            <strong>Contact us</strong>
                            <a href="mailto:info@voila.digital">info@voila.digital</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 center-all text-center">
                    <div class="row">
                        <div class="col-sm-3 center-all text-center">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="col-sm-9 flex flex-direction-column">
                            <strong>Call us</strong>
                            <a href="tel:00966122619667">00966122619667</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>`;
    //----------------------------------------------------------//
    blockManager.add("nav2_rtl", {
        // activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_logo_contact.svg" alt=""><div class="draggable"></div><br> Navigation with Contact',
        content: nav2,
    });
    //----------------------------------------------------------//
    unorderedList = `
    <div>
        <h2>Introducing the unified dell workspace</h2>
        <ul>
            <li>Gains from implementing Dell Technologies Unified Workspace offset hardware and IT costs</li>
            <li>Modern factory provisioning and reduce deployment time by 50%</li>
            <li>Comprehensive endpoint security above and below the OS</li>
            <li>Unified management from one console of your choice</li>
            <li>Predictive, proactive support to reduce support calls by up to 46% and resolve issues 6x faster</li>
        </ul>
    </div>`;
    //----------------------------------------------------------//
    blockManager.add("unorderedList", {
        order: 0,
        category: "Basic",
        label: "Unordered List",
        content: unorderedList,
    });

    //----------------------------------------------------------//
    // blockManager.add('custom-button', {
    //     // activate: true,
    //     order: 0,
    //     category: "Basic",
    //     label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    //             <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
    //             <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
    //             <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
    //         </svg>
    //         <p>Button</p>
    //         `,
    //     content: `<button class="btn" type="button">Go To</button>`
    // });
    //----------------------------------------------------------//
    const defaultType = editor.DomComponents.getType("default");
    const defaultModel = defaultType.model;
    const defaultView = defaultType.view;
    editor.DomComponents.addType("custom-icon", {
        model: defaultModel.extend(
            {
                defaults: Object.assign({}, defaultModel.prototype.defaults, {
                    tagName: "span",
                    type: "custom-icon",
                    content: ``,
                    attributes: {
                        // Default attributes
                        icon_type: { value: "fa fa-phone", name: "Phone" },
                    },
                    traits: [
                        {
                            type: "class_select",
                            name: "icon_type",
                            label: "Icon Type",
                            options: [
                                ...iconsArr.map(function (v) {
                                    return { value: v, name: capitalize(v) };
                                }),
                            ],
                        },
                        {
                            type: "class_select",
                            name: "icon_size",
                            label: "Icon Size",
                            options: [
                                { name: "Large", value: "fa-lg" },
                                { name: "Extra Large", value: "fa-2x" },
                                { name: "Unset", value: "" },
                                { name: "Small", value: "fa-sm" },
                                { name: "Extra Small", value: "fa-xs" },
                            ],
                        },
                    ],
                }),
            },
            {
                isComponent(el) {
                    if (
                        el &&
                        el.classList &&
                        (el.classList.contains("icon") || el.classList.contains("fa"))
                    ) {
                        return { type: "custom-icon" };
                    }
                },
            }
        ),
        view: defaultView,
    });
    //----------------------------------------------------------//
    // blockManager.add('custom-icon').set({
    //     label: `
    //         <div>Icons</div>
    //     `,
    //     category: 'Basic',
    //     content: {
    //         type: 'custom-icon',
    //         classes: ['fa', "fa-lg", "icon"]
    //     }
    // });
    //----------------------------------------------------------//
}

function features(editor) {
    var blockManager = editor.BlockManager;
    //-----------------------------------------------------------//
    let featureImage = `
    <div>
    <div class="container">
        <div class="row flex-lg-row-reverse center-all text-center">
            <div class="col-10 col-sm-8 col-lg-6 center-all text-center">
                <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="d-block ms-auto me-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            </div>
            <div class="col-lg-6 center-all text-center">
                <h1 class="display-5">Responsive left-aligned hero with image</h1>
                <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful
                    JavaScript plugins.</p>
                <div class="d-grid gap-2 d-md-flex left-all text-left">
                <a href="#formLandingPage" style="color:white" class="second-background btn-voila-custom btn-lg">
                Primary
             </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("feature-image", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/feature_image.svg" alt=""><div class="draggable"></div><br> Feature with Image',
        content: featureImage,
    });
    //-----------------------------------------------------------//
    let featureImage1 = `
    <div>
    <div class="container">
        <div class="row flex-lg-row-reverse center-all text-center">
            <div class="col-lg-6 center-all text-center">
                <h1 class="display-5">Responsive left-aligned hero with image</h1>
                <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful
                    JavaScript plugins.</p>
                <div class="d-grid gap-2 d-md-flex left-all text-left">
                <a href="#formLandingPage" style="color:white" class="second-background btn-voila-custom btn-lg">
                Primary
             </a>
                </div>
            </div>
            <div class="col-10 col-sm-8 col-lg-6 center-all text-center">
                <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="d-block ms-auto me-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            </div>
        </div>
    </div>
    </div>`;
    //----------------------------------------------------------//
    blockManager.add("feature-image1", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/feature_image_r.svg" alt=""><div class="draggable"></div><br> Feature with Image (Reverse)',
        content: featureImage1,
    });
    //----------------------------------------------------------//
    let feature3Images = `
    <style>
        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
            font-size: 2rem;
            color: #fff;
            border-radius: .75rem;
        }
    </style>
    <div>
    <div class="container">
        <h2 class=" center-all text-center">Columns with icons</h2>
        <div class="row g-4 row-cols-1 row-cols-lg-3">
            <div class="feature col center-all text-center">
                <div class="">
                <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">
                </div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                <a href="#formLandingPage" class="icon-link">
              Call to action
              <span class="fa fa-arrow-right fa-sm"></span>
            </a>
            </div>
            <div class="feature col center-all text-center">
                <div class="">
                <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">
                </div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                <a href="#formLandingPage" class="icon-link">
              Call to action
              <span class="fa fa-arrow-right fa-sm"></span>
            </a>
            </div>
            <div class="feature col center-all text-center">
                <div class="">
                <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">

                </div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                <a href="#formLandingPage" class="icon-link">
              Call to action
              <span class="fa fa-arrow-right fa-sm"></span>

            </a>
            </div>
        </div>
    </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("feature-3-images", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/3_features_img.svg" alt=""><div class="draggable"></div><br> 3 Feature (Images)',
        content: feature3Images,
    });
    //----------------------------------------------------------//
    let featuresCards = `
    <div>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 flex-wrap-wrap center-all text-center">
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                              View
                            </a>
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                               Edit
                            </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("feature-cards", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/features_cards.svg" alt=""><div class="draggable"></div><br> Features Cards',
        content: featuresCards,
    });
    //----------------------------------------------------------//
    let section1 = `
    <div>
     <div class="container">
      <div class="row g-4 row-cols-1 row-cols-lg-3 center-all text-center">
       <div class="col">
        <div class="row center-all text-center">
         <div class="col-4">
          <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
         </div>
         <div class="col-8">
            Advanced security
         </div>
        </div>
       </div>
       <div class="col">
        <div class="row center-all text-center">
         <div class="col-4">
          <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
         </div>
         <div class="col-8">
           Access and data control
         </div>
        </div>
       </div>
       <div class="col">
         <div class="row center-all text-center">
           <div class="col-4">
             <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
           </div>
           <div class="col-8">
              Cyberthreat protection
            </div>
         </div>
       </div>
      </div>
     </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("section1", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/icons_titles.svg" alt=""><div class="draggable"></div><br> 3 Icons with Title',
        content: section1,
    });
    //----------------------------------------------------------//
    let featureVideo = `
    <section id="hero" class="hero d-flex center-all text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 hero-img center-all text-center">
                    <video controls></video>
                </div>
                <div class="col-lg-6 d-flex flex-column center-all text-center">
                    <h1>We offer modern solutions for growing your business</h1>
                    <h2>We are team of talented designers making websites with Bootstrap</h2>
                    <div class="center-all text-center">
                        <div class="center-all text-center">
                            <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                                  Get Started
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    `;
    //----------------------------------------------------------//
    blockManager.add("featureVideo", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/video.svg" alt=""><div class="draggable"></div><br> Video with Text',
        content: featureVideo,
    });
    //----------------------------------------------------------//
    let iconsWithImg = `
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
            </div>
            <div class="col-lg-6">
                <div>
                    <h3>Test</h3>
                </div>
                <div class="d-flex">
                    <div class="col-2">
                        <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                    </div>
                    <div class="col-10 ps-4">
                        <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-2">
                        <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                    </div>
                    <div class="col-10 ps-4">
                        <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-2">
                        <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                    </div>
                    <div class="col-10 ps-4">
                        <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                    </div>
                </div>
                <div class="text-center">
                    <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                        Get Started
                    </a>
                </div>
            </div>


        </div>
    </div>
</section>
    `;
    //----------------------------------------------------------//
    blockManager.add("iconsWithImg", {
        activate: true,
        category: "Features",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/icons_with_link.svg" alt=""><div class="draggable"></div><br> Image with Icons',
        content: iconsWithImg,
    });
    //----------------------------------------------------------//
}

function headings(editor) {
    let blockManager = editor.BlockManager;
    //----------------------------------------------------------//
    let heading1 = `
    <div>
    <div class="container">
    <div class="center-all">
        <img class="d-block center-all text-center" src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="display-5 fw text-center">Centered hero</h1>
        <div class="col-lg-6 ms-auto me-auto center-all text-center">
            <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript
                plugins.
            </p>
            <div class="d-grid gap-2 d-sm-flex">
            <a href="#formLandingPage" type="button" class="second-background btn-voila-custom btn-lg gap-3">
                Primary button
            </a>
            </div>
        </div>
    </div>
    </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("heading1", {
        activate: true,
        category: "Headings",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/heading1.svg" alt=""><div class="draggable"></div><br> Heading1',
        content: heading1,
    });
    //----------------------------------------------------------//
    let heading2 = `
    <section id="hero" class="hero d-flex center-all text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column center-all text-center">
                    <h1>We offer modern solutions for growing your business</h1>
                    <h2>We are team of talented designers making websites with Bootstrap</h2>
                    <div class="center-all text-center">
                        <div class="center-all text-center">
                            <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                                  Get Started
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-img center-all text-center">
                    <img src="https://bootstrapmade.com/demo/templates/FlexStart/assets/img/hero-img.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>
    `;
    //----------------------------------------------------------//
    blockManager.add("heading2", {
        activate: true,
        category: "Headings",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/heading2.svg" alt=""><div class="draggable"></div><br> Heading2',
        content: heading2,
    });
    //----------------------------------------------------------//
}

function footers(editor) {
    let blockManager = editor.BlockManager;
    let footer1 = `
    <div>
    <div class="container">
        <div class="col-sm-12 center-all text-center">
            <p class="address center-all text-center">HAS HO Building, Second Floor - Mohammed Ibn Abdul Aziz St. </p>
            <p class="contact-us center-all text-center">Contact Us: +966 12 261 9667</p>
            <div class="social center-all text-center">
                <a target="_blank" class="text-decoration-none" href="https://twitter.com/Voila_digital"> <span class="fa fa-twitter-square fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.facebook.com/voila2006">
                    <span class="fa fa-facebook-square fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.youtube.com/channel/UC4-HB822j9rNrxiVrErrz-Q">
                    <span class="fa fa-youtube-square fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.linkedin.com/company/voila">
                    <span class="fa fa-linkedin fa-2x"></span> </a>
            </div>
        </div>
    </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("footer1", {
        activate: true,
        category: "Footers",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/footer.svg" alt=""><div class="draggable"></div><br> Footer1',
        content: footer1,
    });
    //----------------------------------------------------------//
    let footer2 = `
    <div style="background-color:#262627">
        <div class="col-sm-12 center-all text-center">
            <div class="social center-all text-center">
                <a target="_blank" href="https://twitter.com/ctelecoms2007"
                    class="text-decoration-none">
                    <span class="fa fa-twitter-square fa-2x first-color m-2"></span>
                </a>
                <a target="_blank" href="https://www.facebook.com/pages/Ctelecoms/1567811026830567?ref=hl" class="text-decoration-none">
                    <span class="fa fa-facebook-square fa-2x first-color m-2"></span>
                </a>
                <a target="_blank" href="https://www.youtube.com/channel/UCLgRAm3ywVYwIK5I4N4jBGQ" class="text-decoration-none">
                    <span class="fa fa-youtube-square fa-2x first-color m-2"></span>
                </a>
                <a target="_blank" href="https://www.linkedin.com/company/ctelecoms" class="text-decoration-none">
                    <span class="fa fa-linkedin fa-2x first-color m-2"></span>
                </a>
            </div>
        </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("footer2", {
        activate: true,
        category: "Footers",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/footer.svg" alt=""><div class="draggable"></div><br> Footer2',
        content: footer2,
    });
}

function voila_button(editor) {
    let blockManager = editor.BlockManager;
    let customButtonLink = `
    <div>
        <div class="container">
            <div class="row">
                <div class="center-all text-center">
                    <a href="#formLandingPage" class="second-background btn-voila-custom btn-lg">
                        Button
                    </a>
                </div>
            </div>
        </div>
    </div>
    `;
    //----------------------------------------------------------//
    blockManager.add("customButtonLink", {
        activate: true,
        category: "voila Button",
        label: `
            <p>Custom Button</p>
            `,
        content: customButtonLink,
    });
}

function voila_theme(editor) {
    editor.DomComponents.getTypes().map((type) => {
        // let defaultTraits = type.model.prototype.defaults.traits
        let traitArr = [
            {
                type: "label",
                label: "Main Settings",
            },
            {
                type: "text",
                label: "ID",
                name: "id",
            },
            {
                type: "label",
                label: "Theme",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "first-color", name: "First Color" },
                    { value: "second-color", name: "Second Color" },
                    { value: "third-color", name: "Third Color" },
                    { value: "fourth-color", name: "Fourth Color" },
                ],
                label: "Text Color",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "first-background", name: "First Color" },
                    { value: "second-background", name: "Second Color" },
                    { value: "third-background", name: "Third Color" },
                    { value: "fourth-background", name: "Fourth Color" },
                ],
                label: "Background Color",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "text-uppercase", name: "Uppercase" },
                    { value: "text-lowercase", name: "Lowercase" },
                    { value: "text-capitalize", name: "Capitalize" },
                ],
                label: "Text Transform",
            },
            {
                type: "label",
                label: "Alignment",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "center-all", name: "Center" },
                    { value: "right-all", name: "Right" },
                    { value: "left-all", name: "Left" },
                ],
                label: "Block Alignment",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "text-center", name: "Center" },
                    { value: "text-right", name: "Right" },
                    { value: "text-left", name: "Left" },
                ],
                label: "Text Alignment",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "self-center", name: "Center" },
                    { value: "self-right", name: "Right" },
                    { value: "self-left", name: "Left" },
                ],
                label: "Self Alignment",
            },
            {
                type: "label",
                label: "Direction",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "flex-lg-row-reverse", name: "Reverse Direction" },
                    { value: "flex-lg-row", name: "Default Direction" },
                ],
                label: "Block Direction",
            },
            {
                type: "label",
                label: "Outer Spacing (Margin)",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "mt-0", name: "0" },
                    { value: "mt-1", name: "1" },
                    { value: "mt-2", name: "2" },
                    { value: "mt-3", name: "3" },
                    { value: "mt-4", name: "4" },
                    { value: "mt-5", name: "5" },
                    { value: "mt-auto", name: "Auto" },
                ],
                label: "Top",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "mb-0", name: "0" },
                    { value: "mb-1", name: "1" },
                    { value: "mb-2", name: "2" },
                    { value: "mb-3", name: "3" },
                    { value: "mb-4", name: "4" },
                    { value: "mb-5", name: "5" },
                    { value: "mb-auto", name: "Auto" },
                ],
                label: "Bottom",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "me-0", name: "0" },
                    { value: "me-1", name: "1" },
                    { value: "me-2", name: "2" },
                    { value: "me-3", name: "3" },
                    { value: "me-4", name: "4" },
                    { value: "me-5", name: "5" },
                    { value: "me-auto", name: "Auto" },
                ],
                label: "Right",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "ms-0", name: "0" },
                    { value: "ms-1", name: "1" },
                    { value: "ms-2", name: "2" },
                    { value: "ms-3", name: "3" },
                    { value: "ms-4", name: "4" },
                    { value: "ms-5", name: "5" },
                    { value: "ms-auto", name: "Auto" },
                ],
                label: "Left",
            },
            {
                type: "label",
                label: "Inner Spacing (Padding)",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "pt-0", name: "0" },
                    { value: "pt-1", name: "1" },
                    { value: "pt-2", name: "2" },
                    { value: "pt-3", name: "3" },
                    { value: "pt-4", name: "4" },
                    { value: "pt-5", name: "5" },
                ],
                label: "Top",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "pb-0", name: "0" },
                    { value: "pb-1", name: "1" },
                    { value: "pb-2", name: "2" },
                    { value: "pb-3", name: "3" },
                    { value: "pb-4", name: "4" },
                    { value: "pb-5", name: "5" },
                ],
                label: "Bottom",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "pe-0", name: "0" },
                    { value: "pe-1", name: "1" },
                    { value: "pe-2", name: "2" },
                    { value: "pe-3", name: "3" },
                    { value: "pe-4", name: "4" },
                    { value: "pe-5", name: "5" },
                ],
                label: "Right",
            },
            {
                type: "class_select",
                options: [
                    { value: "", name: "None" },
                    { value: "ps-0", name: "0" },
                    { value: "ps-1", name: "1" },
                    { value: "ps-2", name: "2" },
                    { value: "ps-3", name: "3" },
                    { value: "ps-4", name: "4" },
                    { value: "ps-5", name: "5" },
                ],
                label: "Left",
            },
        ];
        if (type.id == "link") {
            traitArr.unshift({
                type: "text",
                label: "Href",
                name: "href",
            });
        }

        if (type.id == "input") {
            traitArr.unshift({
                type: "text",
                label: "Placeholder",
                name: "placeholder",
            });
            traitArr.unshift({
                type: "text",
                label: "Value",
                name: "value",
            });
        }

        editor.DomComponents.addType(type.id, {
            model: type.model.extend({
                defaults: {
                    ...type.model.prototype.defaults,
                    traits: traitArr,
                },
                view: type.view,
            }),
        });
    });
}

const capitalize = (phrase) => {
    return phrase
        .replaceAll("fa fa-", "")
        .replaceAll("far fa-", "")
        .replaceAll("fas fa-", "")
        .replaceAll("fab fa-", "")
        .toLowerCase()
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

function responsive_filemanager_callback(field_id, value) {
    $("#" + field_id, $(".gjs-frame").contents()).prop("src", value);
    if (editor.getSelected().attributes.tagName == 'div')
        editor.getSelected().addStyle({ 'background-image': `url("${$_SITE + value}")` })
    else
        editor.getSelected().set("src", $_SITE + value);
    editor.stopCommand("open-assets");
}
