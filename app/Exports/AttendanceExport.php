<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Attendance::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Employee ID',
            'Date Attended',
            'Time In',
            'Time Out',
            'Time Stamp 1',
            'Time Stamp 2',
            'Remarks',
            'Hours Worked',
        ];
    }
}
