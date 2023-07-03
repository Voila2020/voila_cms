@extends('website.layouts.site')
@section('main_content')
<div class="cs-hero cs-style11 cs-type1 cs-center cs-shape_animaiton">
    <div class="container">
        <div class="cs-hero_text">
            <h1 class="cs-hero_title cs-bold cs-white">Thank You</h1>
            <ol class="cs-breadcrumb cs-style2 cs-type1 cs-white_80">
                <li class="cs-breadcrumb_item"><a href="{{ url('/') }}" class="cs-white">Home</a></li>
                <li class="cs-breadcrumb_item">Thank You</li>
            </ol>
        </div>
    </div>
    <div class="cs-hero_img cs-bg" data-src="{{ asset('web-assets/img/services.png') }}">
        <div class="cs-hero_img_circle"></div>
    </div>
</div>
<div class="cs-height_140 cs-height_lg_80"></div>

<div class="container">
    <div class="cs-section_heading cs-style3 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
        <span class="cs-bold">{!! $response !!}</span>
    </div>
    <div class="cs-height_80 cs-height_lg_60"></div>
</div>

@endsection
