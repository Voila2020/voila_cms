<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing Page Builder</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('landing_page\css\plugins\sweetalert\sweetalert.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('landing_page\js\plugins\sweetalert\sweetalert.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }
        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }
        .links > a {
            margin-top: 10px;
        }
        #template-preview {
            margin-top: 20px;
            display: none;
        }
        #template-preview img {
            width: auto;
            height: 200px;
            object-fit: cover; /* keeps aspect ratio but crops if needed */
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">

        <div class="content">
            <div class="title m-b-md">
                Voila Page Builder
            </div>
            <br>
            <div class="links">
                <!-- <label>Please Select Template</label> -->
                <select id="list-landing" class="form-control">
                    <option value="" selected disabled>Please Select Template</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" data-image="{{ $template->preview_image ? asset($template->preview_image) : "" }}">
                            {{ $template->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Preview container -->
                <div id="template-preview">
                    <p><strong>Template Preview:</strong></p>
                    <img id="preview-img" src="" alt="Template Preview">
                </div>

                <br>
                <a class="btn btn-primary" id="edit-go" style="color: #fff; display:block;" type="button">
                    Go to Page
                </a>
                <a class="btn btn-secondary" id="go-back" style="color: #fff; display:block;" 
                   href="{{ CRUDBooster::adminPath('landing-pages') }}">
                   Go back
                </a>
                <hr>
            </div>
        </div>
    </div>

    <script>
        // show preview when selecting template
        $("#list-landing").on("change", function () {
            let img = $(this).find(":selected").data("image");
            if (img) {
                $("#preview-img").attr("src", img);
                $("#template-preview").show();
            } else {
                $("#template-preview").hide();
            }
        });

        // click preview image -> open full size
        $("#preview-img").on("click", function () {
            let src = $(this).attr("src");
            if (src) {
                window.open(src, "_blank"); // opens original image in new tab
            }
        });

        // go to page with confirmation
        $("#edit-go").on("click", function () {
            let templateId = $("#list-landing").val();
            if (!templateId) {
                swal("Please select a template first!");
                return;
            }

            swal({
                title: "Template change",
                text: "Changing template will delete the old one, sure to continue?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ec1e24",
                confirmButtonText: "Continue",
                cancelButtonText: "Back",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post("{{ CRUDBooster::adminPath('landing-pages/set-template') }}",
                        "_token={{ csrf_token() }}&landingPageId={{ $landingPage->id }}&templateId=" + templateId,
                        function (data) {
                            window.location.href = "{{ CRUDBooster::mainpath('page-builder').'/'.$landingPage->id }}";
                        }
                    );
                }
            });
        });
    </script>
</body>
</html>
