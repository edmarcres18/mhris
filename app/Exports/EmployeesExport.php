<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    // Define the attribute names here
    private $attributes = [
        'company_id',
        'profile',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email_address',
        'contact_no',
        'birth_date',
        'birth_place_province',
        'birth_place_city',
        'birth_place_barangay',
        'province_id',
        'city_id',
        'barangay_id',
        'gender_id',
        'position_id',
        'department_id',
        'salary',
        'zip_code',
        'date_hired',
        'sss_no',
        'pagibig_no',
        'tin_no',
        'philhealth_no',
        'elementary',
        'secondary',
        'tertiary',
        'emergency_name',
        'emergency_no',
        'employment_status',  // Added employment_status
        'employee_status',    // Added employee_status
        'rank',  // Added rank
    ];

    /**
     * Return a collection of employees.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::all();
    }

    /**
     * Return the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'company_id',
            'profile',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'email_address',
            'contact_no',
            'birth_date',
            'birth_place_province',
            'birth_place_city',
            'birth_place_barangay',
            'province_id',
            'city_id',
            'barangay_id',
            'gender_id',
            'position_id',
            'department_id',
            'salary',
            'zip_code',
            'date_hired',
            'sss_no',
            'pagibig_no',
            'tin_no',
            'philhealth_no',
            'elementary',
            'secondary',
            'tertiary',
            'emergency_name',
            'emergency_no',
            'employment_status',  // Added employment_status
            'employee_status',    // Added employee_status
            'rank',  // Added rank
        ];
    }

    /**
     * Map the data to the desired export format.
     *
     * @param \App\Models\Employee $employee
     * @return array
     */
    public function map($employee): array
    {
        return array_map(function($attribute) use ($employee) {
            // Special handling for date attributes
            if (in_array($attribute, ['birth_date', 'date_hired'])) {
                return $this->formatDate($employee->{$attribute});
            }
            return $employee->{$attribute};
        }, $this->attributes);
    }

    /**
     * Apply styles to the header row.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AC1')->getFont()->setBold(true);
        $sheet->getStyle('A1:AC1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Yellow background
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        // Set additional column widths as needed
    }

    /**
     * Format the date field.
     *
     * @param string|null $date
     * @return string
     */
    private function formatDate($date): string
    {
        if (!$date) {
            return '';
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return ''; // Return empty string if date parsing fails
        }
    }
}
