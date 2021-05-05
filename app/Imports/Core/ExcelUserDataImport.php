<?php

namespace App\Imports\Core;


use App\Models\ExcelDataModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelUserDataImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        return [];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

}
