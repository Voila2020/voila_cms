@extends('crudbooster::admin_template')
@section('title', 'Translation ') @section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
</script>
<script>
    var $x = jQuery.noConflict();
</script>

<div class="well" style="background: #FFF">
    <h1>{{ cbLang('Translation') }}</h1>
    <br>

    <form method="POST" action="{{ route('translations.create') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-4">
                <fieldset style="margin-bottom:20px;">
                    <label>{{ cbLang('enter_lang_key') }}</label>
                    <input type="text" name="key" class="form-control" placeholder="{{ cbLang('enter_key') }}"
                        required>
                </fieldset>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-7">
                <label style="margin-left:10px; margin-right:10px;">{{ cbLang('enter_lang_values') }}</label>
                <fieldset style="display:flex;">
                    @foreach ($languages as $language)
                        <input class="form-control" type="text" name="value_{{ $language->code }}"
                            placeholder="{{ $language->name }}" style="margin-left:10px; margin-right:10px;"><br>
                    @endforeach
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">

                <button type="submit" class="btn btn-success">{{ cbLang('Add') }}</button>
            </div>
        </div>
    </form>
    <hr>
    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()"
        placeholder="{{ cbLang('search_for_key_values') }}" title="Type in a name">
    <hr>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped table-bordered" id="mytable">
            <thead>
                <tr>
                    <th class="translate_tbl_header" style="width:auto;">{{ cbLang('Key') }}</th>
                    @if ($languages->count() > 0)
                        @foreach ($languages as $language)
                            <th class="translate_tbl_header" style="width:auto">
                                {{ cbLang($language->name) }}({{ cbLang($language->code) }})</th>
                        @endforeach
                    @endif
                    <th class="translate_tbl_header" style="width:10%">{{ cbLang('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- translate-key class name --}}
                @if ($columnsCount > 0)
                    @foreach ($columns[0] as $columnKey => $columnValue)
                        <tr>
                            <td><a style="color:#000;" data-title="Enter Key" data-type="text"
                                    data-pk="{{ $columnKey }}"
                                    data-url="{{ route('translation.update.json.key') }}">{{ $columnKey }}</a></td>
                            @for ($i = 1; $i <= $columnsCount; ++$i)
                                <td><a href="#" data-title="Enter Translate" class="translate"
                                        data-code="{{ $columns[$i]['lang'] }}" data-type="textarea"
                                        data-pk="{{ $columnKey }}"
                                        data-url="{{ route('translation.update.json') }}">{{ isset($columns[$i]['data'][$columnKey]) ? $columns[$i]['data'][$columnKey] : '' }}</a>
                                </td>
                            @endfor
                            <td><button data-action="{{ route('translations.destroy', $columnKey) }}"
                                    class="btn btn-danger btn-xs remove-key">{{ cbLang('Delete') }}</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("mytable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            td1 = tr[i].getElementsByTagName("td")[1];
            td2 = tr[i].getElementsByTagName("td")[2];
            if (td || td1 || td2) {
                txtValue = td.textContent || td.innerText;
                txtValue1 = td1.textContent || td1.innerText;
                txtValue2 = td2.textContent || td2.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue1.toUpperCase().indexOf(filter) > -1 ||
                    txtValue2.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    $x.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $x('meta[name="csrf-token"]').attr('content')
        }
    });

    $x('.translate').editable({
        params: function(params) {
            params.code = $x(this).editable().data('code');
            return params;
        }
    });

    $x('.translate-key').editable({
        validate: function(value) {
            if ($x.trim(value) == '') {
                return 'Key is required';
            }
        }
    });

    $x('body').on('click', '.remove-key', function() {
        var cObj = $x(this);
        if (confirm("Are you sure want to remove this item?")) {
            $x.ajax({
                url: cObj.data('action'),
                method: 'DELETE',
                success: function(data) {
                    cObj.parents("tr").remove();
                    alert("Your imaginary file has been deleted.");
                }
            });
        }
    });
</script>
@endsection
