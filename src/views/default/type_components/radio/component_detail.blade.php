<?php
// Guards optional form keys (PHP 8 "Undefined array key") and shows the dataenum label instead of the raw value.

if (!empty($form['datatable']) && !empty($form['relationship_table'])) {
    $datatable_array = explode(",", (string) $form['datatable']);
    $datatable_tab = $datatable_array[0];
    $datatable_field = $datatable_array[1] ?? null;
    $foreignKey = CRUDBooster::getForeignKey($table, $form['relationship_table']);
    $foreignKey2 = CRUDBooster::getForeignKey($datatable_tab, $form['relationship_table']);

    $ids = DB::table($form['relationship_table'])->where($form['relationship_table'].'.'.$foreignKey, $id)->pluck($foreignKey2)->toArray();
    $value = DB::table($datatable_tab)->select($datatable_field)->whereIn('id', $ids)->pluck($datatable_field)->toArray();
} elseif (!empty($form['datatable'])) {

    $datatable = explode(',', (string) $form['datatable']);
    $table = $datatable[0];
    $field = $datatable[1] ?? null;
    $r = CRUDBooster::first($table, ['id' => $value])->$field ?? null;
    $value = $r ? [$r] : [];
} elseif (!empty($form['dataquery'])) {
    $dataquery = $form['dataquery'];
    $query = DB::select(DB::raw($dataquery));
    if ($query) {
        foreach ($query as $q) {
            if ($q->value == $value) {
                $value = [$q->label];
                break;
            }
        }
        if (! is_array($value)) {
            $value = [];
        }
    }
} elseif (!empty($form['dataenum'])) {
    $label = (string) $value;
    $dataenum = is_array($form['dataenum']) ? $form['dataenum'] : explode(';', (string) $form['dataenum']);
    foreach ($dataenum as $enum) {
        $parts = explode('|', (string) $enum, 2);
        if ((string) $parts[0] === (string) $value) {
            $label = $parts[1] ?? $parts[0];
            break;
        }
    }
    $value = $label === '' ? [] : [$label];
} else {
    $value = explode(";", (string) $value);
}

foreach ((array) $value as $v) {
    echo "<span class='badge'>".e($v)."</span> ";
}
?>
