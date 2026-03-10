<?php


namespace crocodicstudio\crudbooster\export;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DefaultExportXls implements FromView
{
    public function __construct(private readonly array $data)
    {
    }

    public function view(): View
    {
        return view("crudbooster::export",$this->data);
    }
}
