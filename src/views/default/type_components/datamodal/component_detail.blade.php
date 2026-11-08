<?php
$table = $form['datamodal_table'];
$field = explode(',', (string) $form['datamodal_columns'])[0];
// Empty when nothing is picked or the picked record was deleted
echo CRUDBooster::first($table, ['id' => $value])?->$field ?? '';
?>
