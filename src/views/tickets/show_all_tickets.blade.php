@extends('crudbooster::admin_template')
@section('content')
    @php
        $docsUrl = getenv('SETTING_CLOUDSELL_DOCS');
    @endphp
    <div class="box">

        <div class="support-container">
            <!-- Tabs Header -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#my-tickets" role="tab" data-toggle="tab">{{ trans('crudbooster.my_tickets') }}</a>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content">
                <!-- My Tickets Tab -->
                <div role="tabpanel" class="tab-pane active" id="my-tickets">
                    <!-- Instructional Text -->
                    <div class="notes">
                        <p>{{ trans('crudbooster.contact_support_instruction') }}</p>
                        <p><strong>{{ trans('crudbooster.important_note') }}</strong></p>
                        <ul>
                            <li>{{ trans('crudbooster.ticket_table_note') }}</li>
                            <li><strong>{{ trans('crudbooster.single_ticket_reply_instruction') }}</strong></li>
                        </ul>
                        <p>{{ trans('crudbooster.continue_existing_ticket') }}</p>
                    </div>

                    <div class="tableFixHead">
                        <div class="table-responsive">
                            <table id="table_dashboard"
                                class="table table-hover table-striped table-bordered report-table table_dashboard">

                                <thead>
                                    <tr class="active">
                                        <th>{{ trans('crudbooster.ticket_code') }}</th>
                                        <th>{{ trans('crudbooster.email_address') }}</th>
                                        <th>{{ trans('crudbooster.description') }}</th>
                                        <th>{{ trans('crudbooster.status') }}</th>
                                        <th>{{ trans('crudbooster.created_at') }}</th>
                                        <th>{{ trans('crudbooster.details') }}</th>
                                    </tr>
                                </thead>

                                <tbody class="ui-sortable">
                                    @if(count($data['tickets']) > 0)
                                    @foreach ($data['tickets'] as $ticket)
                                        <tr>
                                            <td>{{ $ticket['code'] }}</td>
                                            <td>{{ $ticket['user_email'] }}</td>
                                            <td>{!! $ticket['description'] !!}</td>
                                            <td>
                                                @php
                                                    $bg_color = '';
                                                    if ($ticket['status_id'] == 1) {
                                                        $bg_color = 'bg-blue';
                                                        $ticket['status_name'] = trans('crudbooster.status_new');
                                                    } elseif ($ticket['status_id'] == 2) {
                                                        $bg_color = 'bg-red';
                                                        $ticket['status_name'] = trans('crudbooster.status_in_progress');
                                                    } elseif ($ticket['status_id'] == 3) {
                                                        $bg_color = 'bg-green';
                                                        $ticket['status_name'] = trans('crudbooster.status_closed');
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $bg_color }}">{{ $ticket['status_name'] }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('Y-m-d H:i') }}
                                            </td>
                                            <td>
                                                <a href="show_tickets/detail/{{ $ticket['code'] }}">
                                                    <button class="btn btn-sm btn-info"
                                                        title="{{ trans('crudbooster.details') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                   @else
                                   <tr><td colspan="6">{{ trans('crudbooster.no_tickets_yet') }}</td></tr>
                                   @endif                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
        <!-- <div class="box-footer" style="background: #F5F5F5">
            <div class="form-group">
                <div class="col-sm-12">
                    <a class="btn btn-default" href="{{ CRUDBooster::adminPath('') }}" >{{ trans('crudbooster.button_back') }}</a>
                </div>
            </div>
        </div> -->
    </div>

    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/select2/select2.min.js') }}"></script>

    @push('bottom')
        <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/select2/i18n/ar.js') }}"></script>
        <script>
            function searchForDocs(event) {
                event.preventDefault();
                const cloudSellDocsUrl = '{{ getenv('SETTING_CLOUDSELL_DOCS') }}';
                const searchInput = document.getElementById('searchInput').value.trim();

                const regex = /^[\u0600-\u06FF0-9a-zA-Z\s]+$/;
                if (!searchInput || !regex.test(searchInput)) {
                    alert('{{ trans('crudbooster.enter_valid_search_term') }}');
                    return;
                }
                window.open(`${cloudSellDocsUrl}/search?word=${encodeURIComponent(searchInput)}`, '_blank');
            }
        </script>
    @endpush
@endsection
