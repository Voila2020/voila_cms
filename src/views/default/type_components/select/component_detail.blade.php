<?php
if (!empty($form['datatable'])) {
    $datatable = explode(',', (string) $form['datatable']);
    $table = $datatable[0] ?? null;
    $field = $datatable[1] ?? null;

    if ($table && $field) {
        $record = CRUDBooster::first($table, ['id' => $value]);
        echo $record->{$field} ?? '';
    }
}
if (!empty($form['dataquery'])) {
    $dataquery = $form['dataquery'];
    $query = DB::select(DB::raw($dataquery));
    if ($query) {
        foreach ($query as $q) {
            if ($q->value == $value) {
                echo $q->label;
                break;
            }
        }
    }
}
if (!empty($form['dataenum'])) {
    echo $value;
}
?>