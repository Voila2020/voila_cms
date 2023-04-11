@extends('crudbooster::admin_template')
@section('title', 'submits') @section('content')

<div class="row">
    <div class="container">
            @foreach($data as $item)
               <div class="well">
                    <strong>Date: </strong> {{$item->updated_at}}<strong style="margin-left: 20px">IP: </strong> {{$item->ip}}
                    {!! $item->response !!}

               </div>
            @endforeach
    </div>
</div>
@endsection
