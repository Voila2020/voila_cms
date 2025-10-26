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
    //-------------------- icon-selector Select2 Type ---------------------------------//
    var select2Value;
    editor.TraitManager.addType('icon-selector', {
        createInput({ trait }) {
            const input = document.createElement('select');
            input.className = 'select2-dropdown';

            const options = trait.attributes.options || [];
            options.forEach(option => {
                const optionEl = document.createElement('option');
                optionEl.value = option.value;
                optionEl.text = option.name;
                input.appendChild(optionEl);
            });
            return input;
        },
        //--------------------
        onUpdate({ elInput, component, trait }) {
            setTimeout(() => {
                $(elInput).select2({
                    width: '100%',
                    templateResult: formatState,
                    templateSelection: formatState,
                });

                const traitValue = trait.getValue();
                if (traitValue) {
                    $(elInput).val(traitValue).trigger('change');
                    select2Value = traitValue;
                } else if (select2Value) {
                    $(elInput).val(select2Value).trigger('change');
                } else {
                    $(elInput).val("fa-cube").trigger('change');
                }

                $(elInput).off('change').on('change', () => {
                    const value = $(elInput).val();
                    const previousValue = trait.previousValue || '';

                    updateComponentClass(component, previousValue, value);

                    trait.previousValue = value;
                    trait.setValue(value);
                });
            }, 0);
        },
        //--------------------------------
        onEvent({ elInput, component, event, trait }) {
            if (event.type === 'change') {
                const value = $(elInput).val();
                const previousValue = trait.previousValue || '';

                updateComponentClass(component, previousValue, value);

                trait.previousValue = value;
                trait.setValue(value);
            }
        },
    });
    //--------------------------------
    editor.TraitManager.addType("tag_select", {
        events: {
            change: "onChange",
        },

        createInput({ trait }) {
            const component = this.target;
            const options = this.model.get("options") || [];
            const select = document.createElement("select");

            options.forEach((opt) => {
                const option = document.createElement("option");
                option.text = opt.name;
                option.value = opt.value;

                if (component.get("tagName") === opt.value) {
                    option.setAttribute("selected", "selected");
                }

                select.append(option);
            });

            return select;
        },

        onUpdate({ elInput, component }) {
            const value = component.get("tagName");
            elInput.value = value;
        },

        onEvent({ elInput, component, event }) {
            const newTag = elInput.value;

            if (newTag && component.get("tagName") !== newTag) {
                const content = component.get("content"); // preserve inner HTML
                component.set({ tagName: newTag, content }); // change the tag
            }
        },
    });


    editor.TraitManager.addType("list_style_select", {
        createInput({ trait }) {
            const input = document.createElement("select");
            const options = [
                { value: "none", name: "None" },
                { value: "disc", name: "Disc" },
                { value: "circle", name: "Circle" },
                { value: "square", name: "Square" },
                { value: "decimal", name: "Decimal" },
                { value: "lower-alpha", name: "Lower Alpha" },
                { value: "upper-alpha", name: "Upper Alpha" },
                { value: "lower-roman", name: "Lower Roman" },
                { value: "upper-roman", name: "Upper Roman" },
            ];

            options.forEach((opt) => {
                const option = document.createElement("option");
                option.value = opt.value;
                option.text = opt.name;
                input.appendChild(option);
            });

            return input;
        },
        onUpdate({ elInput, component }) {
            const listStyle = component.getStyle()['list-style'] || 'none';
            elInput.value = listStyle;
        },
        onEvent({ elInput, component }) {
            const value = elInput.value;
            component.addStyle({ 'list-style': value });

            // Apply the same list-style to all child <li> elements
            const childListItems = component.find('li');
            childListItems.forEach((li) => {
                li.addStyle({ 'list-style': value });
            });
        },
    });

    //--------------------------------
    function updateComponentClass(component, previousValue, newValue) {
        if (previousValue) {
            component.removeClass(previousValue);
        } else {
            // Remove all classes that start with 'fa-' except size-related ones (fa-2x to fa-10x, fa-xs, fa-sm, fa-lg)
            const classesToKeep = [
                'fa-xs', 'fa-sm', 'fa-lg',
                'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-6x', 'fa-7x', 'fa-8x', 'fa-9x', 'fa-10x'
            ];
            const classesToRemove = component.getClasses().filter(cls =>
                cls.startsWith('fa-') && !classesToKeep.includes(cls)
            );
            classesToRemove.forEach(cls => component.removeClass(cls));
        }
        if (newValue) {
            component.addClass(newValue);
        }
    }
    //--------------------------------
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        const $state = $(
            `<span><i class="fa ${state.text}"></i> ${state.text}</span>`);
        return $state;
    }
    //--------------------------------
}

//put the new defined traits in use so we created new inputs using them.
function traits(editor) {
    editor.DomComponents.getTypes().map((type) => {
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
                    {
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
                options: [
                    {
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
                type: "label",
                label: "Text",
            },
            {
                type: "class_select",
                options: [
                    {
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
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "nowrap",
                        name: "Nowrap",
                    }
                ],
                label: "White Space",
            },
            {
                type: "label",
                label: "Direction",
            },
            {
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "flex-direction-column",
                        name: "Column",
                    },
                    {
                        value: "flex-direction-row",
                        name: "Row",
                    },
                ],
                label: "Flex Direction",
            },

            {
                type: "label",
                label: "Alignment",
            },
            {
                type: "class_select",
                options: [
                    {
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
                options: [
                    {
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
                options: [
                    {
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
                options: [
                    {
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
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "m-0",
                        name: "0",
                    },
                    {
                        value: "m-1",
                        name: "1",
                    },
                    {
                        value: "m-2",
                        name: "2",
                    },
                    {
                        value: "m-3",
                        name: "3",
                    },
                    {
                        value: "m-4",
                        name: "4",
                    },
                    {
                        value: "m-5",
                        name: "5",
                    },
                    {
                        value: "m-auto",
                        name: "Auto",
                    },
                ],
                label: "All",
            },
            {
                type: "class_select",
                options: [
                    {
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
                        value: "mt-10",
                        name: "10",
                    },
                    {
                        value: "mt-15",
                        name: "15",
                    },
                    {
                        value: "mt-20",
                        name: "20",
                    },
                    {
                        value: "mt-25",
                        name: "25",
                    },
                    {
                        value: "mt-30",
                        name: "30",
                    },
                    {
                        value: "mt-35",
                        name: "35",
                    },
                    {
                        value: "mt-40",
                        name: "40",
                    },
                    {
                        value: "mt-45",
                        name: "45",
                    },
                    {
                        value: "mt-50",
                        name: "50",
                    },
                    {
                        value: "mt-55",
                        name: "55",
                    },
                    {
                        value: "mt-60",
                        name: "60",
                    },
                    {
                        value: "mt-65",
                        name: "65",
                    },
                    {
                        value: "mt-70",
                        name: "70",
                    },
                    {
                        value: "mt-75",
                        name: "75",
                    },
                    {
                        value: "mt-80",
                        name: "80",
                    },
                    {
                        value: "mt-85",
                        name: "85",
                    },
                    {
                        value: "mt-90",
                        name: "90",
                    },
                    {
                        value: "mt-95",
                        name: "95",
                    },
                    {
                        value: "mt-100",
                        name: "100",
                    },
                    {
                        value: "mt-105",
                        name: "105",
                    },
                    {
                        value: "mt-110",
                        name: "110",
                    },
                    {
                        value: "mt-115",
                        name: "115",
                    },
                    {
                        value: "mt-120",
                        name: "120",
                    },
                    {
                        value: "mt-125",
                        name: "125",
                    },
                    {
                        value: "mt-130",
                        name: "130",
                    },
                    {
                        value: "mt-135",
                        name: "135",
                    },
                    {
                        value: "mt-140",
                        name: "140",
                    },
                    {
                        value: "mt-145",
                        name: "145",
                    },
                    {
                        value: "mt-150",
                        name: "150",
                    },
                    {
                        value: "mt-155",
                        name: "155",
                    },
                    {
                        value: "mt-160",
                        name: "160",
                    },
                    {
                        value: "mt-165",
                        name: "165",
                    },
                    {
                        value: "mt-170",
                        name: "170",
                    },
                    {
                        value: "mt-175",
                        name: "175",
                    },
                    {
                        value: "mt-180",
                        name: "180",
                    },
                    {
                        value: "mt-185",
                        name: "185",
                    },
                    {
                        value: "mt-190",
                        name: "190",
                    },
                    {
                        value: "mt-195",
                        name: "195",
                    },
                    {
                        value: "mt-200",
                        name: "200",
                    },
                    {
                        value: "mt-auto",
                        name: "Auto",
                    }
                ],
                label: "Top",
            },
            {
                type: "class_select",
                options: [
                    {
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
                        value: "mb-10",
                        name: "10",
                    },
                    {
                        value: "mb-15",
                        name: "15",
                    },
                    {
                        value: "mb-20",
                        name: "20",
                    },
                    {
                        value: "mb-25",
                        name: "25",
                    },
                    {
                        value: "mb-30",
                        name: "30",
                    },
                    {
                        value: "mb-35",
                        name: "35",
                    },
                    {
                        value: "mb-40",
                        name: "40",
                    },
                    {
                        value: "mb-45",
                        name: "45",
                    },
                    {
                        value: "mb-50",
                        name: "50",
                    },
                    {
                        value: "mb-55",
                        name: "55",
                    },
                    {
                        value: "mb-60",
                        name: "60",
                    },
                    {
                        value: "mb-65",
                        name: "65",
                    },
                    {
                        value: "mb-70",
                        name: "70",
                    },
                    {
                        value: "mb-75",
                        name: "75",
                    },
                    {
                        value: "mb-80",
                        name: "80",
                    },
                    {
                        value: "mb-85",
                        name: "85",
                    },
                    {
                        value: "mb-90",
                        name: "90",
                    },
                    {
                        value: "mb-95",
                        name: "95",
                    },
                    {
                        value: "mb-100",
                        name: "100",
                    },
                    {
                        value: "mb-105",
                        name: "105",
                    },
                    {
                        value: "mb-110",
                        name: "110",
                    },
                    {
                        value: "mb-115",
                        name: "115",
                    },
                    {
                        value: "mb-120",
                        name: "120",
                    },
                    {
                        value: "mb-125",
                        name: "125",
                    },
                    {
                        value: "mb-130",
                        name: "130",
                    },
                    {
                        value: "mb-135",
                        name: "135",
                    },
                    {
                        value: "mb-140",
                        name: "140",
                    },
                    {
                        value: "mb-145",
                        name: "145",
                    },
                    {
                        value: "mb-150",
                        name: "150",
                    },
                    {
                        value: "mb-155",
                        name: "155",
                    },
                    {
                        value: "mb-160",
                        name: "160",
                    },
                    {
                        value: "mb-165",
                        name: "165",
                    },
                    {
                        value: "mb-170",
                        name: "170",
                    },
                    {
                        value: "mb-175",
                        name: "175",
                    },
                    {
                        value: "mb-180",
                        name: "180",
                    },
                    {
                        value: "mb-185",
                        name: "185",
                    },
                    {
                        value: "mb-190",
                        name: "190",
                    },
                    {
                        value: "mb-195",
                        name: "195",
                    },
                    {
                        value: "mb-200",
                        name: "200",
                    },
                    {
                        value: "mb-auto",
                        name: "Auto",
                    }
                ],
                label: "Bottom",
            },
            {
                type: "class_select",
                options: [
                    {
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
                        value: "me-10",
                        name: "10",
                    },
                    {
                        value: "me-15",
                        name: "15",
                    },
                    {
                        value: "me-20",
                        name: "20",
                    },
                    {
                        value: "me-25",
                        name: "25",
                    },
                    {
                        value: "me-30",
                        name: "30",
                    },
                    {
                        value: "me-35",
                        name: "35",
                    },
                    {
                        value: "me-40",
                        name: "40",
                    },
                    {
                        value: "me-45",
                        name: "45",
                    },
                    {
                        value: "me-50",
                        name: "50",
                    },
                    {
                        value: "me-55",
                        name: "55",
                    },
                    {
                        value: "me-60",
                        name: "60",
                    },
                    {
                        value: "me-65",
                        name: "65",
                    },
                    {
                        value: "me-70",
                        name: "70",
                    },
                    {
                        value: "me-75",
                        name: "75",
                    },
                    {
                        value: "me-80",
                        name: "80",
                    },
                    {
                        value: "me-85",
                        name: "85",
                    },
                    {
                        value: "me-90",
                        name: "90",
                    },
                    {
                        value: "me-95",
                        name: "95",
                    },
                    {
                        value: "me-100",
                        name: "100",
                    },
                    {
                        value: "me-105",
                        name: "105",
                    },
                    {
                        value: "me-110",
                        name: "110",
                    },
                    {
                        value: "me-115",
                        name: "115",
                    },
                    {
                        value: "me-120",
                        name: "120",
                    },
                    {
                        value: "me-125",
                        name: "125",
                    },
                    {
                        value: "me-130",
                        name: "130",
                    },
                    {
                        value: "me-135",
                        name: "135",
                    },
                    {
                        value: "me-140",
                        name: "140",
                    },
                    {
                        value: "me-145",
                        name: "145",
                    },
                    {
                        value: "me-150",
                        name: "150",
                    },
                    {
                        value: "me-155",
                        name: "155",
                    },
                    {
                        value: "me-160",
                        name: "160",
                    },
                    {
                        value: "me-165",
                        name: "165",
                    },
                    {
                        value: "me-170",
                        name: "170",
                    },
                    {
                        value: "me-175",
                        name: "175",
                    },
                    {
                        value: "me-180",
                        name: "180",
                    },
                    {
                        value: "me-185",
                        name: "185",
                    },
                    {
                        value: "me-190",
                        name: "190",
                    },
                    {
                        value: "me-195",
                        name: "195",
                    },
                    {
                        value: "me-200",
                        name: "200",
                    },
                    {
                        value: "me-auto",
                        name: "Auto",
                    }
                ],
                label: "Right",
            },
            {
                type: "class_select",
                options: [
                    {
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
                        value: "ms-10",
                        name: "10",
                    },
                    {
                        value: "ms-15",
                        name: "15",
                    },
                    {
                        value: "ms-20",
                        name: "20",
                    },
                    {
                        value: "ms-25",
                        name: "25",
                    },
                    {
                        value: "ms-30",
                        name: "30",
                    },
                    {
                        value: "ms-35",
                        name: "35",
                    },
                    {
                        value: "ms-40",
                        name: "40",
                    },
                    {
                        value: "ms-45",
                        name: "45",
                    },
                    {
                        value: "ms-50",
                        name: "50",
                    },
                    {
                        value: "ms-55",
                        name: "55",
                    },
                    {
                        value: "ms-60",
                        name: "60",
                    },
                    {
                        value: "ms-65",
                        name: "65",
                    },
                    {
                        value: "ms-70",
                        name: "70",
                    },
                    {
                        value: "ms-75",
                        name: "75",
                    },
                    {
                        value: "ms-80",
                        name: "80",
                    },
                    {
                        value: "ms-85",
                        name: "85",
                    },
                    {
                        value: "ms-90",
                        name: "90",
                    },
                    {
                        value: "ms-95",
                        name: "95",
                    },
                    {
                        value: "ms-100",
                        name: "100",
                    },
                    {
                        value: "ms-105",
                        name: "105",
                    },
                    {
                        value: "ms-110",
                        name: "110",
                    },
                    {
                        value: "ms-115",
                        name: "115",
                    },
                    {
                        value: "ms-120",
                        name: "120",
                    },
                    {
                        value: "ms-125",
                        name: "125",
                    },
                    {
                        value: "ms-130",
                        name: "130",
                    },
                    {
                        value: "ms-135",
                        name: "135",
                    },
                    {
                        value: "ms-140",
                        name: "140",
                    },
                    {
                        value: "ms-145",
                        name: "145",
                    },
                    {
                        value: "ms-150",
                        name: "150",
                    },
                    {
                        value: "ms-155",
                        name: "155",
                    },
                    {
                        value: "ms-160",
                        name: "160",
                    },
                    {
                        value: "ms-165",
                        name: "165",
                    },
                    {
                        value: "ms-170",
                        name: "170",
                    },
                    {
                        value: "ms-175",
                        name: "175",
                    },
                    {
                        value: "ms-180",
                        name: "180",
                    },
                    {
                        value: "ms-185",
                        name: "185",
                    },
                    {
                        value: "ms-190",
                        name: "190",
                    },
                    {
                        value: "ms-195",
                        name: "195",
                    },
                    {
                        value: "ms-200",
                        name: "200",
                    },
                    {
                        value: "ms-auto",
                        name: "Auto",
                    }
                ],
                label: "Left",
            },
            {
                type: "label",
                label: "Outer Spacing (Margin) For Large Screen Only",
            },
            {
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "mt-lg-0",
                        name: "0",
                    },
                    {
                        value: "mt-lg-1",
                        name: "1",
                    },
                    {
                        value: "mt-lg-2",
                        name: "2",
                    },
                    {
                        value: "mt-lg-3",
                        name: "3",
                    },
                    {
                        value: "mt-lg-4",
                        name: "4",
                    },
                    {
                        value: "mt-lg-5",
                        name: "5",
                    },
                    {
                        value: "mt-lg-10",
                        name: "10",
                    },
                    {
                        value: "mt-lg-15",
                        name: "15",
                    },
                    {
                        value: "mt-lg-20",
                        name: "20",
                    },
                    {
                        value: "mt-lg-25",
                        name: "25",
                    },
                    {
                        value: "mt-lg-30",
                        name: "30",
                    },
                    {
                        value: "mt-lg-35",
                        name: "35",
                    },
                    {
                        value: "mt-lg-40",
                        name: "40",
                    },
                    {
                        value: "mt-lg-45",
                        name: "45",
                    },
                    {
                        value: "mt-lg-50",
                        name: "50",
                    },
                    {
                        value: "mt-lg-55",
                        name: "55",
                    },
                    {
                        value: "mt-lg-60",
                        name: "60",
                    },
                    {
                        value: "mt-lg-65",
                        name: "65",
                    },
                    {
                        value: "mt-lg-70",
                        name: "70",
                    },
                    {
                        value: "mt-lg-75",
                        name: "75",
                    },
                    {
                        value: "mt-lg-80",
                        name: "80",
                    },
                    {
                        value: "mt-lg-85",
                        name: "85",
                    },
                    {
                        value: "mt-lg-90",
                        name: "90",
                    },
                    {
                        value: "mt-lg-95",
                        name: "95",
                    },
                    {
                        value: "mt-lg-100",
                        name: "100",
                    },
                    {
                        value: "mt-lg-105",
                        name: "105",
                    },
                    {
                        value: "mt-lg-110",
                        name: "110",
                    },
                    {
                        value: "mt-lg-115",
                        name: "115",
                    },
                    {
                        value: "mt-lg-120",
                        name: "120",
                    },
                    {
                        value: "mt-lg-125",
                        name: "125",
                    },
                    {
                        value: "mt-lg-130",
                        name: "130",
                    },
                    {
                        value: "mt-lg-135",
                        name: "135",
                    },
                    {
                        value: "mt-lg-140",
                        name: "140",
                    },
                    {
                        value: "mt-lg-145",
                        name: "145",
                    },
                    {
                        value: "mt-lg-150",
                        name: "150",
                    },
                    {
                        value: "mt-lg-155",
                        name: "155",
                    },
                    {
                        value: "mt-lg-160",
                        name: "160",
                    },
                    {
                        value: "mt-lg-165",
                        name: "165",
                    },
                    {
                        value: "mt-lg-170",
                        name: "170",
                    },
                    {
                        value: "mt-lg-175",
                        name: "175",
                    },
                    {
                        value: "mt-lg-180",
                        name: "180",
                    },
                    {
                        value: "mt-lg-185",
                        name: "185",
                    },
                    {
                        value: "mt-lg-190",
                        name: "190",
                    },
                    {
                        value: "mt-lg-195",
                        name: "195",
                    },
                    {
                        value: "mt-lg-200",
                        name: "200",
                    },
                    {
                        value: "mt-lg-auto",
                        name: "Auto",
                    }
                ],
                label: "Top",
            },
            {
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "mb-lg-0",
                        name: "0",
                    },
                    {
                        value: "mb-lg-1",
                        name: "1",
                    },
                    {
                        value: "mb-lg-2",
                        name: "2",
                    },
                    {
                        value: "mb-lg-3",
                        name: "3",
                    },
                    {
                        value: "mb-lg-4",
                        name: "4",
                    },
                    {
                        value: "mb-lg-5",
                        name: "5",
                    },
                    {
                        value: "mb-lg-10",
                        name: "10",
                    },
                    {
                        value: "mb-lg-15",
                        name: "15",
                    },
                    {
                        value: "mb-lg-20",
                        name: "20",
                    },
                    {
                        value: "mb-lg-25",
                        name: "25",
                    },
                    {
                        value: "mb-lg-30",
                        name: "30",
                    },
                    {
                        value: "mb-lg-35",
                        name: "35",
                    },
                    {
                        value: "mb-lg-40",
                        name: "40",
                    },
                    {
                        value: "mb-lg-45",
                        name: "45",
                    },
                    {
                        value: "mb-lg-50",
                        name: "50",
                    },
                    {
                        value: "mb-lg-55",
                        name: "55",
                    },
                    {
                        value: "mb-lg-60",
                        name: "60",
                    },
                    {
                        value: "mb-lg-65",
                        name: "65",
                    },
                    {
                        value: "mb-lg-70",
                        name: "70",
                    },
                    {
                        value: "mb-lg-75",
                        name: "75",
                    },
                    {
                        value: "mb-lg-80",
                        name: "80",
                    },
                    {
                        value: "mb-lg-85",
                        name: "85",
                    },
                    {
                        value: "mb-lg-90",
                        name: "90",
                    },
                    {
                        value: "mb-lg-95",
                        name: "95",
                    },
                    {
                        value: "mb-lg-100",
                        name: "100",
                    },
                    {
                        value: "mb-lg-105",
                        name: "105",
                    },
                    {
                        value: "mb-lg-110",
                        name: "110",
                    },
                    {
                        value: "mb-lg-115",
                        name: "115",
                    },
                    {
                        value: "mb-lg-120",
                        name: "120",
                    },
                    {
                        value: "mb-lg-125",
                        name: "125",
                    },
                    {
                        value: "mb-lg-130",
                        name: "130",
                    },
                    {
                        value: "mb-lg-135",
                        name: "135",
                    },
                    {
                        value: "mb-lg-140",
                        name: "140",
                    },
                    {
                        value: "mb-lg-145",
                        name: "145",
                    },
                    {
                        value: "mb-lg-150",
                        name: "150",
                    },
                    {
                        value: "mb-lg-155",
                        name: "155",
                    },
                    {
                        value: "mb-lg-160",
                        name: "160",
                    },
                    {
                        value: "mb-lg-165",
                        name: "165",
                    },
                    {
                        value: "mb-lg-170",
                        name: "170",
                    },
                    {
                        value: "mb-lg-175",
                        name: "175",
                    },
                    {
                        value: "mb-lg-180",
                        name: "180",
                    },
                    {
                        value: "mb-lg-185",
                        name: "185",
                    },
                    {
                        value: "mb-lg-190",
                        name: "190",
                    },
                    {
                        value: "mb-lg-195",
                        name: "195",
                    },
                    {
                        value: "mb-lg-200",
                        name: "200",
                    },
                    {
                        value: "mb-lg-auto",
                        name: "Auto",
                    }
                ],
                label: "Bottom",
            },
            {
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "me-lg-0",
                        name: "0",
                    },
                    {
                        value: "me-lg-1",
                        name: "1",
                    },
                    {
                        value: "me-lg-2",
                        name: "2",
                    },
                    {
                        value: "me-lg-3",
                        name: "3",
                    },
                    {
                        value: "me-lg-4",
                        name: "4",
                    },
                    {
                        value: "me-lg-5",
                        name: "5",
                    },
                    {
                        value: "me-lg-10",
                        name: "10",
                    },
                    {
                        value: "me-lg-15",
                        name: "15",
                    },
                    {
                        value: "me-lg-20",
                        name: "20",
                    },
                    {
                        value: "me-lg-25",
                        name: "25",
                    },
                    {
                        value: "me-lg-30",
                        name: "30",
                    },
                    {
                        value: "me-lg-35",
                        name: "35",
                    },
                    {
                        value: "me-lg-40",
                        name: "40",
                    },
                    {
                        value: "me-lg-45",
                        name: "45",
                    },
                    {
                        value: "me-lg-50",
                        name: "50",
                    },
                    {
                        value: "me-lg-55",
                        name: "55",
                    },
                    {
                        value: "me-lg-60",
                        name: "60",
                    },
                    {
                        value: "me-lg-65",
                        name: "65",
                    },
                    {
                        value: "me-lg-70",
                        name: "70",
                    },
                    {
                        value: "me-lg-75",
                        name: "75",
                    },
                    {
                        value: "me-lg-80",
                        name: "80",
                    },
                    {
                        value: "me-lg-85",
                        name: "85",
                    },
                    {
                        value: "me-lg-90",
                        name: "90",
                    },
                    {
                        value: "me-lg-95",
                        name: "95",
                    },
                    {
                        value: "me-lg-100",
                        name: "100",
                    },
                    {
                        value: "me-lg-105",
                        name: "105",
                    },
                    {
                        value: "me-lg-110",
                        name: "110",
                    },
                    {
                        value: "me-lg-115",
                        name: "115",
                    },
                    {
                        value: "me-lg-120",
                        name: "120",
                    },
                    {
                        value: "me-lg-125",
                        name: "125",
                    },
                    {
                        value: "me-lg-130",
                        name: "130",
                    },
                    {
                        value: "me-lg-135",
                        name: "135",
                    },
                    {
                        value: "me-lg-140",
                        name: "140",
                    },
                    {
                        value: "me-lg-145",
                        name: "145",
                    },
                    {
                        value: "me-lg-150",
                        name: "150",
                    },
                    {
                        value: "me-lg-155",
                        name: "155",
                    },
                    {
                        value: "me-lg-160",
                        name: "160",
                    },
                    {
                        value: "me-lg-165",
                        name: "165",
                    },
                    {
                        value: "me-lg-170",
                        name: "170",
                    },
                    {
                        value: "me-lg-175",
                        name: "175",
                    },
                    {
                        value: "me-lg-180",
                        name: "180",
                    },
                    {
                        value: "me-lg-185",
                        name: "185",
                    },
                    {
                        value: "me-lg-190",
                        name: "190",
                    },
                    {
                        value: "me-lg-195",
                        name: "195",
                    },
                    {
                        value: "me-lg-200",
                        name: "200",
                    },
                    {
                        value: "me-lg-auto",
                        name: "Auto",
                    }
                ],
                label: "Right",
            },
            {
                type: "class_select",
                options: [
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "ms-lg-0",
                        name: "0",
                    },
                    {
                        value: "ms-lg-1",
                        name: "1",
                    },
                    {
                        value: "ms-lg-2",
                        name: "2",
                    },
                    {
                        value: "ms-lg-3",
                        name: "3",
                    },
                    {
                        value: "ms-lg-4",
                        name: "4",
                    },
                    {
                        value: "ms-lg-5",
                        name: "5",
                    },
                    {
                        value: "ms-lg-10",
                        name: "10",
                    },
                    {
                        value: "ms-lg-15",
                        name: "15",
                    },
                    {
                        value: "ms-lg-20",
                        name: "20",
                    },
                    {
                        value: "ms-lg-25",
                        name: "25",
                    },
                    {
                        value: "ms-lg-30",
                        name: "30",
                    },
                    {
                        value: "ms-lg-35",
                        name: "35",
                    },
                    {
                        value: "ms-lg-40",
                        name: "40",
                    },
                    {
                        value: "ms-lg-45",
                        name: "45",
                    },
                    {
                        value: "ms-lg-50",
                        name: "50",
                    },
                    {
                        value: "ms-lg-55",
                        name: "55",
                    },
                    {
                        value: "ms-lg-60",
                        name: "60",
                    },
                    {
                        value: "ms-lg-65",
                        name: "65",
                    },
                    {
                        value: "ms-lg-70",
                        name: "70",
                    },
                    {
                        value: "ms-lg-75",
                        name: "75",
                    },
                    {
                        value: "ms-lg-80",
                        name: "80",
                    },
                    {
                        value: "ms-lg-85",
                        name: "85",
                    },
                    {
                        value: "ms-lg-90",
                        name: "90",
                    },
                    {
                        value: "ms-lg-95",
                        name: "95",
                    },
                    {
                        value: "ms-lg-100",
                        name: "100",
                    },
                    {
                        value: "ms-lg-105",
                        name: "105",
                    },
                    {
                        value: "ms-lg-110",
                        name: "110",
                    },
                    {
                        value: "ms-lg-115",
                        name: "115",
                    },
                    {
                        value: "ms-lg-120",
                        name: "120",
                    },
                    {
                        value: "ms-lg-125",
                        name: "125",
                    },
                    {
                        value: "ms-lg-130",
                        name: "130",
                    },
                    {
                        value: "ms-lg-135",
                        name: "135",
                    },
                    {
                        value: "ms-lg-140",
                        name: "140",
                    },
                    {
                        value: "ms-lg-145",
                        name: "145",
                    },
                    {
                        value: "ms-lg-150",
                        name: "150",
                    },
                    {
                        value: "ms-lg-155",
                        name: "155",
                    },
                    {
                        value: "ms-lg-160",
                        name: "160",
                    },
                    {
                        value: "ms-lg-165",
                        name: "165",
                    },
                    {
                        value: "ms-lg-170",
                        name: "170",
                    },
                    {
                        value: "ms-lg-175",
                        name: "175",
                    },
                    {
                        value: "ms-lg-180",
                        name: "180",
                    },
                    {
                        value: "ms-lg-185",
                        name: "185",
                    },
                    {
                        value: "ms-lg-190",
                        name: "190",
                    },
                    {
                        value: "ms-lg-195",
                        name: "195",
                    },
                    {
                        value: "ms-lg-200",
                        name: "200",
                    },
                    {
                        value: "ms-lg-auto",
                        name: "Auto",
                    }
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
                    {
                        value: "",
                        name: "None",
                    },
                    {
                        value: "p-0",
                        name: "0",
                    },
                    {
                        value: "p-1",
                        name: "1",
                    },
                    {
                        value: "p-2",
                        name: "2",
                    },
                    {
                        value: "p-3",
                        name: "3",
                    },
                    {
                        value: "p-4",
                        name: "4",
                    },
                    {
                        value: "p-5",
                        name: "5",
                    },
                ],
                label: "All",
            },
            {
                type: "class_select",
                options: [
                    {
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
                    {
                        value: "pt-10",
                        name: "10",
                    },
                    {
                        value: "pt-15",
                        name: "15",
                    },
                    {
                        value: "pt-20",
                        name: "20",
                    },
                    {
                        value: "pt-25",
                        name: "25",
                    },
                    {
                        value: "pt-30",
                        name: "30",
                    },
                    {
                        value: "pt-35",
                        name: "35",
                    },
                    {
                        value: "pt-40",
                        name: "40",
                    },
                    {
                        value: "pt-45",
                        name: "45",
                    },
                    {
                        value: "pt-50",
                        name: "50",
                    },
                    {
                        value: "pt-55",
                        name: "55",
                    },
                    {
                        value: "pt-60",
                        name: "60",
                    },
                    {
                        value: "pt-65",
                        name: "65",
                    },
                    {
                        value: "pt-70",
                        name: "70",
                    },
                    {
                        value: "pt-75",
                        name: "75",
                    },
                    {
                        value: "pt-80",
                        name: "80",
                    },
                    {
                        value: "pt-85",
                        name: "85",
                    },
                    {
                        value: "pt-90",
                        name: "90",
                    },
                    {
                        value: "pt-95",
                        name: "95",
                    },
                    {
                        value: "pt-100",
                        name: "100",
                    },
                    {
                        value: "pt-105",
                        name: "105",
                    },
                    {
                        value: "pt-110",
                        name: "110",
                    },
                    {
                        value: "pt-115",
                        name: "115",
                    },
                    {
                        value: "pt-120",
                        name: "120",
                    },
                    {
                        value: "pt-125",
                        name: "125",
                    },
                    {
                        value: "pt-130",
                        name: "130",
                    },
                    {
                        value: "pt-135",
                        name: "135",
                    },
                    {
                        value: "pt-140",
                        name: "140",
                    },
                    {
                        value: "pt-145",
                        name: "145",
                    },
                    {
                        value: "pt-150",
                        name: "150",
                    },
                    {
                        value: "pt-155",
                        name: "155",
                    },
                    {
                        value: "pt-160",
                        name: "160",
                    },
                    {
                        value: "pt-165",
                        name: "165",
                    },
                    {
                        value: "pt-170",
                        name: "170",
                    },
                    {
                        value: "pt-175",
                        name: "175",
                    },
                    {
                        value: "pt-180",
                        name: "180",
                    },
                    {
                        value: "pt-185",
                        name: "185",
                    },
                    {
                        value: "pt-190",
                        name: "190",
                    },
                    {
                        value: "pt-195",
                        name: "195",
                    },
                    {
                        value: "pt-200",
                        name: "200",
                    }
                ],
                label: "Top",
            },
            {
                type: "class_select",
                options: [
                    {
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
                    {
                        value: "pb-10",
                        name: "10",
                    },
                    {
                        value: "pb-15",
                        name: "15",
                    },
                    {
                        value: "pb-20",
                        name: "20",
                    },
                    {
                        value: "pb-25",
                        name: "25",
                    },
                    {
                        value: "pb-30",
                        name: "30",
                    },
                    {
                        value: "pb-35",
                        name: "35",
                    },
                    {
                        value: "pb-40",
                        name: "40",
                    },
                    {
                        value: "pb-45",
                        name: "45",
                    },
                    {
                        value: "pb-50",
                        name: "50",
                    },
                    {
                        value: "pb-55",
                        name: "55",
                    },
                    {
                        value: "pb-60",
                        name: "60",
                    },
                    {
                        value: "pb-65",
                        name: "65",
                    },
                    {
                        value: "pb-70",
                        name: "70",
                    },
                    {
                        value: "pb-75",
                        name: "75",
                    },
                    {
                        value: "pb-80",
                        name: "80",
                    },
                    {
                        value: "pb-85",
                        name: "85",
                    },
                    {
                        value: "pb-90",
                        name: "90",
                    },
                    {
                        value: "pb-95",
                        name: "95",
                    },
                    {
                        value: "pb-100",
                        name: "100",
                    },
                    {
                        value: "pb-105",
                        name: "105",
                    },
                    {
                        value: "pb-110",
                        name: "110",
                    },
                    {
                        value: "pb-115",
                        name: "115",
                    },
                    {
                        value: "pb-120",
                        name: "120",
                    },
                    {
                        value: "pb-125",
                        name: "125",
                    },
                    {
                        value: "pb-130",
                        name: "130",
                    },
                    {
                        value: "pb-135",
                        name: "135",
                    },
                    {
                        value: "pb-140",
                        name: "140",
                    },
                    {
                        value: "pb-145",
                        name: "145",
                    },
                    {
                        value: "pb-150",
                        name: "150",
                    },
                    {
                        value: "pb-155",
                        name: "155",
                    },
                    {
                        value: "pb-160",
                        name: "160",
                    },
                    {
                        value: "pb-165",
                        name: "165",
                    },
                    {
                        value: "pb-170",
                        name: "170",
                    },
                    {
                        value: "pb-175",
                        name: "175",
                    },
                    {
                        value: "pb-180",
                        name: "180",
                    },
                    {
                        value: "pb-185",
                        name: "185",
                    },
                    {
                        value: "pb-190",
                        name: "190",
                    },
                    {
                        value: "pb-195",
                        name: "195",
                    },
                    {
                        value: "pb-200",
                        name: "200",
                    }
                ]
                ,
                label: "Bottom",
            },
            {
                type: "class_select",
                options: [
                    {
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
                    {
                        value: "pe-10",
                        name: "10",
                    },
                    {
                        value: "pe-15",
                        name: "15",
                    },
                    {
                        value: "pe-20",
                        name: "20",
                    },
                    {
                        value: "pe-25",
                        name: "25",
                    },
                    {
                        value: "pe-30",
                        name: "30",
                    },
                    {
                        value: "pe-35",
                        name: "35",
                    },
                    {
                        value: "pe-40",
                        name: "40",
                    },
                    {
                        value: "pe-45",
                        name: "45",
                    },
                    {
                        value: "pe-50",
                        name: "50",
                    },
                    {
                        value: "pe-55",
                        name: "55",
                    },
                    {
                        value: "pe-60",
                        name: "60",
                    },
                    {
                        value: "pe-65",
                        name: "65",
                    },
                    {
                        value: "pe-70",
                        name: "70",
                    },
                    {
                        value: "pe-75",
                        name: "75",
                    },
                    {
                        value: "pe-80",
                        name: "80",
                    },
                    {
                        value: "pe-85",
                        name: "85",
                    },
                    {
                        value: "pe-90",
                        name: "90",
                    },
                    {
                        value: "pe-95",
                        name: "95",
                    },
                    {
                        value: "pe-100",
                        name: "100",
                    },
                    {
                        value: "pe-105",
                        name: "105",
                    },
                    {
                        value: "pe-110",
                        name: "110",
                    },
                    {
                        value: "pe-115",
                        name: "115",
                    },
                    {
                        value: "pe-120",
                        name: "120",
                    },
                    {
                        value: "pe-125",
                        name: "125",
                    },
                    {
                        value: "pe-130",
                        name: "130",
                    },
                    {
                        value: "pe-135",
                        name: "135",
                    },
                    {
                        value: "pe-140",
                        name: "140",
                    },
                    {
                        value: "pe-145",
                        name: "145",
                    },
                    {
                        value: "pe-150",
                        name: "150",
                    },
                    {
                        value: "pe-155",
                        name: "155",
                    },
                    {
                        value: "pe-160",
                        name: "160",
                    },
                    {
                        value: "pe-165",
                        name: "165",
                    },
                    {
                        value: "pe-170",
                        name: "170",
                    },
                    {
                        value: "pe-175",
                        name: "175",
                    },
                    {
                        value: "pe-180",
                        name: "180",
                    },
                    {
                        value: "pe-185",
                        name: "185",
                    },
                    {
                        value: "pe-190",
                        name: "190",
                    },
                    {
                        value: "pe-195",
                        name: "195",
                    },
                    {
                        value: "pe-200",
                        name: "200",
                    },
                ]
                ,
                label: "Right",
            },
            {
                type: "class_select",
                options: [
                    {
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
                    {
                        value: "ps-10",
                        name: "10",
                    },
                    {
                        value: "ps-15",
                        name: "15",
                    },
                    {
                        value: "ps-20",
                        name: "20",
                    },
                    {
                        value: "ps-25",
                        name: "25",
                    },
                    {
                        value: "ps-30",
                        name: "30",
                    },
                    {
                        value: "ps-35",
                        name: "35",
                    },
                    {
                        value: "ps-40",
                        name: "40",
                    },
                    {
                        value: "ps-45",
                        name: "45",
                    },
                    {
                        value: "ps-50",
                        name: "50",
                    },
                    {
                        value: "ps-55",
                        name: "55",
                    },
                    {
                        value: "ps-60",
                        name: "60",
                    },
                    {
                        value: "ps-65",
                        name: "65",
                    },
                    {
                        value: "ps-70",
                        name: "70",
                    },
                    {
                        value: "ps-75",
                        name: "75",
                    },
                    {
                        value: "ps-80",
                        name: "80",
                    },
                    {
                        value: "ps-85",
                        name: "85",
                    },
                    {
                        value: "ps-90",
                        name: "90",
                    },
                    {
                        value: "ps-95",
                        name: "95",
                    },
                    {
                        value: "ps-100",
                        name: "100",
                    },
                    {
                        value: "ps-105",
                        name: "105",
                    },
                    {
                        value: "ps-110",
                        name: "110",
                    },
                    {
                        value: "ps-115",
                        name: "115",
                    },
                    {
                        value: "ps-120",
                        name: "120",
                    },
                    {
                        value: "ps-125",
                        name: "125",
                    },
                    {
                        value: "ps-130",
                        name: "130",
                    },
                    {
                        value: "ps-135",
                        name: "135",
                    },
                    {
                        value: "ps-140",
                        name: "140",
                    },
                    {
                        value: "ps-145",
                        name: "145",
                    },
                    {
                        value: "ps-150",
                        name: "150",
                    },
                    {
                        value: "ps-155",
                        name: "155",
                    },
                    {
                        value: "ps-160",
                        name: "160",
                    },
                    {
                        value: "ps-165",
                        name: "165",
                    },
                    {
                        value: "ps-170",
                        name: "170",
                    },
                    {
                        value: "ps-175",
                        name: "175",
                    },
                    {
                        value: "ps-180",
                        name: "180",
                    },
                    {
                        value: "ps-185",
                        name: "185",
                    },
                    {
                        value: "ps-190",
                        name: "190",
                    },
                    {
                        value: "ps-195",
                        name: "195",
                    },
                    {
                        value: "ps-200",
                        name: "200",
                    },
                ]
                ,
                label: "Left",
            },
            {
                type: "label",
                label: "Hide or Show",
            },
            {
                type: "class_select",
                options: [
                    {
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
                options: [
                    {
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
                options: [
                    {
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
            traitArr.unshift(
                {
                    type: "label",
                    label: "Link",
                },
                {
                    type: "text",
                    label: "Href",
                    name: "href",
                }
            );
        }
        if (type.id == "text") { // matches h1-h6 or p
            traitArr.unshift({
                type: "tag_select",
                label: "Tag",
                name: "tagName",
                options: [
                    { value: "p", name: "Paragraph" },
                    { value: "h1", name: "H1" },
                    { value: "h2", name: "H2" },
                    { value: "h3", name: "H3" },
                    { value: "h4", name: "H4" },
                    { value: "h5", name: "H5" },
                    { value: "h6", name: "H6" },
                ]
            });
        }
        if (type.id == "list") {
            traitArr.unshift({
                type: "list_style_select",
                label: "List Style",
                name: "listStyle",
            });
        }
        if (type.id == "icon-block") {
            traitArr.splice(5, 0,
                {
                    type: "label",
                    label: "Icon Settings",
                },
                {
                    type: 'icon-selector',
                    options: [
                        {
                            value: "fa-cube",
                            name: "fa-cube",
                        },
                        ...((getIcons()) || []).map((iconClass) => ({
                            value: iconClass,
                            name: iconClass,
                        })),
                    ],
                    label: "Icon",
                },
                {
                    type: 'class_select',
                    options: [
                        { value: 'fa-xs', name: 'Extra Small' },
                        { value: 'fa-sm', name: 'Small' },
                        { value: 'fa-lg', name: 'Large' },
                        { value: 'fa-2x', name: '2x' },
                        { value: 'fa-3x', name: '3x' },
                        { value: 'fa-4x', name: '4x' },
                        { value: 'fa-5x', name: '5x' },
                        { value: 'fa-6x', name: '6x' },
                        { value: 'fa-7x', name: '7x' },
                        { value: 'fa-8x', name: '8x' },
                        { value: 'fa-9x', name: '9x' },
                        { value: 'fa-10x', name: '10x' },
                    ],
                    label: 'Size',
                },
            );
        }

        if (type.id == "col") {
            traitArr.unshift({
                type: "class_select",
                options: [
                    {
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
                options: [
                    {
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
            traitArr.unshift(
                {
                    type: "label",
                    label: "Column",
                },
                {
                    type: "class_select",
                    name: "col_lg_class",
                    options: [
                        {
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
                },
                {
                    type: "class_select",
                    name: "col_xl_class",
                    options: [
                        {
                            value: "",
                            name: "None",
                        },
                        {
                            value: "col-xl-12",
                            name: "12/12",
                        },
                        {
                            value: "col-xl-11",
                            name: "11/12",
                        },
                        {
                            value: "col-xl-10",
                            name: "10/12",
                        },
                        {
                            value: "col-xl-9",
                            name: "9/12",
                        },
                        {
                            value: "col-xl-8",
                            name: "8/12",
                        },
                        {
                            value: "col-xl-7",
                            name: "7/12",
                        },
                        {
                            value: "col-xl-6",
                            name: "6/12",
                        },
                        {
                            value: "col-xl-5",
                            name: "5/12",
                        },
                        {
                            value: "col-xl-4",
                            name: "4/12",
                        },
                        {
                            value: "col-xl-3",
                            name: "3/12",
                        },
                        {
                            value: "col-xl-2",
                            name: "2/12",
                        },
                        {
                            value: "col-xl-1",
                            name: "1/12",
                        },
                    ],
                    label: "Extra Large Screen Size",
                }
            );
        }

        if (type.id == "button") {
            traitArr.unshift(
                {
                    type: "label",
                    label: "Button",
                },
                {
                    type: "text",
                    label: "Value",
                    name: "value",
                }
            );
        }

        if (type.id == "Input") {
            traitArr.unshift(
                {
                    type: "label",
                    label: "Input",
                },
                {
                    type: "text",
                    label: "Placeholder",
                    name: "placeholder",
                },
                {
                    type: "text",
                    label: "Value",
                    name: "value",
                },
                {
                    type: "checkbox",
                    label: "Required",
                    name: "required",
                }
            );
        }

        if (type.id == "image") {
            traitArr.unshift(
                {
                    type: "label",
                    label: "Image Settings",
                },
                {
                    type: "text",
                    label: "Alternative Text",
                    name: "alt",
                },
                {
                    type: "text",
                    label: "Width",
                    name: "width",
                },
                {
                    type: "text",
                    label: "Height",
                    name: "height",
                },
            );
        }

        if (type.id == "container") {
            traitArr.unshift(
                {
                    type: "label",
                    label: "Sections Style",
                },
                {
                    type: "class_select",
                    name: "col_lg_class",
                    options: [
                        {
                            value: "container",
                            name: "Boxed",
                        },
                        {
                            value: "container-fluid",
                            name: "All Width",
                        },
                    ],
                    label: "Boxed Section",
                },
            );
        }

        editor.DomComponents.addType(type.id, {
            model: {
                defaults: {
                    traits: traitArr,
                },
            },
        });

    });
}
