function define_new_traits(editor) {
    //Define a new trait type class_select.
    editor.TraitManager.addType("class_select", {
        events: {
            change: "onChange",
        },
        createInput({ trait }) {
            const md = this.model;
            const opts = md.get("options") || [];
            const input = document.createElement("select");
            const target_view_el = this.target.view.el;

            for (let i = 0; i < opts.length; i++) {
                const option = document.createElement("option");
                let value = opts[i].value;
                if (value === "") {
                    value = "GJS_NO_CLASS";
                }
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

    //label trait (to put new labels in the settings tab).
    editor.TraitManager.addType("label", {
        createInput({ trait }) {
            const input = document.createElement("div");
            const label = trait.attributes.label;
            input.innerHTML = label;
            return input;
        },
        onUpdate({ elInput, component }) { },
        onEvent({ elInput, component, event }) { },
    });



    // editor.TraitManager.addType("editor-change-content", {
    //     noLabel: true,
    //     createInput({ trait }) {
    //         var input = document.createElement("textarea");
    //         input.setAttribute("class", "editor-change-content");
    //         input.value = this.target.view.el.innerHTML;
    //         return input;
    //     },
    //     //update the text value in the canvas when the value in the new trait is updated.
    //     onUpdate: function (model, value, opts) {
    //         this.target.view.el.innerHTML = this.model.get("value");
    //     },
    //     onEvent({ elInput, component, event }) {}
    // });

    // //load the tinymce text editor configuration on open-tm command.
    // editor.on("run:open-tm", () => {
    //     tinymce.remove();

    //     tinymce.init({
    //         selector: ".editor-change-content",
    //         height: 300,
    //         width: "100%",
    //         plugins: [
    //             "advlist",
    //             "autolink",
    //             "lists",
    //             "link",
    //             "image",
    //             "charmap",
    //             "preview",
    //             "anchor",
    //             "searchreplace",
    //             "visualblocks",
    //             "fullscreen",
    //             "insertdatetime",
    //             "media",
    //             "table",
    //             "help",
    //             "wordcount",
    //         ],
    //         menubar:false,
    //         statusbar: false,
    //         fullscreen_native: true,
    //         toolbar: "undo redo | casechange blocks | bold italic backcolor | " +
    //             "alignleft aligncenter alignright alignjustify | " +
    //             "bullist numlist outdent indent | removeformat | code table help",
    //         init_instance_callback: function (tinymceEditor) {
    //             tinymceEditor.on("Change", function (e) {
    //                 editor.getSelected().getTrait("change-content").setValue(tinymceEditor.getContent());
    //             });
    //         },
    //     });
    // });
}

//put the new defined traits in use so we created new inputs using them.
function traits(editor) {
    editor.DomComponents.getTypes().map((type) => {
        let traitArr = [{
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
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "first-color",
                name: "First Color",
            },
            {
                value: "second-color",
                name: "Second Color",
            },
            {
                value: "third-color",
                name: "Third Color",
            },
            {
                value: "fourth-color",
                name: "Fourth Color",
            },
            ],
            label: "Text Color",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "first-background",
                name: "First Color",
            },
            {
                value: "second-background",
                name: "Second Color",
            },
            {
                value: "third-background",
                name: "Third Color",
            },
            {
                value: "fourth-background",
                name: "Fourth Color",
            },
            ],
            label: "Background Color",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "text-uppercase",
                name: "Uppercase",
            },
            {
                value: "text-lowercase",
                name: "Lowercase",
            },
            {
                value: "text-capitalize",
                name: "Capitalize",
            },
            ],
            label: "Text Transform",
        },
        {
            type: "label",
            label: "Alignment",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "center-all",
                name: "Center",
            },
            {
                value: "right-all",
                name: "Right",
            },
            {
                value: "left-all",
                name: "Left",
            },
            ],
            label: "Block Alignment",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "text-center",
                name: "Center",
            },
            {
                value: "text-right",
                name: "Right",
            },
            {
                value: "text-left",
                name: "Left",
            },
            ],
            label: "Text Alignment",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "self-center",
                name: "Center",
            },
            {
                value: "self-right",
                name: "Right",
            },
            {
                value: "self-left",
                name: "Left",
            },
            ],
            label: "Self Alignment",
        },
        {
            type: "label",
            label: "Direction",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "flex-lg-row-reverse",
                name: "Reverse Direction",
            },
            {
                value: "flex-lg-row",
                name: "Default Direction",
            },
            ],
            label: "Block Direction",
        },
        {
            type: "label",
            label: "Outer Spacing (Margin)",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "mt-0",
                name: "0",
            },
            {
                value: "mt-1",
                name: "1",
            },
            {
                value: "mt-2",
                name: "2",
            },
            {
                value: "mt-3",
                name: "3",
            },
            {
                value: "mt-4",
                name: "4",
            },
            {
                value: "mt-5",
                name: "5",
            },
            {
                value: "mt-auto",
                name: "Auto",
            },
            ],
            label: "Top",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "mb-0",
                name: "0",
            },
            {
                value: "mb-1",
                name: "1",
            },
            {
                value: "mb-2",
                name: "2",
            },
            {
                value: "mb-3",
                name: "3",
            },
            {
                value: "mb-4",
                name: "4",
            },
            {
                value: "mb-5",
                name: "5",
            },
            {
                value: "mb-auto",
                name: "Auto",
            },
            ],
            label: "Bottom",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "me-0",
                name: "0",
            },
            {
                value: "me-1",
                name: "1",
            },
            {
                value: "me-2",
                name: "2",
            },
            {
                value: "me-3",
                name: "3",
            },
            {
                value: "me-4",
                name: "4",
            },
            {
                value: "me-5",
                name: "5",
            },
            {
                value: "me-auto",
                name: "Auto",
            },
            ],
            label: "Right",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "ms-0",
                name: "0",
            },
            {
                value: "ms-1",
                name: "1",
            },
            {
                value: "ms-2",
                name: "2",
            },
            {
                value: "ms-3",
                name: "3",
            },
            {
                value: "ms-4",
                name: "4",
            },
            {
                value: "ms-5",
                name: "5",
            },
            {
                value: "ms-auto",
                name: "Auto",
            },
            ],
            label: "Left",
        },
        {
            type: "label",
            label: "Inner Spacing (Padding)",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "pt-0",
                name: "0",
            },
            {
                value: "pt-1",
                name: "1",
            },
            {
                value: "pt-2",
                name: "2",
            },
            {
                value: "pt-3",
                name: "3",
            },
            {
                value: "pt-4",
                name: "4",
            },
            {
                value: "pt-5",
                name: "5",
            },
            ],
            label: "Top",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "pb-0",
                name: "0",
            },
            {
                value: "pb-1",
                name: "1",
            },
            {
                value: "pb-2",
                name: "2",
            },
            {
                value: "pb-3",
                name: "3",
            },
            {
                value: "pb-4",
                name: "4",
            },
            {
                value: "pb-5",
                name: "5",
            },
            ],
            label: "Bottom",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "pe-0",
                name: "0",
            },
            {
                value: "pe-1",
                name: "1",
            },
            {
                value: "pe-2",
                name: "2",
            },
            {
                value: "pe-3",
                name: "3",
            },
            {
                value: "pe-4",
                name: "4",
            },
            {
                value: "pe-5",
                name: "5",
            },
            ],
            label: "Right",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "ps-0",
                name: "0",
            },
            {
                value: "ps-1",
                name: "1",
            },
            {
                value: "ps-2",
                name: "2",
            },
            {
                value: "ps-3",
                name: "3",
            },
            {
                value: "ps-4",
                name: "4",
            },
            {
                value: "ps-5",
                name: "5",
            },
            ],
            label: "Left",
        },
        {
            type: "label",
            label: "Hide or Show",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "hide_on_desktop",
                name: "hide",
            },
            ],
            label: "Hide on Desktop",
        },
        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "hide_on_mobile",
                name: "hide",
            },
            ],
            label: "Hide on Mobile",
        },

        {
            type: "class_select",
            options: [{
                value: "",
                name: "None",
            },
            {
                value: "hide_on_tablet",
                name: "hide",
            },
            ],
            label: "Hide on Tablet",
        },
        ];

        if (type.id == "link") {
            traitArr.unshift({
                type: "label",
                label: "Link",
            }, {
                type: "text",
                label: "Href",
                name: "href",
            });
        }

        if (type.id == "col") {
            traitArr.unshift({
                type: "class_select",
                options: [{
                    value: "",
                    name: "None",
                },
                {
                    value: "col-sm-12",
                    name: "12/12",
                },
                {
                    value: "col-sm-11",
                    name: "11/12",
                },
                {
                    value: "col-sm-10",
                    name: "10/12",
                },
                {
                    value: "col-sm-9",
                    name: "9/12",
                },
                {
                    value: "col-sm-8",
                    name: "8/12",
                },
                {
                    value: "col-sm-7",
                    name: "7/12",
                },
                {
                    value: "col-sm-6",
                    name: "6/12",
                },
                {
                    value: "col-sm-5",
                    name: "5/12",
                },
                {
                    value: "col-sm-4",
                    name: "4/12",
                },
                {
                    value: "col-sm-3",
                    name: "3/12",
                },
                {
                    value: "col-sm-2",
                    name: "2/12",
                },
                {
                    value: "col-sm-1",
                    name: "1/12",
                },
                ],
                label: "Small Screen Size",
                name: "col_sm_class",
            });
            traitArr.unshift({
                type: "class_select",
                options: [{
                    value: "",
                    name: "None",
                },
                {
                    value: "col-md-12",
                    name: "12/12",
                },
                {
                    value: "col-md-11",
                    name: "11/12",
                },
                {
                    value: "col-md-10",
                    name: "10/12",
                },
                {
                    value: "col-md-9",
                    name: "9/12",
                },
                {
                    value: "col-md-8",
                    name: "8/12",
                },
                {
                    value: "col-md-7",
                    name: "7/12",
                },
                {
                    value: "col-md-6",
                    name: "6/12",
                },
                {
                    value: "col-md-5",
                    name: "5/12",
                },
                {
                    value: "col-md-4",
                    name: "4/12",
                },
                {
                    value: "col-md-3",
                    name: "3/12",
                },
                {
                    value: "col-md-2",
                    name: "2/12",
                },
                {
                    value: "col-md-1",
                    name: "1/12",
                },
                ],
                label: "Medium Screen Size",
                name: "col_md_class",
            });
            traitArr.unshift({
                type: "label",
                label: "Column",
            }, {
                type: "class_select",
                name: "col_lg_class",
                options: [{
                    value: "",
                    name: "None",
                },
                {
                    value: "col-lg-12",
                    name: "12/12",
                },
                {
                    value: "col-lg-11",
                    name: "11/12",
                },
                {
                    value: "col-lg-10",
                    name: "10/12",
                },
                {
                    value: "col-lg-9",
                    name: "9/12",
                },
                {
                    value: "col-lg-8",
                    name: "8/12",
                },
                {
                    value: "col-lg-7",
                    name: "7/12",
                },
                {
                    value: "col-lg-6",
                    name: "6/12",
                },
                {
                    value: "col-lg-5",
                    name: "5/12",
                },
                {
                    value: "col-lg-4",
                    name: "4/12",
                },
                {
                    value: "col-lg-3",
                    name: "3/12",
                },
                {
                    value: "col-lg-2",
                    name: "2/12",
                },
                {
                    value: "col-lg-1",
                    name: "1/12",
                },
                ],
                label: "Large Screen Size",
            });
        }

        if (type.id == "button") {
            traitArr.unshift({
                type: "label",
                label: "Button",
            }, {
                type: "text",
                label: "Value",
                name: "value",
            });
        }

        if (type.id == "Input") {
            traitArr.unshift({
                type: "label",
                label: "Input",
            }, {
                type: "text",
                label: "Placeholder",
                name: "placeholder",
            },
                {
                    type: "text",
                    label: "Value",
                    name: "value",
                }, {
                type: 'checkbox',
                label: "Required",
                name: 'required',
            }
            );

        }

        // if (type.id == "text" || type.id == "paragraph") {
        //     traitArr.unshift({
        //         type: "label",
        //         label: "Text Editor",
        //     }, {
        //         type: "editor-change-content",
        //         label: "Text Editor",
        //         name: "change-content"
        //     });
        // }

        editor.DomComponents.addType(type.id, {
            model: {
                defaults: {
                    traits: traitArr,
                },
            },
        });
    });
}