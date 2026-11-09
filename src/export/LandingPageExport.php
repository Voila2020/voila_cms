<?php

namespace crocodicstudio\crudbooster\export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LandingPageExport implements FromView
{
    private readonly array $data;

    private readonly array $columns;

    /**
     * Callers pass query results (Collections) or arrays. Only arrays were accepted before,
     * so the landing page export failed with a TypeError.
     */
    public function __construct(iterable $data, iterable $columns)
    {
        $this->data = collect($data)->all();
        $this->columns = collect($columns)->all();
    }

    public function view(): View
    {
        return view("crudbooster::landing_page_builder.applications-export", [
            'applications' => $this->data,
            'columns' => $this->columns,
        ]);
    }
}
