<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    ShouldAutoSize,
    WithHeadings,
    WithStyles,
    WithEvents,
    WithMapping
};

class CompaniesExport implements ShouldAutoSize, WithHeadings, FromArray, WithStyles, WithEvents, WithMapping
{
    private static $response;

    public function __construct()
    {
        self::$response = Http::get('http://127.0.0.1:8000/api/all-company')->json();

        date_default_timezone_set('Asia/Jakarta');
    }

    public function array(): array
    {
        return self::$response;
    }

    public function map($response): array
    {
        return [
            $response['no'],
            $response['id'],
            $response['name'],
            $response['email'],
            $response['phone'],
            $response['address'],
            $response['created_at'],
            $response['updated_at'],
        ];
    }

    public function headings(): array
    {
        return [
            ['Export Data User'],
            ['Jumlah User ', count(self::$response)],
            ['Di Export Pada ', date("Y-m-d h:i:s")],
            [],
            ['No', 'Id', 'Nama', 'Email', 'Phone', 'Address', 'Created', 'Update'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        $dataCount = count(self::$response) + 5;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($dataCount) {

                $event->sheet
                    ->getDelegate()
                    ->getStyle('A1:H' . $dataCount)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
