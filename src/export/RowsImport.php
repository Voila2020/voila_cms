<?php

namespace crocodicstudio\crudbooster\export;

use Maatwebsite\Excel\Concerns\ToArray;

/**
 * Reads an uploaded sheet into plain arrays with Excel::toArray().
 * Laravel-Excel 4 requires an import object here; 3.1 accepted an empty array.
 */
class RowsImport implements ToArray
{
    public function array(array $array): void
    {
    }
}
