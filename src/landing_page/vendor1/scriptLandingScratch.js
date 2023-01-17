$(document).ready(function(){
	var canvas=$("body");	
	applySettingRowInViewMode(canvas, true);
    applySettingColInViewMode(canvas, true);
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
								$style += "background-color:" + $bg_value + ";background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($bg_mode === "image" && $bg_value !== "") {
                                $style += "background-image:url(" + $bg_value + ");";
                                //$style += "background-size:cover;-webkit-background-origin:content-box;background-origin:content-box;background-position:center center;background-repeat:no-repeat;";
								//$style += "background-size:cover;-webkit-background-origin:content-box;background-origin:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style += "background-size:cover;background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($row_mode == "full-row") {
                                $(this).parent(".bs-container").parent(".ge-sect").attr("style", $style);
                            }
                            if ($row_mode == "container") {
                                $(this).parent(".bs-container").attr("style", $style);
                            }

                        });
                    } else {
                        $("body").contents().find(".row.ge-row").each(function() {
                            $(this).parent(".bs-container").removeAttr("style");
                            $(this).parent(".bs-container").parent(".ge-sect").removeAttr("style");
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
							$classes=$(this).attr("data-class");
							if($classes !==undefined && $classes !=null){
								$(this).addClass($classes);
							}
                            $content_mode = $(this).attr("data-content-mode");
                            $style = $(this).attr("data-style");
                            if ($style === undefined) {
                                $style = "";
                            }
							if ($bg_mode === "color" && $bg_value !== "") {
                                //$style += "background-color:" + $bg_value + ";-webkit-background-clip:content-box;background-clip:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style += "background-color:" + $bg_value + ";background-position:center center;background-repeat:no-repeat;";
                            }
                            if ($bg_mode === "image" && $bg_value !== "") {
                                $style += "background-image:url(" + $bg_value + ");";
                                //$style += "background-size:cover;-webkit-background-origin:content-box;background-origin:content-box;background-position:center center;background-repeat:no-repeat;";
                                $style += "background-size:cover;background-position:center center;background-repeat:no-repeat;";
                            }

                            $(this).attr("style", $style);
                            if ($style !== undefined) {
                                $styleDataArray = $style.split(";");
                                for (var i = 0; i < $styleDataArray.length; i++) {
                                    $itemStyle = $styleDataArray[i].split(":");
                                    if ($itemStyle[0] !== undefined && $itemStyle[1] !== undefined) {

                                        if ($itemStyle[0] === "padding-left" || $itemStyle[0] === "padding-right") {
                                            $(this).css($itemStyle[0], (parseInt($itemStyle[1].replace("px", "")) + 15) + "px");
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
})