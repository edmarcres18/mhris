<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \App\Models\Attendance|null
     */
    public function model(array $row)
    {
        return new Attendance([
            'employee_id' => $row['employee_id'],
            'date_attended' => $row['date_attended'],
            'time_in' => $row['time_in'] ?? null,
            'time_out' => $row['time_out'] ?? null,
            'time_stamp1' => $row['time_stamp1'] ?? null,
            'time_stamp2' => $row['time_stamp2'] ?? null,
            'remarks' => $row['remarks'],
            'hours_worked' => $row['hours_worked'] ?? null,
        ]);
    }
}
