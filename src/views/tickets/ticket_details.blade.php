@extends('crudbooster::admin_template')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="row">
        <div class='col-sm-12'>
            <div class='ticket-details'>
                <div class="callout callout-info">
                    <h4><i class='fa fa-ticket'></i> {{ $ticket['client_name'] }} </h4>
                    {!! $ticket['description'] !!}
                </div>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-info"></i>
                        <h3 class="box-title">{{ trans('crudbooster.ticket_details') }}</h3>
                    </div>
                    <div class="box-body">
                        <table class='table table-striped .table-hover'>
                            <tr>
                                <th width="20%">{{ trans('crudbooster.ticket') }}</th>
                                <td>#{{ $ticket['code'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">{{ trans('crudbooster.open_date') }}</th>
                                <td>
                                    {{ $ticket['created_at'] ? Carbon::parse($ticket['created_at'])->format('M d, Y H:i A') : '-' }}
                                </td>

                            </tr>
                            <tr>
                                <th>{{ trans('crudbooster.client_name') }}</th>
                                <td>{{ $ticket['client_name'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('crudbooster.client_email') }}</th>
                                <td>{{ $ticket['client_email'] }}</td>
                            </tr>
                            @if($ticket['client_phone'])
                            <tr>
                                <th>{{ trans('crudbooster.phone_number') }}</th>
                                <td>{{ $ticket['client_phone'] }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>{{ trans('crudbooster.category') }}</th>
                                <td>{{ $ticket['category_name'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('crudbooster.status') }}</th>
                                <td>
                                    @php
                                        $bg_color = '';
                                        $closed_at_date_format = '';
                                        if ($ticket['status_name'] == 'New') {
                                            $bg_color = 'bg-blue';
                                        } elseif ($ticket['status_name'] == 'In Progress') {
                                            $bg_color = 'bg-red';
                                        } elseif ($ticket['status_name'] == 'Closed') {
                                            $bg_color = 'bg-green';
                                        }

                                    @endphp
                                    <span class="badge {{ $bg_color }}">{{ $ticket['status_name'] }}</span>

                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('crudbooster.attachment') }}</th>
                                <td>
                                    @if ($ticket['attachment'])
                                        <a class=""
                                            href="{{ config('crudbooster.TICKET_SYSTEM_LINK') . '/' . $ticket['attachment'] }}"
                                            target="_blank">
                                            {{ trans('crudbooster.view') }} <i class='fa fa-image'></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

                <ul class="timeline">

                    <li class="time-label">
                        <span class="bg-red">
                            {{ trans('crudbooster.comments') }}
                        </span>
                    </li>

                    @if ($ticket['comments'] && count($ticket['comments']) > 0)
                        @foreach ($ticket['comments'] as $comment)
                            <li>
                                <i class="fa fa-comments bg-blue"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header"> <i class='fa fa-user'></i> <a
                                            href="javascript:void(0)">{{ $comment['author_by'] }}</a> <span
                                            class='pull-right'><small><i class='fa fa-calendar'></i>
                                                {{ Carbon::parse($comment['created_at'])->format('M d, Y H:i A') }}</small></span>

                                    </h3>
                                    <div class="timeline-body">
                                        {!! $comment['comment'] !!}
                                    </div>
                                    <div class="timeline-footer">
                                        @if ($comment['attachments'])
                                            <a class="btn btn-primary btn-xs"
                                                href="{{ config('crudbooster.TICKET_SYSTEM_LINK') . '/' . $comment['attachments'] }}"
                                                target="_blank">
                                                {{ trans('crudbooster.view') }} <i class='fa fa-image'></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <i class="fa fa-comments bg-red"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">{{ trans('crudbooster.no_comments_yet') }}</h3>
                            </div>
                        </li>
                    @endif
                </ul>
                <div class='action-sect'>
                    @php
                        $return_url = Request::fullUrl();
                    @endphp
                    <a class="btn btn-default" href="{{ CRUDBooster::adminPath('show_tickets') }}" >{{ trans('crudbooster.button_back') }}</a>
                    <a class="btn btn-primary add-comment-btn {{ $ticket['status_name'] == 'Closed' ? 'disabled' : '' }}"
                        href="{{ $ticket['status_name'] == 'Closed' ? '#' : url('/admin/comments/create') }}">
                        {{ trans('crudbooster.add_comment') }} <i class="fa fa-comments"></i>
                    </a>
                    <div class="close-ticket-btn">
                        <button class="btn btn-info" {{ $ticket['status_name'] == 'Closed' ? 'disabled' : '' }}>
                            {{ trans('crudbooster.close_ticket') }} 
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END ROW -->

    @push('bottom')
        <script>
            $(".close-ticket-btn button").on("click", function() {
                if(confirm('Are You sure?')){
                    $('.close-ticket-btn button').append('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                    $('.close-ticket-btn button').attr('disabled', true);
                    //-----------------------------------------------
                    $.ajax({
                        url: "{{ url('admin/close-ticket') }}",
                        type: 'POST',
                        data: {
                            code: "{{ $ticket['code'] }}"
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }
                
            });
        </script>
    @endpush
@endsection
