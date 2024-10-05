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
                <div style="display:flex;justify-content:end;">
                    <button class="btn btn-danger delete-application" data-application-id="{{ $item->id }}"
                        data-form-id="{{ $item->form_id }}">Delete</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@push('bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            // Delete Form Application
            $('.delete-application').click(function() {
                let applicationId = $(this).data('application-id');
                let formId = $(this).data('form-id');
                let currUrl = @json(CRUDBooster::mainpath()) + '/delete-application/' + applicationId;

                $.ajax({
                    url: currUrl,
                    type: 'GET',
                    data: {
                        form_id: formId
                    },
                    success: function(response) {
                        $(this).closest('.well')
                            .addClass('hide');
                    }.bind(this),
                    error: function(xhr) {
                        alert('Error deleting application: ' + xhr.responseText);
                    }
                });

            });
        });
    </script>
@endpush
@endsection
