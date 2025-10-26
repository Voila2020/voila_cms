function components(editor) {
    //container component type
    editor.DomComponents.addType('container', {
        model: {
            defaults: {
                tagName: 'div',
                editable: true,
                draggable: "*",
                droppable: true,
            },
        },
        isComponent: function (e) {
            if (e && e.classList && e.classList.contains("container"))
                return { type: "container" };
        },
    });

    //row component type
    editor.DomComponents.addType("Row", {
        model: {
            defaults: {
                tagName: "div",
                editable: true,
                draggable: ".container",
                droppable: true,
            },
        },
        isComponent: function (e) {
            if (e && e.classList && e.classList.contains("row"))
                return { type: "Row" };
        },
    });

    //column component type
    editor.DomComponents.addType('col', {
        model: {
            defaults: {
                tagName: 'div',
                editable: true,
                draggable: ".row",
                droppable: true,
                resizable: {
                    updateTarget: (el, rect, opt) => {
                        const selected = editor.getSelected();
                        if (!selected) {
                            return;
                        }

                        //compute the current screen size (bootstrap semantic)
                        const docWidth = el.getRootNode().body.offsetWidth;
                        let currentSize = "";
                        if (docWidth >= 1200) {
                            currentSize = "xl";
                        } else if (docWidth >= 992) {
                            currentSize = "lg";
                        } else if (docWidth >= 768) {
                            currentSize = "md";
                        } else if (docWidth >= 576) {
                            currentSize = "sm";
                        }

                        //compute the threshold when add on remove 1 col span to the element
                        const row = el.parentElement;
                        const oneColWidth = row.offsetWidth / 12;
                        //the threshold is half one column width
                        const threshold = oneColWidth * 0.5;

                        //check if we are growing or shrinking the column
                        const grow = rect.w > el.offsetWidth + threshold;
                        const shrink = rect.w < el.offsetWidth - threshold;
                        if (grow || shrink) {
                            let testRegexp = new RegExp("^col-" + currentSize + "-\\d{1,2}$");
                            if (!currentSize) {
                                testRegexp = new RegExp("^col-\\d{1,2}$");
                            }
                            let found = false;
                            let sizesSpans = {};
                            let oldSpan = 0;
                            let oldClass = null;
                            for (let cl of el.classList) {
                                if (cl.indexOf("col-") === 0) {
                                    let [c, size, span] = cl.split("-");
                                    if (!span) {
                                        span = size;
                                        size = "";
                                    }
                                    sizesSpans[size] = span;
                                    if (size === currentSize) {
                                        //found the col-XX-99 class
                                        oldClass = cl;
                                        oldSpan = span;
                                        found = true;
                                    }
                                }
                            }

                            if (!found) {
                                const sizeOrder = ["", "xs", "sm", "md", "lg", "xl"];
                                for (let s of sizeOrder) {
                                    if (sizesSpans[s]) {
                                        oldSpan = sizesSpans[s];
                                        found = true;
                                    }
                                    if (s === currentSize) {
                                        break;
                                    }
                                }
                            }

                            let newSpan = Number(oldSpan);
                            if (grow) {
                                newSpan++;
                            } else {
                                newSpan--;
                            }
                            if (newSpan > 12) {
                                newSpan = 12;
                            }
                            if (newSpan < 1) {
                                newSpan = 1;
                            }

                            let newClass = "col-" + currentSize + "-" + newSpan;
                            if (!currentSize) {
                                newClass = "col-" + newSpan;
                            }
                            //update the class
                            selected.addClass(newClass);
                            if (oldClass && oldClass !== newClass) {
                                selected.removeClass(oldClass);
                            }

                        }
                    },
                    tl: 0,
                    tc: 0,
                    tr: 0,
                    cl: 0,
                    cr: 1,
                    bl: 0,
                    bc: 0,
                    br: 0,
                },
            },
        },
        isComponent: function (e) {
            let classes = (e.classList?.value ? e.classList.value : "");
            let IsColumn = classes.includes("col-");
            if (e && e.classList && (e.classList.contains("col") || IsColumn))
                return { type: "col" };
        },
    });


    //card component type
    editor.DomComponents.addType('card', {
        model: {
            defaults: {
                editable: true,
                tagName: 'div',
                droppable: true,
            },
        },
        isComponent: function (e) {
            if (e && e.classList && e.classList.contains("card"))
                return { type: "card" };
        },
    });

    //Header component
    editor.DomComponents.addType('header', {
        model: {
            defaults: {
                editable: true,
                tagName: 'h1',
                droppable: true,

            },
        },

        isComponent: function (e) {
            if (e && e.classList && e.classList.contains("header"))
                return { type: "header" };
        },
    });


    //Paragraph component
    editor.DomComponents.addType('paragraph', {
        model: {
            defaults: {
                editable: true,
                tagName: 'p',
                droppable: true,

            },
        },
        isComponent: function (e) {
            if (e && e.classList && e.classList.contains("paragraph"))
                return { type: "paragraph" };
        },
    });




    //Select component
    editor.DomComponents.addType("select", {
        model: {
            defaults: {
                editable: true,
                tagName: "select",
            },
        },
        isComponent(el) {
            if (el.tagName === "SELECT") {
                return { type: "select" };
            }
        },
    });


    //input component
    editor.DomComponents.addType("Input", {
        model: {
            defaults: {
                editable: true,
                tagName: "Input",
            },
        },
        isComponent(el) {
            if (el.tagName === "INPUT") {
                return { type: "Input" };
            }
        },
    });


    //button component
    editor.DomComponents.addType("Button", {
        model: {
            defaults: {
                editable: true,
                tagName: "Button",
            },
        },
        isComponent(el) {
            if (el.tagName === "BUTTON") {
                return { type: "Button" };
            }
        },
    });


    //column component type
    editor.DomComponents.addType('column', {
        model: {
            defaults: {
                tagName: 'div',
                editable: true,
                draggable: ".row",
                droppable: true,
                resizable: {
                    updateTarget: (el, rect, opt) => {
                        const selected = editor.getSelected();
                        if (!selected) {
                            return;
                        }

                        //compute the current screen size (bootstrap semantic)
                        const docWidth = el.getRootNode().body.offsetWidth;
                        let currentSize = "";
                        if (docWidth >= 1200) {
                            currentSize = "xl";
                        } else if (docWidth >= 992) {
                            currentSize = "lg";
                        } else if (docWidth >= 768) {
                            currentSize = "md";
                        } else if (docWidth >= 576) {
                            currentSize = "sm";
                        }

                        //compute the threshold when add on remove 1 col span to the element
                        const row = el.parentElement;
                        const oneColWidth = row.offsetWidth / 12;
                        //the threshold is half one column width
                        const threshold = oneColWidth * 0.5;

                        //check if we are growing or shrinking the column
                        const grow = rect.w > el.offsetWidth + threshold;
                        const shrink = rect.w < el.offsetWidth - threshold;
                        if (grow || shrink) {
                            let testRegexp = new RegExp("^col-" + currentSize + "-\\d{1,2}$");
                            if (!currentSize) {
                                testRegexp = new RegExp("^col-\\d{1,2}$");
                            }
                            let found = false;
                            let sizesSpans = {};
                            let oldSpan = 0;
                            let oldClass = null;
                            for (let cl of el.classList) {
                                if (cl.indexOf("col-") === 0) {
                                    let [c, size, span] = cl.split("-");
                                    if (!span) {
                                        span = size;
                                        size = "";
                                    }
                                    sizesSpans[size] = span;
                                    if (size === currentSize) {
                                        //found the col-XX-99 class
                                        oldClass = cl;
                                        oldSpan = span;
                                        found = true;
                                    }
                                }
                            }

                            if (!found) {
                                const sizeOrder = ["", "xs", "sm", "md", "lg", "xl"];
                                for (let s of sizeOrder) {
                                    if (sizesSpans[s]) {
                                        oldSpan = sizesSpans[s];
                                        found = true;
                                    }
                                    if (s === currentSize) {
                                        break;
                                    }
                                }
                            }

                            let newSpan = Number(oldSpan);
                            if (grow) {
                                newSpan++;
                            } else {
                                newSpan--;
                            }
                            if (newSpan > 12) {
                                newSpan = 12;
                            }
                            if (newSpan < 1) {
                                newSpan = 1;
                            }

                            let newClass = "col-" + currentSize + "-" + newSpan;
                            if (!currentSize) {
                                newClass = "col-" + newSpan;
                            }
                            //update the class
                            selected.addClass(newClass);
                            if (oldClass && oldClass !== newClass) {
                                selected.removeClass(oldClass);
                            }

                        }
                    },
                    tl: 0,
                    tc: 0,
                    tr: 0,
                    cl: 0,
                    cr: 1,
                    bl: 0,
                    bc: 0,
                    br: 0,
                },
            },
        },
        isComponent: function (e) {
            let classes = (e.classList?.value ? e.classList.value : "");
            let IsColumn = classes.includes("col-");
            if (e && e.classList && (e.classList.contains("col") || IsColumn))
                return { type: "col" };
        },
    });

    //Icon Component
    editor.DomComponents.addType('icon-block', {
        model: {
            defaults: {
                tagName: 'i',
                draggable: true,
                droppable: false,
                selectable: true,
                type: 'icon',
                classes: ['fa', 'fa-cube', 'fa-2x'],
            },
        },
    });

    const defaultType = editor.DomComponents.getType('default');
    const defaultModel = defaultType.model;
    const defaultView = defaultType.view;

    editor.DomComponents.addType('list',
        {
            model: {
                defaults: {
                    'custom-name': 'List',
                    tagName: 'ul',
                    resizable: 1,
                    traits: [
                        {
                            type: 'select',
                            options: [
                                { value: 'ul', name: 'No' },
                                { value: 'ol', name: 'Yes' }
                            ],
                            label: 'Ordered?',
                            name: 'tagName',
                            changeProp: 1
                        }
                    ].concat(defaultModel.prototype.defaults.traits)
                },
            },
            isComponent: function (el) {
                if (el && ['UL', 'OL'].includes(el.tagName)) {
                    return { type: 'list' };
                }
            },
        }
    );

    editor.DomComponents.addType('list-item',
        {
            model: {
                defaults: {
                    'custom-name': 'List Item',
                    tagName: 'li',
                },
            },
            isComponent: function (el) {
                if (el && ['li'].includes(el.tagName)) {
                    return { type: 'list-item' };
                }
            },
        }
    );
}
