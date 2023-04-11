@extends('crudbooster::admin_template')

@section('content')
    <div class="box">
        <div class="box-header">
            <div class="pull-{{ cbLang('left') }}">
                <div class="selected-action" style="display:inline-block;position:relative;">
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)" data-name='delete' title='{{ cbLang('action_delete_selected') }}'><i
                                    class="fa fa-trash"></i>
                                {{ cbLang('action_delete_selected') }}</a></li>
                    </ul>
                    <!--end-dropdown-menu-->
                </div>
                <!--end-selected-action-->
            </div>
            <!--end-pull-left-->
        </div>
        {{-- start table code --}}
        <div class="box-body table-responsive no-padding">
            @php
                $directory = storage_path('app\backups');
                if (File::exists($directory)) {
                    $files = File::files($directory);
                    $files = array_reverse($files);
                }
            @endphp
            <form id='form-table' method='post' action=''>
                <input type='hidden' name='button_name' value='' />
                <input type='hidden' name='_token' value='{{ csrf_token() }}' />
                <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                    <thead class="table-head">
                        <tr class="active">
                            <th width="20%">
                                <span class="tbl-head">
                                    {{ cbLang('backup') }}
                                </span>
                            </th>
                            <th width="20%">
                                <span class="tbl-head">
                                    {{ cbLang('backup_file_size') }}
                                </span>
                            </th>
                            <th width="auto">
                                <span class="tbl-head">
                                    {{ cbLang('action') }}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($files)
                            @foreach ($files as $key => $file)
                                @php
                                    $filesize = $file->getSize();
                                    $humanSize = formatBytes($filesize);
                                @endphp
                                <tr>
                                    <td id="backup_name">{{ $file->getFilename() }}</td>
                                    <td id="backup_fileSize">{{ $humanSize }}</td>
                                    <td id="backup_action">
                                        <div class="button_action">
                                            <a class="btn btn-xs btn-success" title="Restore Database"
                                                onClick='restoreDB("{{ $file->getFilename() }}")'>
                                                {{ cbLang('restore_db') }}
                                            </a>
                                            <a class="btn btn-xs btn-danger" title="Delete Database"
                                                onClick='deleteDB("{{ $file->getFilename() }}")'>
                                                {{ cbLang('delete_db') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                    </tfoot>
                </table>

            </form>
            @php
            @endphp
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <span class="pull-right">
                    @if ($files)
                        Total rows: 1 to {{ count($files) }}
                    @endif
                </span>
            </div>
            <!--END FORM TABLE-->


        </div>

        {{-- @if (!is_null($post_index_html) && !empty($post_index_html))
        {!! $post_index_html !!}
        @endif --}}
    </div>
@endsection

@push('bottom')
    <script type="text/javascript">
        $('.content-header h1').append(`
            <a  id="btn_backup" class="btn btn-sm btn-primary" onclick="makeBackup()" title="Make Backup">
                <i class="fa fa-database"></i>
                {{ cbLang('make_backup') }}
            </a>
        `);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function makeBackup() {
            let createBackupUrl = @json(CRUDBooster::mainPath('make-backup'));
            $.ajax({
                type: "GET",
                url: createBackupUrl,
                beforeSend: function() {
                    $('.spinner-loader').css('display', 'block');
                    $('.main-overlay').css('display', 'block');
                },
                success: function(data) {
                    $('.spinner-loader').css('display', 'none');
                    $('.main-overlay').css('display', 'none');
                    location.reload();
                },
                error: function(data) {},
            }).done(function(msg) {});
        }

        function restoreDB(fileName) {
            let restoreUrl = @json(CRUDBooster::mainPath('restore-backup/')) + fileName;
            console.log("restore url => ", restoreUrl);
            $.ajax({
                type: "GET",
                url: restoreUrl,
                beforeSend: function() {
                    $('.spinner-loader').css('display', 'block');
                    $('.main-overlay').css('display', 'block');
                },
                success: function(data) {
                    $('.spinner-loader').css('display', 'none');
                    $('.main-overlay').css('display', 'none');
                    location.reload();
                },
                error: function(data) {},
            }).done(function(msg) {

            });
        }

        function deleteDB(fileName) {
            let deleteUrl = @json(CRUDBooster::mainPath('delete-backup/')) + fileName;
            $.ajax({
                type: 'GET',
                url: deleteUrl,
                beforeSend: function() {
                    $('.spinner-loader').css('display', 'block');
                    $('.main-overlay').css('display', 'block');
                },
                success: function(data) {
                    $('tbody tr').each(function(index, element) {
                        var tr = $(element);
                        var name = tr.find('#backup_name').text();
                        if (name == fileName) {
                            tr.remove();
                        }
                    });
                },
                error: function(data) {}
            }).done(function(msg) {
                $('.spinner-loader').css('display', 'none');
                $('.main-overlay').css('display', 'none');
            });
        }
    </script>
@endpush
