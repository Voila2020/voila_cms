<?php

namespace crocodicstudio\crudbooster\export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LandingPageExport implements FromView
{
    private $data;
    private $columns;
    public function __construct($data, $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function view(): View
    {
        return view("crudbooster::landing_page_builder.applications-export", [
            'applications' => $this->data,
            'columns' => $this->columns,
        ]);
    }
}
