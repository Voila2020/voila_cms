<?php

namespace crocodicstudio\crudbooster\export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LandingPageExport implements FromView
{
    public function __construct(private readonly array $data, private readonly array $columns)
    {
    }

    public function view(): View
    {
        return view("crudbooster::landing_page_builder.applications-export", [
            'applications' => $this->data,
            'columns' => $this->columns,
        ]);
    }
}
