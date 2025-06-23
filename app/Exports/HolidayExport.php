<?php

namespace App\Exports;

use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class HolidayExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Holiday::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Type',
            'Date',
            'Created At',
            'Updated At'
        ];
    }

    public function map($holiday): array
    {
        return [
            $holiday->id,
            $holiday->title,
            $holiday->type,
            Carbon::parse($holiday->date)->format('Y-m-d'),
            $holiday->created_at ? Carbon::parse($holiday->created_at)->format('Y-m-d H:i:s') : null,
            $holiday->updated_at ? Carbon::parse($holiday->updated_at)->format('Y-m-d H:i:s') : null,
        ];
    }
} 