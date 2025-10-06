@extends('crudbooster::admin_template')
@section('title', 'Landing Page builder')
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
</style>
<div class="text-center">
    <div class="item-title" style="display: block; position: relative; z-index: 2; font-weight: bolder; margin-top: -45px;">
        {{ ($itemTitle) ? $itemTitle:"Unknown" }}
        <p><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>
                &nbsp; {{ cbLang('form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
        </p>
    </div>
</div>
<iframe 
  src="{{ $iframeURL }}" 
  title="Landing Page builder iframe"
  style="width:100%; min-height:1350px; border:0;">
</iframe>

@endsection

@push('bottom')
    <script>
        $(document).ready(function(){
            $('body').addClass('sidebar-collapse');
        });
    </script>
@endpush