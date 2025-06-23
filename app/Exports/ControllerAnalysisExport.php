<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ControllerAnalysisExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $controllerData;

    public function __construct($controllerData)
    {
        $this->controllerData = $controllerData;
    }

    public function collection()
    {
        return collect($this->controllerData);
    }

    public function headings(): array
    {
        return [
            'Controller Name',
            'Total Lines',
            'Code Lines',
            'Methods Count',
            'Complexity',
            'File Size (KB)',
            'Last Modified',
            'Permissions',
            'Method Count by Type'
        ];
    }

    public function map($controller): array
    {
        $methodTypes = collect($controller['methods'])
            ->groupBy('type')
            ->map(function ($group) {
                return count($group);
            })
            ->toJson();

        return [
            $controller['name'],
            $controller['metrics']['total_lines'],
            $controller['metrics']['code_lines'],
            $controller['metrics']['methods_count'],
            $controller['metrics']['complexity'],
            $controller['file_size'],
            $controller['last_modified'],
            implode(', ', $controller['permissions']),
            $methodTypes
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['font' => ['bold' => true]],
        ];
    }
}
