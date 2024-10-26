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
        } else if(select2Value) {
          $(elInput).val(select2Value).trigger('change');
        }else{
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
            value: "mt-auto",
            name: "Auto",
          },
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
            value: "mb-auto",
            name: "Auto",
          },
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
            value: "me-auto",
            name: "Auto",
          },
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
        ],
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
