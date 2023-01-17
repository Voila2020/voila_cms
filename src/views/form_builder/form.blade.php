@extends('crudbooster::admin_template')
@section('title', 'Form') @section('content')

<div class="row">
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{!! \Session::get('success') !!}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! $data !!}
    </div>
</div>
@endsection
