<?php
namespace crocodicstudio\crudbooster\export;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Contracts\View\View;

class DefaultExportCsv implements FromView, WithCustomCsvSettings
{
    public function __construct(private readonly array $data)
    {
    }

    public function view(): View
    {
        return view('crudbooster::export', $this->data);
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => "\r\n",
        ];
    }
}

