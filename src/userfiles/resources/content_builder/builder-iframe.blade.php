@extends('crudbooster::admin_template')
@section('title', 'Content Builder')
@section('content')
<style>
    .content-header > .breadcrumb{
        z-index: 9999;
    }
    #content_section {
        padding-left: 0px !important;
        padding-right: 0px !important;
        padding-bottom: 0px !important;
    }
    .content-builder-action-sect{
        position: relative;
        z-index: 1;
        margin-right:15px;
        margin-top:-20px;
        margin-bottom: 5px;
    }
    .content-builder-action-sect a:hover{
        color:#dd4b39 !important;
    }
    .content-wrapper{
        min-height: 90vh !important;
    }
</style>
<div class="text-center">
    <div class="item-title" style="display: block; position: relative; z-index: 2; font-weight: bolder; margin-top: -45px;">
        {{ ($itemTitle) ? $itemTitle:"Unknown" }}
        <p><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>
                &nbsp; {{ cbLang('form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
        </p>
    </div>
</div>
<div class="content-builder-action-sect text-right">
    <div class="dropdown" style="display:inline-block;">
        <button  class="btn btn-xs btn-primary" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
               Copy Content From <i class="fa fa-copy"></i>
        </button>
        <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenuButton">
            @foreach ($website_languages as $lang)
                @if($lang->code != $content_lang)
                <li><a href="javascript:void(0)" class="getContentFrom" data-item-id="{{ $itemId }}" data-field="{{ $fieldName }}" data-current-lang="{{ $content_lang }}" data-from-lang="{{ $lang->code }}"><i class="fa fa-language"></i> {{$lang->name }} Content </a></li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<iframe 
  src="{{ $iframeURL }}" 
  title="Content builder iframe"
  style="width:100%; min-height:90vh; height:auto; border:0;"
  scrolling="yes">
</iframe>

@endsection

@push('bottom')
    <script>
        $(document).ready(function(){
            $('body').addClass('sidebar-collapse');


            $(".getContentFrom").click(function () {
                if (confirm("This will overwrite the existing content and you cannot return to the old version. Do you want to continue?")) {
                    let item_id = $(this).data('item-id');
                    let field = $(this).data('field');
                    let current_lang = $(this).data('current-lang');
                    let from_lang = $(this).data('from-lang');

                    // send ajax request
                    $.ajax({
                        url: '{{ CRUDBooster::mainpath('content-from') }}', // <-- replace with your route URL
                        type: 'GET',
                        data: {
                            item_id: item_id,
                            field: field,
                            current_lang: current_lang,
                            from_lang: from_lang
                        },
                        success: function (response) {
                            // handle response here
                            console.log("Response:", response);
                            if(response.status){
                                location.reload();
                            }else{
                                alert(response.message);
                            }
                          

                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", error);
                            alert("Failed to fetch content.");
                        }
                    });
                }
            });

        });
    </script>
@endpush