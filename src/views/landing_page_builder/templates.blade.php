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

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
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
                    <label>Please Select Template</label>
                    <select id="list-landing" class="form-control" onchange="selectTemplate(this)">
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <a class="btn btn-primary" id="edit-go" style="color: rgb(255, 255, 255); display: block;"
                        type="button" onclick="selectTemplate()">go to Page</a>
                        <br>
                    
                    <a class="btn btn-secondary" id="go-back" style="color: rgb(255, 255, 255); display: block;" href="{{ CRUDBooster::adminPath('landing-pages') }}">go back</a>
                    <hr>
                </div>
            </div>
        </div>
        <script>
            function selectTemplate(e) {
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
                }, function(isConfirm) {
                    if (isConfirm) {
                        let templateId = $("#list-landing").val();
                        $.post("{{ CRUDBooster::adminPath('landing-pages/set-template') }}","_token={{csrf_token()}}&landingPageId={{$landingPage->id}}&templateId="+templateId, function(data) {
                            chooseTemplate(data); 
                        });
                    } else {
                        return false;
                    }
                });
            }
        function chooseTemplate(id) {
            window.location.href = "{{ CRUDBooster::mainpath('page-builder').'/'.$landingPage->id }}";
        }
        </script>
</body>
</html>
