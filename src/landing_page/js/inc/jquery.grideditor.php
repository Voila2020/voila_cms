<script>
/**
 * Frontwise grid editor plugin.
 */

jQuery.fn.extend({
    unwrapInner: function(selector) {
        return this.each(function() {
            var t = this,
                c = $(t).children(selector);
            if (c.length === 1) {
                c.contents().appendTo(t);
                c.remove();
            }
        });
    }
});

function ColorPicker(object) {
    object.colorpicker();
}

function init_switch() {

}
$removeBtnHtml = "<span class='glyphicon glyphicon-trash'></span> Remove";
$formLists = [];
(function($) {

    $("body").append('<div class="tooTip tool" id="tooTip" style="display: none;">Double click to edit</div>');


    var currentColSelected;
    $.fn.gridEditor = function(options) {

        var self = this;
        var grideditor = self.data('grideditor');
        var tooltipSpan = document.getElementById('tooTip');
        var firstMouseDrga = false;
        /** Methods **/

        if (arguments[0] == 'getHtml') {
            if (grideditor) {
                grideditor.deinit();
                var html = self.html();
                //grideditor.init();
                return html;
            } else {
                return self.html();
            }
        }

        /** Initialize plugin */

        self.each(function(baseIndex, baseElem) {
            baseElem = $(baseElem);

            // Wrap content if it is non-bootstrap
            if (baseElem.children().length && !baseElem.find('div.row').length) {
                var children = baseElem.children();
                var row = $('<div class="row"><div class="col-md-12"/></div>').appendTo(baseElem);
                row.find('.col-md-12').append(children);
            }

            var settings = $.extend({
                'new_row_layouts': [ // Column layouts for add row buttons
                    [12],
                    [6, 6],
                    [4, 4, 4],
                    [3, 3, 3, 3],
                    [2, 2, 2, 2, 2, 2],
                    [2, 8, 2],
                    [4, 8],
                    [8, 4]
                ],
                'row_classes': [{
                    label: 'Example class',
                    cssClass: 'example-class'
                }],
                'col_classes': [{
                    label: 'Example class',
                    cssClass: 'example-class'
                }],
                'col_tools': [],
                /* Example:
                                [ {
                                title: 'Set background image',
                                iconClass: 'glyphicon-picture',
                                on: { click: function() {} }
                                } ]
                                */
                'row_tools': [],
                'custom_filter': '',
                'content_types': ['tinymce'],
                'valid_col_sizes': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                'source_textarea': '',
                'widgets_section': ["ge-add-text", "ge-add-form", "ge-add-image"]
            }, options);

            // Elems
            var canvas,
                mainControls,
                addRowGroup,
                htmlTextArea,
                widgetSection;
            var colClasses = ['col-md-', 'col-sm-', 'col-xs-'];
            var curColClassIndex = 0; // Index of the column class we are manipulating currently
            var MAX_COL_SIZE = 12;

            setup();
            init();

            function setup() {
                /* Setup canvas */
                canvas = baseElem.addClass('ge-canvas');

                if (settings.source_textarea) {
                    var sourceEl = $(settings.source_textarea);

                    sourceEl.addClass('ge-html-output');
                    htmlTextArea = sourceEl;

                    if (sourceEl.val()) {
                        self.html(sourceEl.val());
                    }
                }

                if (typeof htmlTextArea === 'undefined' || !htmlTextArea.length) {
                    htmlTextArea = $('<textarea class="ge-html-output"/>').insertBefore(canvas);
                }

                /* Create main controls*/
                mainControls = $('<div class="ge-mainControls" />').insertBefore(htmlTextArea).wrap(
                    "<div class='container'></div>");
                var wrapper = $('<div class="ge-wrapper ge-top" />').appendTo(mainControls);

                // Add row
                addRowGroup = $('<div class="ge-addRowGroup btn-group" />').appendTo(wrapper);
                $.each(settings.new_row_layouts, function(j, layout) {
                    var btn = $('<a class="btn btn-xs btn-primary" />')
                        .attr('title', 'Add row ' + layout.join('-'))
                        .on('click', function() {
                            var row = createRow().appendTo(canvas);
                            layout.forEach(function(i) {
                                createColumn(i).appendTo(row);
                            });
                            row = row.wrap("<section class='ge-sect'>").wrap(
                                "<div class='container bs-container'></div>");
                            init();
                        })
                        .appendTo(addRowGroup);

                    btn.append('<span class="glyphicon glyphicon-plus-sign"/>');

                    var layoutName = layout.join(' - ');
                    var icon = '<div class="row ge-row-icon">';
                    layout.forEach(function(i) {
                        icon += '<div class="column col-xs-' + i + '"/>';
                    });
                    icon += '</div>';
                    btn.append(icon);
                });

                widgetSection = $('<div class="ge-widget-section"/>').appendTo(mainControls);

                // generate wudgets buttons
                $.each(settings.widgets_section, function(j, layout) {
                    $ico = "";
                    $title = "";

                    // set icon and title foreach widget
                    switch (settings.widgets_section[j]) {

                        case "ge-add-text": {
                            $ico = $("<i/>").attr("class", "glyphicon glyphicon-text-height");
                            $title = "Insert Text";
                            break;
                        }
                        case "ge-add-social": {
                            $ico = $("<i/>").attr("class", "glyphicon glyphicon-link");
                            $title = "Insert Social Links";
                            break;
                        }
                        case "ge-add-form": {
                            $ico = $("<i/>").attr("class", "glyphicon glyphicon-check");
                            $title = "Insert Form";
                            break;
                        }
                        case "ge-add-image": {
                            $title = "Insert Image";
                            $ico = $("<i/>").attr("class", "glyphicon glyphicon-picture");
                            break;
                        }

                    }

                    // create <a> tag and add icon and set title
                    var widEx = $("<a/>").attr("href", "javascript:void(0);").attr("class",
                        "btn btn-default ge-wid-sect-item-drag grab").addClass(settings
                        .widgets_section[j]).html($ico);
                    widEx.draggable({
                        revert: true,
                        helper: 'clone',
                        start: function() {
                            $(".ui-draggable-dragging").removeClass("grab")
                                .addClass("grabbing");
                        }
                    });
                    widEx.attr("title", $title);
                    widEx.appendTo(widgetSection);
                });

                dropable_content();
                // Buttons on right
                var layoutDropdown = $('<div class="dropdown pull-right ge-layout-mode">' +
                        '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span>Desktop</span></button>' +
                        '<ul class="dropdown-menu" role="menu">' +
                        '<li><a data-width="auto" title="Desktop"><span>Desktop</span></a></li>' +
                        '<li><a title="Tablet"><span>Tablet</span></li>' +
                        '<li><a title="Phone"><span>Phone</span></a></li>' +
                        '</ul>' +
                        '</div>')
                    .on('click', 'a', function() {
                        var a = $(this);
                        switchLayout(a.closest('li').index());
                        var btn = layoutDropdown.find('button');
                        btn.find('span').remove();
                        btn.append(a.find('span').clone());
                    })
                    .appendTo(wrapper);
                var btnGroup = $('<div class="btn-group pull-right"/>')
                    .appendTo(wrapper);
                var htmlButton = $(
                        '<button title="Edit Source Code" type="button" class="btn btn-xs btn-default gm-edit-mode"><span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-right"></span></button>'
                    )
                    .on('click', function() {
                        if (htmlButton.hasClass('active')) {
                            canvas.empty().html(htmlTextArea.val()).show();
                            init();
                            htmlTextArea.hide();
                        } else {
                            deinit();
                            htmlTextArea
                                .height(0.8 * $(window).height())
                                .val(canvas.html())
                                .show();
                            canvas.hide();
                        }

                        htmlButton.toggleClass('active btn-danger');
                    })
                    .appendTo(btnGroup);
                var previewButton = $(
                        '<button title="Preview" type="button" class="btn btn-xs btn-default gm-preview"><span class="glyphicon glyphicon-eye-open"></span></button>'
                    )

                    .on('click', function() {
                        previewButton.toggleClass('active btn-danger');
                        if (!previewButton.hasClass('active')) {
                            canvas.addClass('ge-editing');
                            $(".well-bar,.ge-widget-section,.ge-layout-mode,.gm-edit-mode").show();
                            $(".ge-addRowGroup").css({
                                "visibility": "visible"
                            });
                            $(".ge-mainControls").removeClass("fixed-bar-top");
                            $("#scratchGrid").css("background", "");
                            $("#scratchGrid").css("min-height", "");
                            applySettingRowInViewMode(canvas, false);
                            applySettingColInViewMode(canvas, false);
                        } else {
                            $(".well-bar,.ge-widget-section,.ge-layout-mode,.gm-edit-mode").hide();
                            $(".ge-addRowGroup").css({
                                "visibility": "hidden"
                            });
                            canvas.removeClass('ge-editing');
                            $(".ge-mainControls").addClass("fixed-bar-top")
                            applySettingRowInViewMode(canvas, true);
                            applySettingColInViewMode(canvas, true);
                            $bg_temp = $("#image-background-div").css("background");

                            if ($bg_temp) {
                                $("#scratchGrid").css("background", $bg_temp);
                                $("#scratchGrid").css("min-height", "100vh");
                            }

                        }
                    })

                    .appendTo(btnGroup);

                // Make controls fixed on scroll
                var $window = $(window);
                $window.on('scroll', function(e) {
                    if (
                        $window.scrollTop() > mainControls.offset().top &&
                        $window.scrollTop() < canvas.offset().top + canvas.height()
                    ) {
                        if (wrapper.hasClass('ge-top')) {
                            wrapper
                                .css({
                                    left: wrapper.offset().left,
                                    width: wrapper.outerWidth(),
                                })
                                .removeClass('ge-top')
                                .addClass('ge-fixed');
                        }
                    } else {
                        if (wrapper.hasClass('ge-fixed')) {
                            wrapper
                                .css({
                                    left: '',
                                    width: ''
                                })
                                .removeClass('ge-fixed')
                                .addClass('ge-top');
                        }
                    }
                });

                /* Init RTE on click */
                canvas.on('click', '.ge-content', function(e) {
                    var rte = getRTE($(this).data('ge-content-type'));
                    if (rte) {
                        rte.init(settings, $(this));
                    }
                });
            }

            function reset() {
                deinit();
                init();
            }

            function init() {
                runFilter(true);
                canvas.addClass('ge-editing');
                addAllColClasses();
                wrapContent();
                createRowControls();
                createColControls();
                makeSortable();
                switchLayout(curColClassIndex);
                dropable_content(createRow, createColumn, init);
            }

            function deinit() {
                canvas.removeClass('ge-editing');
                var contents = canvas.find('.ge-content').each(function() {
                    var content = $(this);
                    //getRTE(content.data('ge-content-type')).deinit(settings, content);
                });
                canvas.find('.ge-tools-drawer').remove();
                //removeSortable();
                //runFilter();
            }

            function createRowControls() {
                canvas.find('.row').each(function() {
                    var row = $(this);
                    if (row.find('> .ge-tools-drawer').length) {
                        return;
                    }

                    var drawer = $('<div class="ge-tools-drawer" />').prependTo(row);
                    createTool(drawer, 'Move', 'ge-move', 'glyphicon-move');
                    createTool(drawer, 'Settings', '', 'glyphicon-cog', function() {
                        currentColSelected = row;
                        openSettings();
                    });
                    settings.row_tools.forEach(function(t) {
                        createTool(drawer, t.title || '', t.className || '', t
                            .iconClass || 'glyphicon-wrench', t.on);
                    });
                    createTool(drawer, 'Remove row', '', 'glyphicon-trash', function() {
                        row.slideUp(function() {
                            row.remove();
                        });
                    });
                    createTool(drawer, 'Add column', 'ge-add-column', 'glyphicon-plus-sign',
                        function() {
                            row.append(createColumn(3));
                            init();
                        });

                    var details = createDetails(row, settings.row_classes).appendTo(drawer);
                });
            }

            function applySettingRowInViewMode(obj, view) {

                if (obj !== undefined && obj !== "" && view !== undefined) {
                    if (view === true) {

                        $("body").contents().find(".row.ge-row").each(function() {

                            $bg_mode = $(this).attr("data-bg-mode");
                            $bg_value = $(this).attr("data-bg-value");
                            $row_mode = $(this).attr("data-row-mode");
                            $content_mode = $(this).attr("data-content-mode");
                            $style = $(this).data("style");
                            if ($style === undefined) {
                                $style = "";
                            }

                            if ($bg_mode === "color" && $bg_value !== "") {
                                //$style += "background-color:" + $bg_value + ";-webkit-background-clip:content-box;background-clip:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style += "background-color:" + $bg_value +
                                    ";background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($bg_mode === "image" && $bg_value !== "") {
                                $style += "background-image:url(" + $bg_value + ");";
                                //$style += "background-size:cover;-webkit-background-origin:content-box;background-origin:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style +=
                                    "background-size:cover;background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($row_mode == "full-row") {
                                $(this).parent(".bs-container").parent(".ge-sect").attr("style",
                                    $style);
                            }
                            if ($row_mode == "container") {
                                $(this).parent(".bs-container").attr("style", $style);
                            }

                        });
                    } else {
                        $("body").contents().find(".row.ge-row").each(function() {
                            $(this).parent(".bs-container").removeAttr("style");
                            $(this).parent(".bs-container").parent(".ge-sect").removeAttr(
                                "style");
                        });
                    }
                }
            }

            function applySettingColInViewMode(obj, view) {
                if (obj !== undefined && obj !== "" && view !== undefined) {

                    if (view === true) {


                        $("body").children().find(".column[class^='col-']").each(function() {

                            $bg_mode = $(this).attr("data-bg-mode");
                            $bg_value = $(this).attr("data-bg-value");
                            $row_mode = $(this).attr("data-row-mode");
                            $content_mode = $(this).attr("data-content-mode");
                            $style = $(this).attr("data-style");
                            if ($style === undefined) {
                                $style = "";
                            }
                            if ($bg_mode === "color" && $bg_value !== "") {
                                //$style += "background-color:" + $bg_value + ";-webkit-background-clip:content-box;background-clip:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style += "background-color:" + $bg_value +
                                    ";background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($bg_mode === "image" && $bg_value !== "") {
                                $style += "background-image:url(" + $bg_value + ");";
                                //$style += "background-size:cover;-webkit-background-origin:content-box;background-origin:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style +=
                                    "background-size:cover;background-position:center center;background-repeat:no-repeat;";
                            }

                            $(this).attr("style", $style);
                            if ($style !== undefined) {
                                $styleDataArray = $style.split(";");
                                for (var i = 0; i < $styleDataArray.length; i++) {
                                    $itemStyle = $styleDataArray[i].split(":");
                                    if ($itemStyle[0] !== undefined && $itemStyle[1] !==
                                        undefined) {

                                        if ($itemStyle[0] === "padding-left" || $itemStyle[
                                                0] === "padding-right") {
                                            $(this).css($itemStyle[0], (parseInt($itemStyle[1]
                                                .replace("px", "")) + 15) + "px");
                                        } else {
                                            $(this).css($itemStyle[0], $itemStyle[1]);
                                        }

                                    }
                                }
                            }

                        });
                    } else {

                        $("body").children().find(".column[class^='col-']").each(function() {
                            $(this).removeAttr("style");
                            $(this).children(".ge-content:first").removeAttr("style");
                        });
                    }
                }
            }


            function createBoxBgToRow(dataBg, obj, withoutSave) {
                if (withoutSave === undefined) {
                    withoutSave = false;
                }
                if (withoutSave === true) {
                    // debugger;
                    if (dataBg !== "" && dataBg !== undefined) {
                        var dataBgArray = dataBg.split(":");
                        if (dataBgArray[1] !== "" && dataBgArray[1] !== undefined) {
                            $val = dataBgArray[1];

                            $image = $val;
                            $imageBox = $("<div/>").addClass("image-box-row").append($("<a/>").attr(
                                "href", $image).addClass("html5lightbox").attr("data-thumbnail",
                                $image).append($("<img/>").attr("src", $image).addClass(
                                "img-responsive")));
                            $('#BackgroundRowModal #bg-image .image-box-row').remove();
                            $imageBox.insertAfter('#BackgroundRowModal #bg-image #bg_image_id');
                            $("#bg_image_id").val($val);
                        }
                    }
                    return false;
                }
                if (dataBg !== "" && dataBg !== undefined) {
                    var dataBgArray = dataBg.split(":");
                    if (dataBgArray[1] !== "" && dataBgArray[1] !== undefined) {
                        $val = dataBgArray[1];
                        $valA = $val.split("/");

                        if (dataBgArray[0] === "bg-img") {
                            $.ajax({
                                url: "../ajax_page_builder/getImagePath.php",
                                data: {
                                    id: $valA[$valA.length - 1]
                                },
                                type: "post",
                                success: function(data) {
                                    if (data !== "") {
                                        obj.children().find(".image-box-row").remove();
                                        $imageBox = $("<div/>").addClass("image-box-row")
                                            .append($("<a/>").attr("href", data).addClass(
                                                "html5lightbox").attr("data-thumbnail",
                                                data).append($("<img/>").attr("src",
                                                data).addClass("img-responsive")));
                                        obj.children(".ge-tools-drawer").append($imageBox);
                                        $(".html5lightbox").html5lightbox();
                                        obj.children().find(".color-box-row").remove();
                                    }
                                }
                            });
                        } else if (dataBgArray[0] === "bg-color") {
                            if ($val !== "" && $val !== undefined) {
                                obj.children().find(".color-box-row").remove();
                                $colorBox = $("<div/>").addClass("color-box-row").css("background",
                                    $val);
                                obj.children(".ge-tools-drawer:first").append($colorBox);
                            } else {
                                obj.children().find(".color-box-row").remove();
                            }
                            obj.children().find(".image-box-row").remove();
                        }
                    } else {
                        obj.children().find(".color-box-row").remove();
                        obj.children().find(".image-box-row").remove();
                    }
                }
            }

            function openSettings() {

                $("#BackgroundRowModal .modal-body").html(`  <div class="row">
                <div class="col-sm-12">
                    <div>
                        <!-- Nav tabs -->
                        <ul 
                            class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#style_dom_ext" aria-controls="style_dom_ext" role="tab" data-toggle="tab"> style </a></li>
                            <li role="presentation"><a href="#settings_dom_ext" aria-controls="settings_dom_ext" role="tab" data-toggle="tab"> Settings </a></li>                
                            <li role="presentation"><a href="#visible_cols_dom_ext" aria-controls="visible_cols_dom_ext" role="tab" data-toggle="tab">Visible cols</a></li>                
                            
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style='margin-top:20px;'>
                            <div role="tabpanel" class="tab-pane active" id="style_dom_ext">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label for="cssClassSetting" class="col-sm-3 control-label">Css Class</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="cssClassSetting">
                                        </div>
                                    </div>
                                    <div class="form-group" id="BackGroundTypeDiv">
                                        <label class="col-xs-3 control-label" for="BackgroundType"> background :</label>
                                        <div class="col-xs-7"> <input id="BackgroundType" class="switch-btn" type="checkbox" value="1" checked="" data-on-text=" color " data-off-text=" image " data-on-color="danger"></div>
                                    </div>
                                    <div class="form-group" id="bg-color">
                                        <label class="control-label col-xs-3" for="BackgroundColor">Background Color:</label>
                                        <div class="col-xs-7">
                                            <div id="BackgroundColor" class="input-group colorpicker-component">
                                                <input type="text" value="" class="form-control" />
                                                <span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="bg-image" style="display:none;">
                                        <label class="control-label col-xs-3" for="BackgroundColor">Background Image:</label>
                                        <div class="col-xs-9">
                                            <div class="row">
                                                <div class="col-xs-6" style='padding:0px'>
                                                    <a href= "javascript:void(0);" class="photoManagerBackground btn btn-default"> Open File Manager </a>
                                                </div>
                                                <div class="col-xs-6">
                                                    <input type="hidden" readonly class='form-control' id="bg_image_id">
                                                </div>
                                            </div>                                                                                                             
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-xs-3" for="CustomeMinHeight">Min Height:</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">                    
                                                <input type="number" class="form-control" min="0" id="CustomeMinHeight">
                                                <span class="input-group-addon">px</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="control-label col-xs-3" for="CustomeMarginTop" style="padding-top:30px"> margin :</label>

                                        <div class="col-sm-7">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="input-group">                                                                                                            
                                                        <div style="width:25%" class="col-sm-4">Top</div>
                                                        <div style="width:25%" class="col-sm-4">Bottom</div>
                                                        <div style="width:25%" class="col-sm-4">Right</div>                                            
                                                        <div style="width:25%" class="col-sm-4">Left</div>
                                                        <span class="input-group-addon" style="border:0px;background:none">&nbsp;</span>
                                                    </div>                                        
                                                    <div class="input-group">                    
                                                        <input type="number" class="form-control" style="width:25%"  id="CustomeMarginTop">
                                                        <input type="number" class="form-control" style="width:25%"  id="CustomeMarginBottom">
                                                        <input type="number" class="form-control" style="width:25%"  id="CustomeMarginRight">                                            
                                                        <input type="number" class="form-control" style="width:25%"  id="CustomeMarginLeft">
                                                        <span class="input-group-addon">px</span>
                                                    </div>
                                                </div>
                                            </div>                                
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-3" for="CustomePaddingLeft"  style="padding-top:30px"> padding :</label>
                                        <div class="col-sm-7">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="input-group">                                                                                                            
                                                        <div style="width:25%" class="col-sm-4">Top</div>
                                                        <div style="width:25%" class="col-sm-4">Bottom</div>
                                                        <div style="width:25%" class="col-sm-4">Right</div>                                            
                                                        <div style="width:25%" class="col-sm-4">Left</div>
                                                        <span class="input-group-addon" style="border:0px;background:none">&nbsp;</span>
                                                    </div>                                        
                                                    <div class="input-group">                    
                                                        <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingTop">
                                                        <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingBottom">
                                                        <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingRight">                                            
                                                        <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingLeft">
                                                        <span class="input-group-addon">px</span>
                                                    </div>
                                                </div>
                                            </div>                                
                                        </div>
                                    </div>

                                </div>           
                            </div>
                            <div role="tabpanel" class="tab-pane" id="settings_dom_ext">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="IdSetting">ID</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="IdSetting">
                                        </div>
                                    </div>
                                    <div class="form-group" id="FullRowMode">
                                        <label class="col-xs-3 control-label" for="BackgroundFull">Full Row:</label>
                                        <div class="col-xs-7"> <input id="BackgroundFull" class="switch-btn" type="checkbox" value="1" data-on-text=" Yes " data-off-text=" No " data-on-color="danger"></div>
                                    </div>
                                    <div class="form-group" style='display: none; ' id="rowContentMode">
                                        <label class="col-xs-3 control-label" for="ContentFullRow">Content Is Full:</label>
                                        <div class="col-xs-7"> <input id="ContentFullRow" class="switch-btn" type="checkbox" value="1" data-on-text=" Yes " data-off-text=" No " data-on-color="danger"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="visible_cols_dom_ext">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="desktop_hidden">Desktop Hide &nbsp;<i class='fa fa-desktop' ></i></label>
                                        <div class="col-sm-8">
                                            <input id="desktop_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tablet_hidden" class="col-sm-4 control-label">Tablet Hide &nbsp;<i class='fa fa-tablet'></i></label>
                                        <div class="col-sm-8">
                                            <input id="tablet_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_hidden" class="col-sm-4 control-label">Mobile Hide &nbsp; <i class='fa fa-mobile-phone'></i></label>
                                        <div class="col-sm-8">
                                            <input id="mobile_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                        </div>
                                    </div>
                                </div>
                            </div>                                        
                        </div>
                    </div>
                </div>
            </div>`)
                $("#BackgroundRowModal").modal();
                $('#SaveBackgroundRow').click(function() {
                    saveSettings();
                    $("#BackgroundRowModal").modal("hide");
                });

                $('.switch-btn').bootstrapSwitch();
                $('#BackgroundFull').on('switchChange.bootstrapSwitch', function(event, state) {
                    if (state === true) {
                        $('#rowContentMode').show();
                    } else {
                        $('#rowContentMode').hide();
                        $('#ContentFullRow').bootstrapSwitch("state", false);
                    }
                });

                $('#BackgroundType').on('switchChange.bootstrapSwitch', function(event, state) {
                    if (state === true) {
                        $('#bg-color').show();
                        $('#bg-image').hide();
                        $("#bg_image_id").val("");
                    } else {
                        $('#bg-color').hide();
                        $('#bg-image').show();
                    }
                });

                $(".photoManagerBackground").click(function() {
                    OpenInsertImageForBackground();
                });

                function OpenInsertImageForBackground() {
                    $("#modalInsertPhotoForBackgroundRow .modal-body").html(
                        `<iframe width="99%" height="400" src="{{url('js/includes/filemanager/dialog.php?type=2&field_id=TempImageSelectedForBackgroundRow&fldr=')}}" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                    );
                    $("#modalInsertPhotoForBackgroundRow").modal();
                }
                $('#modalInsertPhotoForBackgroundRow').on('hidden.bs.modal', function() {
                    $("#TempImageSelectedForBackgroundRow").trigger("change");
                });
                $("#TempImageSelectedForBackgroundRow").change(function() {
                    $val = $(this).val();
                    if ($val && $val != "") {
                        createBoxBgToRow("bg-img:" + $val, currentColSelected, true);
                    }
                    $("#TempImageSelectedForBackgroundRow").val("");
                })



                $oldMode = currentColSelected.attr('data-row-mode');
                if ($oldMode !== undefined && $oldMode !== "") {
                    if ($oldMode === "full-row") {
                        $('#BackgroundFull').bootstrapSwitch("state", true);
                        $('#rowContentMode').show();
                        $content_mode = currentColSelected.attr('data-content-mode');
                        $without_cols_rows = currentColSelected.attr('data-without-rows-cols');
                        if ($content_mode !== undefined && $content_mode !== "") {
                            if ($content_mode === "full") {
                                $('#ContentFullRow').bootstrapSwitch("state", true);

                            } else {
                                $('#ContentFullRow').bootstrapSwitch("state", false);
                            }
                            $('#ContentFullRow').show();
                        }
                    } else {
                        $('#BackgroundFull').bootstrapSwitch("state", false);
                        $('#rowContentMode').hide();
                    }
                }

                if (currentColSelected.parent(".bs-container").length === 0) {
                    $("#FullRowMode").hide();
                    if (currentColSelected.hasClass("ge-row")) {
                        $("#BackGroundTypeDiv").hide();
                        $("#bg-color").hide();
                        $("#bg-image").hide();
                    }
                }

                $bg_mode = currentColSelected.attr('data-bg-mode');
                $bg_value = currentColSelected.attr('data-bg-value');
                if ($bg_value === undefined) {
                    $bg_value = "";
                }

                if ($bg_mode === "image") {
                    $('#BackgroundType').bootstrapSwitch("state", false);
                    $("#bg_image_id").val($bg_value);
                    createBoxBgToRow("bg-img:" + $bg_value, currentColSelected, true);
                } else {
                    $('#BackgroundType').bootstrapSwitch("state", true);
                    $("#BackgroundColor input").val($bg_value);

                }
                ColorPicker($("#BackgroundColor"));
                returnValE($('#CustomeMinHeight'), currentColSelected.attr('data-style'), 'min-height');
                returnValE($('#CustomeMarginTop'), currentColSelected.attr('data-style'), 'margin-top');
                returnValE($('#CustomeMarginBottom'), currentColSelected.attr('data-style'),
                    'margin-bottom');
                returnValE($('#CustomeMarginLeft'), currentColSelected.attr('data-style'),
                    'margin-left');
                returnValE($('#CustomeMarginRight'), currentColSelected.attr('data-style'),
                    'margin-right');
                returnValE($('#CustomePaddingTop'), currentColSelected.attr('data-style'),
                    'padding-top');
                returnValE($('#CustomePaddingBottom'), currentColSelected.attr('data-style'),
                    'padding-bottom');
                returnValE($('#CustomePaddingRight'), currentColSelected.attr('data-style'),
                    'padding-right');
                returnValE($('#CustomePaddingLeft'), currentColSelected.attr('data-style'),
                    'padding-left');

                var dom_id = currentColSelected.attr("data-id");
                if (dom_id !== undefined && dom_id != "") {
                    $("#IdSetting").val(dom_id);
                } else {
                    $("#IdSetting").val("");
                }
                var orginClass = currentColSelected.attr("data-class");
                var orginClassArray = [];
                if (orginClass !== undefined) {
                    orginClassArray = orginClass.split(" ");
                }
                var hidden_md = false;
                var hidden_sm = false;
                var hidden_xs = false;
                for (var i in orginClassArray) {
                    switch (orginClassArray[i]) {
                        case "hidden-md":
                        case "hidden-lg": {
                            hidden_md = true;
                            orginClass = orginClass.replace("hidden-md", "");
                            orginClass = orginClass.replace("hidden-lg", "");
                            break;
                        }
                        case "hidden-sm": {
                            hidden_sm = true;
                            orginClass = orginClass.replace("hidden-sm", "");
                            break;
                        }
                        case "hidden-xs": {
                            hidden_xs = true;
                            orginClass = orginClass.replace("hidden-xs", "");
                            break;
                        }
                    }
                }
                $("#BackgroundRowModal .modal-body #cssClassSetting").val(trim(orginClass));


                $('#BackgroundRowModal .modal-body #desktop_hidden').bootstrapSwitch("state",
                    hidden_md);
                $('#BackgroundRowModal .modal-body #tablet_hidden').bootstrapSwitch("state", hidden_sm);
                $('#BackgroundRowModal .modal-body #mobile_hidden').bootstrapSwitch("state", hidden_xs);


            }

            function addGeFieldDataObject(col, attr, value) {
                if (value && value != "") {
                    col.children(".ge-content").attr(attr, value);
                } else {
                    col.children(".ge-content").removeAttr(attr);
                }

            }
            $('body').on('mousemove', '[data-fe-type]', function(e) {
                var x = e.clientX,
                    y = e.clientY;
                if (firstMouseDrga === false) {
                    tooltipSpan.style.display = "block";
                    firstMouseDrga = true;
                }
                tooltipSpan.style.top = (y + 20) + 'px';
                tooltipSpan.style.left = (x + 20) + 'px';
            });
            $('body').on('mouseleave', '[data-fe-type]', function(e) {
                tooltipSpan.style.display = "none";
                firstMouseDrga = false;
            });
            $('body').on('dblclick', '[data-fe-type]', function(e) {
                $type = $(this).data("fe-type");
                currentColSelected = $(this).parent(".column");
                if ($type !== "" && $type !== undefined) {
                    switch ($type) {
                        case "widget": {
                            EditWidgetModal(currentColSelected);
                            break;
                        }
                        case "link": {
                            openLink();
                            break;
                        }
                        case "text": {
                            EditTextModal(currentColSelected);
                            break;
                        }
                        case "image": {

                            OpenEditImage();
                            break;
                        }
                        case "map": {
                            $formula = $(this).children("[data-widget]").data("widget");
                            $valId = "";
                            if ($formula !== undefined || $formula !== "") {
                                widgetFormula = String($formula);
                                $widget_id_formula = widgetFormula.match(
                                    /##wid_con_start##.*##wid_con_end##/g);
                                $img_id = $widget_id_formula[0].replace("##wid_con_start##", "")
                                    .replace("##wid_con_end##", "");
                                $valId = $img_id.split("=")[1];
                            }
                            OpenMaps($valId);
                            break;
                        }
                        case "form": {
                            $form_id = currentColSelected.children("[data-form-id]").data(
                                "form-id");

                            OpenFormInsert($form_id);
                            break;
                        }
                        case "tabs": {
                            var inner_data = currentColSelected.children(".ge-content").html();
                            OpenTabs(inner_data);
                            break;
                        }
                        case "accordion": {
                            var inner_data = currentColSelected.children(".ge-content").html();
                            OpenAccordion(inner_data);
                            break;
                        }
                        case "timeline": {
                            var inner_data = currentColSelected.children(".ge-content").html();
                            open_time_line(inner_data);
                            break;
                        }
                    }
                }
            });

            function dropable_content(createRow, createColumn, init) {
                $(".ge-content,.bottomDrop").droppable({
                    accept: ".ge-wid-sect-item-drag",
                    drop: function(event, ui) {
                        $this = $(this);
                        if ($(event.target).hasClass("bottomDrop") === true) {
                            var row = createRow();
                            currentColSelected = createColumn(12).appendTo(row);
                            row.insertBefore($(event.target));
                            init();
                            $this = row.find(".ge-content");
                        }
                        $ext = $(ui)[0].helper;
                        $able_drop = check_type_widget_sect($this, "drop", $ext);
                        $this.css({
                            "background-color": "inherit"
                        });
                        if ($able_drop === true) {
                            currentColSelected = $this.parent(".column");
                            if ($ext.hasClass("ge-add-text") === true) {
                                EditTextModal(currentColSelected);
                            }
                            if ($ext.hasClass("ge-add-image") === true) {
                                OpenEditImage();
                            }
                            if ($ext.hasClass("ge-add-widget") === true) {
                                EditWidgetModal(currentColSelected);
                            }
                            if ($ext.hasClass("ge-add-link") === true) {
                                openLink();
                            }
                            if ($ext.hasClass("ge-add-map") === true) {
                                OpenMaps();
                            }
                            if ($ext.hasClass("ge-add-tabs") === true) {
                                OpenTabs();
                            }
                            if ($ext.hasClass("ge-add-accordion") === true) {
                                OpenAccordion();
                            }
                            if ($ext.hasClass("ge-add-form") === true) {
                                OpenFormInsert();
                            }
                            if ($ext.hasClass("ge-add-timeline") === true) {
                                open_time_line();
                            }


                            $ext.hide();
                        }

                    },
                    over: function(event, ui) {
                        $ext = $(ui)[0].helper;
                        $(this).css({
                            "background-color": "#f4f4f4"
                        });
                        check_type_widget_sect($(this), "over", $ext);
                    },
                    out: function(event, ui) {
                        $ext = $(ui)[0].helper;
                        $(this).css({
                            "background-color": "inherit"
                        });
                        check_type_widget_sect($(this), "out", $ext);
                    }
                });
            }

            function check_type_widget_sect(obj, type_event, helper) {
                if (obj !== undefined) {
                    $type = obj.attr("data-fe-type");
                    if (type_event === "out") {
                        helper.css({
                            "cursor": ""
                        });
                    } else {
                        if ($type === undefined || $type === "") {
                            if (type_event === "over") {
                                helper.css({
                                    "cursor": ""
                                });
                            }
                            if (type_event === "drop") {
                                return true;
                            }
                        } else {
                            if (type_event === "over") {
                                helper.css({
                                    "cursor": "no-drop"
                                });
                            }
                            if (type_event === "drop") {
                                return false;
                            }
                        }
                    }

                }
            }

            function trim(stringToTrim) {
                if (stringToTrim !== undefined) {
                    return stringToTrim.replace(/^\s+|\s+$/g, "");
                }
                return "";
            }

            function checkValE(obj, styleProperties) {
                if (obj.val() !== undefined && obj.val() !== "") {
                    var ext = "";
                    var ex = obj.parent("div").children(".input-group-addon").text();
                    if (ex !== undefined && ex !== "") {
                        ext = ex;
                    }

                    return styleProperties + ":" + obj.val() + ext + ";";
                }
                return "";
            }

            function returnValE(obj, styles, styleProperties) {
                if (styles !== "" && styles !== undefined) {
                    var arrayStyles = styles.split(";");
                    for (var i = 0; i < arrayStyles.length; i++) {
                        var style = arrayStyles[i].split(":");
                        if (style[0] === styleProperties) {
                            obj.val(style[1].replace("px", ""));
                            break;
                        }
                    }
                }
            }

            function saveSettings() {
                if ($('#BackgroundFull').bootstrapSwitch("state") === true) {
                    currentColSelected.attr("data-row-mode", "full-row");

                } else {
                    currentColSelected.attr("data-row-mode", "container");
                }
                if ($('#ContentFullRow').bootstrapSwitch("state") === true) {
                    currentColSelected.attr("data-content-mode", "full");
                    currentColSelected.parent(".bs-container").removeClass("container").addClass(
                        "container-fluid");
                } else {
                    currentColSelected.attr("data-content-mode", "container");
                    currentColSelected.parent(".bs-container").removeClass("container-fluid").addClass(
                        "container");
                }
                if ($('#BackgroundType').bootstrapSwitch("state") === true) {
                    currentColSelected.attr("data-bg-mode", "color");
                    currentColSelected.attr("data-bg-value", $("#BackgroundColor input").val());

                } else {
                    currentColSelected.attr("data-bg-mode", "image");
                    currentColSelected.attr("data-bg-value", $("#bg_image_id").val());
                }
                $styles = "";
                $styles += checkValE($('#CustomeMinHeight'), "min-height");
                $styles += checkValE($('#CustomeMarginTop'), "margin-top");
                $styles += checkValE($('#CustomeMarginBottom'), "margin-bottom");
                $styles += checkValE($('#CustomeMarginLeft'), "margin-left");
                $styles += checkValE($('#CustomeMarginRight'), "margin-right");
                $styles += checkValE($('#CustomePaddingTop'), "padding-top");
                $styles += checkValE($('#CustomePaddingBottom'), "padding-bottom");
                $styles += checkValE($('#CustomePaddingRight'), "padding-right");
                $styles += checkValE($('#CustomePaddingLeft'), "padding-left");

                if ($styles !== "") {
                    currentColSelected.attr("data-style", $styles);
                } else {

                    currentColSelected.removeAttr("data-style");

                }
                var dom_id = $("#IdSetting").val();
                if (dom_id !== undefined && dom_id != "") {
                    currentColSelected.attr("data-id", $dom_id);
                } else {
                    currentColSelected.removeAttr("data-id");
                }
                $classes = "";
                $classes += $("#cssClassSetting").val() + " ";
                var hidden_md = $('#BackgroundRowModal .modal-body #desktop_hidden').bootstrapSwitch(
                    "state");
                var hidden_sm = $('#BackgroundRowModal .modal-body #tablet_hidden').bootstrapSwitch(
                    "state");
                var hidden_xs = $('#BackgroundRowModal .modal-body #mobile_hidden').bootstrapSwitch(
                    "state");

                if (hidden_md === true) {
                    $classes += "hidden-md" + " ";
                    $classes += "hidden-lg" + " ";
                }
                if (hidden_sm === true) {
                    $classes += "hidden-sm" + " ";
                }
                if (hidden_xs === true) {
                    $classes += "hidden-xs" + " ";
                }
                $classes = $classes.replace(/\s+$/, '')
                if ($classes !== "") {
                    currentColSelected.attr("data-class", $classes);
                }

            }

            function createColControls() {
                canvas.find('.column').each(function() {
                    var col = $(this);
                    if (col.find('> .ge-tools-drawer').length) {
                        return;
                    }

                    var drawer = $('<div class="ge-tools-drawer" />').prependTo(col);

                    createTool(drawer, 'Move', 'ge-move', 'glyphicon-move');

                    createTool(drawer, 'Make column narrower\n(hold shift for min)',
                        'ge-decrease-col-width', 'glyphicon-minus',
                        function(e) {
                            var colSizes = settings.valid_col_sizes;
                            var curColClass = colClasses[curColClassIndex];
                            var curColSizeIndex = colSizes.indexOf(getColSize(col,
                                curColClass));
                            var newSize = colSizes[clamp(curColSizeIndex - 1, 0, colSizes
                                .length - 1)];
                            if (e.shiftKey) {
                                newSize = colSizes[0];
                            }
                            setColSize(col, curColClass, Math.max(newSize, 1));
                        });

                    createTool(drawer, 'Make column wider\n(hold shift for max)',
                        'ge-increase-col-width', 'glyphicon-plus',
                        function(e) {
                            var colSizes = settings.valid_col_sizes;
                            var curColClass = colClasses[curColClassIndex];
                            var curColSizeIndex = colSizes.indexOf(getColSize(col,
                                curColClass));
                            var newColSizeIndex = clamp(curColSizeIndex + 1, 0, colSizes
                                .length - 1);
                            var newSize = colSizes[newColSizeIndex];
                            if (e.shiftKey) {
                                newSize = colSizes[colSizes.length - 1];
                            }
                            setColSize(col, curColClass, Math.min(newSize, MAX_COL_SIZE));
                        });

                    createTool(drawer, 'Settings', '', 'glyphicon-cog', function() {
                        currentColSelected = col;
                        openSettings();
                    });

                    createTool(drawer, 'Clear Content', '', 'glyphicon-repeat', function() {
                        addGeFieldDataObject(col, "data-fe-type", "");
                        col.children(".ge-content").html("");
                    });

                    createTool(drawer, 'Remove col', '', 'glyphicon-trash', function() {
                        col.animate({
                            opacity: 'hide',
                            width: 'hide',
                            height: 'hide'
                        }, 400, function() {
                            col.remove();
                        });
                    });


                    createTool(drawer, 'Add row', 'ge-add-row', 'glyphicon-plus-sign',
                        function() {
                            var row = createRow();
                            col.append(row);
                            row.append(createColumn(6)).append(createColumn(6));

                            init();
                        });

                    var details = createDetails(col, settings.col_classes).appendTo(drawer);
                });
            }

            function OpenFormInsert(form_id) {

                if (form_id === null || form_id === undefined) {
                    form_id = "";
                }

                $.ajax({
                    url: "<?= url('/landingPage/getAllForms') ?>",
                    type: "get",
                    success: function(data) {
                        $select = $("<select/>").addClass("form-control").attr("id",
                            "form_selected");
                        $option = $("<option/>").val("").text("");
                        $select.append($option);
                        if (data.data.length) {
                            $formLists = data.data;
                            for (var x in data.data) {
                                $option = $("<option/>").val(data.data[x]['id']).text(data
                                    .data[x]['name']);
                                if (parseInt(form_id) === parseInt(data.data[x]['id'])) {
                                    $option.prop("selected", true);
                                }
                                $select.append($option)
                            }
                        }
                        $("#FormInserting .modal-body .ck-content").html($select);
                        $("#formID").val(form_id);
                        $("#FormInserting").modal();
                    }
                });
            }
            $("#InsertFormBtn").click(function() {
                $form_id = $("#form_selected").val();
                // debugger
                if ($form_id !== "" && $form_id !== undefined) {

                    $formRow = get_row_form($form_id);
                    if ($formRow) {
                        $content = $formRow["shortact"];
                        currentColSelected.children(".ge-content").html($content);
                        addGeFieldDataObject(currentColSelected, "data-fe-type", "form");
                        addGeFieldDataObject(currentColSelected, "data-form-id", $form_id);
                        $("#FormInserting").modal("hide");
                        $("#formID").val("");
                    }

                }
            });

            function get_row_form($id) {
                for (var x in $formLists) {
                    if (parseInt($formLists[x]["id"]) === parseInt($id)) {
                        return $formLists[x];
                    }
                }
            }

            function OpenEditImage() {
                $("#modalInsertPhoto .modal-body").html(
                    `<iframe width="100%" height="400" src="<?= url('js/includes/filemanager/dialog.php?type=2&field_id=TempImageSelected&fldr=') ?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`
                );
                $("#modalInsertPhoto").modal();
                $()
            }
            $('#modalInsertPhoto').on('hidden.bs.modal', function() {
                $("#TempImageSelected").trigger("change");
            });
            $("#TempImageSelected").change(function() {
                $val = $(this).val();
                if ($val && $val != "") {
                    currentColSelected.children(".ge-content").html("<img src='" + $val +
                        "' alt='" + $val + "' class='img-responsive'>");
                    addGeFieldDataObject(currentColSelected, "data-fe-type", "image");
                }
            })

            function EditTextModal(col) {
                tinyMCE.activeEditor.setContent(currentColSelected.children(".ge-content").html());
                $removeBtn = $('<button/>').attr("type", "button").attr("class",
                    "btn btn-danger pull-left").attr("id", "widRemoveText").html($removeBtnHtml);
                $('#widRemoveText').remove();
                if (col.children(".ge-content").html() !== "") {
                    $('#InsetTextModal .modal-footer').append($removeBtn);
                    $('#widRemoveText').click(function() {
                        col.children(".ge-content").html("");
                        addGeFieldDataObject(col, "data-fe-type", "");
                        // disableButtons(col, "data-fe-type");
                        $('#InsetTextModal').modal("hide");
                    });
                }
                $('#InsetTextModal').modal("show");

                // $(".fade.show").css("opacity","1");

            }
            $('body').on('click', '#InsertText', function() {
                var contents = tinyMCE.activeEditor.getContent();
                currentColSelected.children(".ge-content").html(contents);
                $('#InsetTextModal').modal("hide");
                if (contents !== "" && contents !== "<p></p>") {
                    addGeFieldDataObject(currentColSelected, "data-fe-type", "text");
                } else {
                    addGeFieldDataObject(currentColSelected, "data-fe-type", "");
                }
                //disableButtons(currentColSelected, "data-fe-type");
            });

            function createTool(drawer, title, className, iconClass, eventHandlers) {
                var tool = $('<a title="' + title + '" class="' + className +
                        '"><span class="glyphicon ' + iconClass + '"></span></a>')
                    .appendTo(drawer);
                if (typeof eventHandlers == 'function') {
                    tool.on('click', eventHandlers);
                }
                if (typeof eventHandlers == 'object') {
                    $.each(eventHandlers, function(name, func) {
                        tool.on(name, func);
                    });
                }
            }

            function createDetails(container, cssClasses) {
                var detailsDiv = $('<div class="ge-details" />');

                $('<input class="ge-id" />')
                    .attr('placeholder', 'id')
                    .val(container.attr('id'))
                    .attr('title', 'Set a unique identifier')
                    .appendTo(detailsDiv);

                var classGroup = $('<div class="btn-group" />').appendTo(detailsDiv);
                cssClasses.forEach(function(rowClass) {
                    var btn = $('<a class="btn btn-xs btn-default" />')
                        .html(rowClass.label)
                        .attr('title', rowClass.title ? rowClass.title : 'Toggle "' + rowClass
                            .label + '" styling')
                        .toggleClass('active btn-primary', container.hasClass(rowClass
                            .cssClass))
                        .on('click', function() {
                            btn.toggleClass('active btn-primary');
                            container.toggleClass(rowClass.cssClass, btn.hasClass(
                                'active'));
                        })
                        .appendTo(classGroup);
                });

                return detailsDiv;
            }

            function addAllColClasses() {
                canvas.find('.ge-row>.column,.ge-row>div[class*="col-"]').each(function() {
                    var col = $(this);

                    var size = 2;
                    var sizes = getColSizes(col);
                    if (sizes.length) {
                        size = sizes[0].size;
                    }

                    var elemClass = col.attr('class');
                    colClasses.forEach(function(colClass) {
                        if (elemClass.indexOf(colClass) == -1) {
                            col.addClass(colClass + size);
                        }
                    });

                    col.addClass('column');
                });
            }

            /**
             * Return the column size for colClass, or a size from a different
             * class if it was not found.
             * Returns null if no size whatsoever was found.
             */
            function getColSize(col, colClass) {
                var sizes = getColSizes(col);
                for (var i = 0; i < sizes.length; i++) {
                    if (sizes[i].colClass == colClass) {
                        return sizes[i].size;
                    }
                }
                if (sizes.length) {
                    return sizes[0].size;
                }
                return null;
            }

            function getColSizes(col) {
                var result = [];
                colClasses.forEach(function(colClass) {
                    var re = new RegExp(colClass + '(\\d+)', 'i');
                    if (re.test(col.attr('class'))) {
                        result.push({
                            colClass: colClass,
                            size: parseInt(re.exec(col.attr('class'))[1])
                        });
                    }
                });
                return result;
            }

            function setColSize(col, colClass, size) {
                var re = new RegExp('(' + colClass + '(\\d+))', 'i');
                var reResult = re.exec(col.attr('class'));
                if (reResult && parseInt(reResult[2]) !== size) {
                    col.switchClass(reResult[1], colClass + size, 50);
                } else {
                    col.addClass(colClass + size);
                }
            }

            function makeSortable() {
                canvas.sortable({
                    items: '> section.ge-sect',
                    connectWith: '.ge-canvas',
                    handle: '.bs-container > .row > .ge-tools-drawer .ge-move',
                    start: sortStart,
                    helper: 'clone',
                });
                canvas.find('.row').sortable({
                    items: '> .column',
                    connectWith: '.ge-canvas .row',
                    handle: '> .ge-tools-drawer .ge-move',
                    start: sortStart,
                    helper: 'clone',
                });
                canvas.find('.column').sortable({
                    items: '> .row, > .ge-content',
                    connectsWith: '.column',
                    handle: '> .ge-tools-drawer .ge-move',
                    start: sortStart,
                    helper: 'clone',
                });


                function sortStart(e, ui) {
                    ui.placeholder.css({
                        height: ui.item.outerHeight()
                    });
                    ui.helper.hide();
                }
            }

            function removeSortable() {
                canvas.add(canvas.find('.column')).add(canvas.find('.row')).sortable('destroy');
            }

            function createRow() {

                return $('<div class="row ge-row" />');
            }

            function createColumn(size) {
                $classes = colClasses.map(function(c) {
                    return c += size
                }).join(' ');
                $classes = $classes.replace("col-xs-" + size, "col-xs-12");
                return $('<div/>')
                    .addClass($classes)
                    .append(createDefaultContentWrapper().html(
                        getRTE(settings.content_types[0]).initialContent));
            }

            /**
             * Run custom content filter on init and deinit
             */
            function runFilter(isInit) {
                if (settings.custom_filter.length) {
                    $.each(settings.custom_filter, function(key, func) {
                        if (typeof func == 'string') {
                            func = window[func];
                        }

                        func(canvas, isInit);
                    });
                }
            }

            /**
             * Wrap column content in <div class="ge-content"> where neccesary
             */
            function wrapContent() {
                canvas.find('.column').each(function() {
                    var col = $(this);
                    var contents = $();
                    col.children().each(function() {
                        var child = $(this);
                        if (child.is('.row, .ge-tools-drawer, .ge-content')) {
                            doWrap(contents);
                        } else {
                            contents = contents.add(child);
                        }
                    });
                    doWrap(contents);
                });
            }

            function doWrap(contents) {
                if (contents.length) {
                    var container = createDefaultContentWrapper().insertAfter(contents.last());
                    contents.appendTo(container);
                    contents = $();
                }
            }

            function createDefaultContentWrapper() {
                return $('<div/>')
                    .addClass('ge-content ge-content-type-' + settings.content_types[0])
                    .attr('data-ge-content-type', settings.content_types[0]);
            }

            function switchLayout(colClassIndex) {
                curColClassIndex = colClassIndex;

                var layoutClasses = ['ge-layout-desktop', 'ge-layout-tablet', 'ge-layout-phone'];
                layoutClasses.forEach(function(cssClass, i) {
                    canvas.toggleClass(cssClass, i == colClassIndex);
                });
            }

            function getRTE(type) {
                return "";
            }

            function clamp(input, min, max) {
                return Math.min(max, Math.max(min, input));
            }

            baseElem.data('grideditor', {
                init: init,
                deinit: deinit,
            });

        });

        return self;

    };

    $.fn.gridEditor.RTEs = {};

})(jQuery);
(function() {
    $.fn.gridEditor.RTEs.summernote = {
        init: function(settings, contentAreas) {

            if (!jQuery().summernote) {
                console.error('Summernote not available! Make sure you loaded the Summernote js file.');
            }

            var self = this;
            contentAreas.each(function() {
                var contentArea = $(this);
                if (!contentArea.hasClass('active')) {
                    if (contentArea.html() == self.initialContent) {
                        contentArea.html('');
                    }
                    contentArea.addClass('active');

                    var configuration = $.extend(
                        (settings.summernote && settings.summernote.config ? settings.summernote
                            .config : {}), {
                            tabsize: 2,
                            airMode: true,
                            oninit: function(editor) {
                                try {
                                    settings.summernote.config.oninit(editor);
                                } catch (e) {}
                                $('#' + editor.settings.id).focus();
                            }
                        }
                    );
                    var summernote = contentArea.summernote(configuration);
                }
            });
        },
        deinit: function(settings, contentAreas) {
            contentAreas.filter('.active').each(function() {
                var contentArea = $(this);
                var summernote = contentArea.summernote();
                if (summernote) {
                    summernote.summernote('destroy');
                }
                contentArea
                    .removeClass('active')
                    .attr('id', null)
                    .attr('style', null)
                    .attr('spellcheck', null);
            });
        },
        initialContent: '<p>Lorem ipsum dolores</p>',
    }
})();
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});
(function() {
    $.fn.gridEditor.RTEs.tinymce = {
        init: function(settings, contentAreas) {
            if (!window.tinymce) {
                console.error('tinyMCE not available! Make sure you loaded the tinyMCE js file.');
            }
            if (!contentAreas.tinymce) {
                console.error(
                    'tinyMCE jquery integration not available! Make sure you loaded the jquery integration plugin.'
                );
            }
            var self = this;
            contentAreas.each(function() {
                var contentArea = $(this);
                if (!contentArea.hasClass('active')) {
                    if (contentArea.html() == self.initialContent) {
                        contentArea.html('');
                    }
                    contentArea.addClass('active');
                    var configuration = $.extend(
                        (settings.tinymce && settings.tinymce.config ? settings.tinymce.config :
                        {}), {
                            inline: true,
                            oninit: function(editor) {
                                try {
                                    settings.tinymce.config.oninit(editor);
                                } catch (e) {}
                                $('#' + editor.settings.id).focus();
                            }
                        }
                    );
                    var tiny = contentArea.tinymce(configuration);
                }
            });
        },
        deinit: function(settings, contentAreas) {
            contentAreas.filter('.active').each(function() {
                var contentArea = $(this);
                var tiny = contentArea.tinymce();
                if (tiny) {
                    tiny.remove();
                }
                contentArea
                    .removeClass('active')
                    .attr('id', null)
                    .attr('style', null)
                    .attr('spellcheck', null);
            });
        },
        initialContent: '<p>Lorem ipsum dolores</p>',
    }
})();;
</script>