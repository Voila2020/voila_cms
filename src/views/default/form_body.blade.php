<?php
if (!isset($lang)) {
    $lang = null;
}

if (($lang === null || is_array($lang)) && isset($websiteLanguages) && method_exists($websiteLanguages, 'where')) {
    $lang = $websiteLanguages->where('default', 1)->first();
}

if (is_array($lang)) {
    $lang = (object) $lang;
}

if ($lang === null || is_string($lang)) {
    $lang = (object) ['default' => 1, 'code' => 'en'];
}

if (!isset($lang->default)) {
    $lang->default = null;
}

$parent_field = $parent_field ?? null;
$parent_id = $parent_id ?? null;
//Loading Assets
$asset_already = [];
foreach($forms as $form) {
$type = $form['type'] ?? 'text';
$name = $form['name'] ?? null;

if (!$name) {
    continue;
}

if (in_array($type, $asset_already)) {
    continue;
}
?>
@if(file_exists(base_path('/vendor/voila_cms/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php')))
    @include('crudbooster::default.type_components.'.$type.'.asset')
@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php')))
    @include('vendor.crudbooster.type_components.'.$type.'.asset')
@endif
<?php
$asset_already[] = $type;
}


//Loading input components
$header_group_class = "";
foreach($forms as $index=>$form) {

$name = $form['name'] ?? null;
if (!$name) {
    continue;
}
$join = $form['join'] ?? null;
$value = $form['value'] ?? '';
@$value = $row->{$name} ?? $value;

$old = old($name);
$value = (empty($old)) ? $value : $old;

$validation = [];
$validation_raw = isset($form['validation']) ? explode('|', $form['validation']) : [];
foreach ($validation_raw as $vr) {
    $vr_a = explode(':', $vr);
    $key = $vr_a[0] ?? '';
    $param = $vr_a[1] ?? null;

    if ($param !== null && $param !== '' && $param !== '0') {
        $validation[$key] = $param;
    } else {
        $validation[$vr] = TRUE;
    }
}

if (isset($form['callback_php'])) {
    try {
        $value = eval('return ' . $form['callback_php'] . ';');
    } catch (\Throwable $e) {
        \Log::warning('Callback PHP evaluation error: ' . $e->getMessage());
    }
}


if (isset($form['callback'])) {
    $value = call_user_func($form['callback'], $row);
}

if ($join && @$row) {
    $join_arr = explode(',', (string) $join);
    array_walk($join_arr, trim(...));
    $join_table = $join_arr[0] ?? null;
    $join_title = $join_arr[1] ?? null;
    if (!$join_table || !$join_title) {
        $join_table = null;
    }
    if ($join_table && $join_title) {
        $join_query_[$join_table] = DB::table($join_table)->select($join_title)->where("id", $row->{'id_'.$join_table})->first();
        $value = @$join_query_[$join_table]->{$join_title};
    }
}
$form['type'] = $form['type'] ?? 'text';
$type = $form['type'];
$required = (!empty($form['required'])) ? "required" : "";
$required = (strpos((string) ($form['validation'] ?? ''), 'required') !== FALSE) ? "required" : $required;

if(CRUDBooster::getCurrentModule()->translation_table != '' && $lang->default == null){
    $required = '';
}

$readonly = (!empty($form['readonly'])) ? "readonly" : "";
$disabled = (!empty($form['disabled'])) ? "disabled" : "";
$placeholder = (!empty($form['placeholder'])) ? "placeholder='".$form['placeholder']."'" : "";
$col_width = $form['width'] ?? "col-sm-9";

if ($parent_field == $name) {
    $type = 'hidden';
    $value = $parent_id;
}

if ($type == 'header') {
    $header_group_class = "header-group-$index";
} else {
    $header_group_class = ($header_group_class) ?: "header-group-$index";
}

?>
@if(file_exists(base_path('/vendor/voila_cms/crudbooster/src/views/default/type_components/'.$type.'/component.blade.php')))
    @include('crudbooster::default.type_components.'.$type.'.component', ['current_language' => $lang])
@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/component.blade.php')))
    @include('vendor.crudbooster.type_components.'.$type.'.component',['current_language' => $lang])
@else
    <p class='text-danger'>{{$type}} is not found in type component system</p><br/>
@endif
<?php
}
