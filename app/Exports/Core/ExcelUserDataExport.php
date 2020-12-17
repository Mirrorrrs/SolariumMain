<?php

namespace App\Exports\Core;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;

class ExcelUserDataExport implements FromArray
{
    protected $invoices;
    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices->toArray();
    }

    public function array(): array
    {
        return $this->invoices;
    }
}
