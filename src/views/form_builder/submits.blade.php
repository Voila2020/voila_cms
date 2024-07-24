@extends('crudbooster::admin_template')
@section('title', 'submits') @section('content')

<div class="row">
    <div class="container">
        @if ($export_data_columns)
            <form method='post' target="_blank" action='{{ CRUDBooster::mainpath('export-data?t=' . time()) }}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type='hidden' name='filename' class='form-control' required
                    value='Report {{ $module_name }} - {{ date('d M Y') }}' />

                <input type="hidden" name="export_data_columns" value="{{ json_encode($export_data_columns) }}">

                <input type="hidden" name="export_data_result" value="{{ json_encode($export_data_result) }}">
                <input type="hidden" name="fileformat" value="xls">
                <div style="margin-bottom: 5px">
                    <button class="btn btn-primary btn-submit" type="submit">Export</button>
                </div>
            </form>
        @endif
        @foreach ($data as $item)
            <div class="well">
                <strong>Date: </strong> {{ $item->updated_at }}<strong style="margin-left: 20px">IP: </strong>
                {{ $item->ip }}
                {!! $item->response !!}
            </div>
        @endforeach
    </div>
</div>
@endsection