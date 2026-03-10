<?php
$table = $form['datamodal_table'];
$field = explode(',', (string) $form['datamodal_columns'])[0];
echo CRUDBooster::first($table, ['id' => $value])->$field;
?>