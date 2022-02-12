<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\{ToArray, WithHeadingRow};


class CompaniesImport implements ToArray, WithHeadingRow
{
    public function array(array $array)
    {
    }

    public function headingRow(): int
    {
        return 5;
    }
}
