<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Employee([
            'company_id'     => $row['company_id'],
            'profile'     => $row['profile'],
            'first_name'     => $row['first_name'],
            'middle_name'    => $row['middle_name'] ?? null,
            'last_name'      => $row['last_name'],
            'suffix'         => $row['suffix'] ?? null,
            'email_address'  => $row['email_address'],
            'contact_no'     => $row['contact_no'],
            'birth_date'     => isset($row['birth_date']) ? \Carbon\Carbon::parse($row['birth_date'])->format('Y-m-d') : null,
            'birth_place_province' => $row['birth_place_province'] ?? null,
            'birth_place_city' => $row['birth_place_city'] ?? null,
            'birth_place_barangay' => $row['birth_place_barangay'] ?? null,
            'province_id'    => $row['province_id'],
            'city_id'        => $row['city_id'],
            'barangay_id'    => $row['barangay_id'],
            'gender_id'      => $row['gender_id'],
            'position_id'    => $row['position_id'],
            'department_id'  => $row['department_id'],
            'salary'         => $row['salary'],
            'zip_code'       => $row['zip_code'] ?? null,
            'date_hired'     => isset($row['date_hired']) ? \Carbon\Carbon::parse($row['date_hired'])->format('Y-m-d') : null,
            'sss_no'         => $row['sss_no'] ?? null,
            'pagibig_no'     => $row['pagibig_no'] ?? null,
            'tin_no'         => $row['tin_no'] ?? null,
            'philhealth_no'  => $row['philhealth_no'] ?? null,
            'elementary'     => $row['elementary'] ?? null,
            'secondary'      => $row['secondary'] ?? null,
            'tertiary'       => $row['tertiary'] ?? null,
            'emergency_name' => $row['emergency_name'],
            'emergency_no'   => $row['emergency_no'],
            'employment_status' => $row['employment_status'] ?? null,
            'employee_status' => $row['employee_status'] ?? null,
            'rank' => $row['rank'] ?? null,
        ]);
    }


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email_address' => 'required|email|unique:employees,email_address',
            'province_id' => 'required',
            'city_id' => 'required',
            'barangay_id' => 'required',
            'position_id' => 'required',
            'department_id' => 'required',
            'gender_id' => 'required',
            'contact_no' => 'required',
            'birth_date' => 'nullable|date',
            'salary' => 'required|numeric',
            'date_hired' => 'nullable|date',
            'emergency_name' => 'required',
            'emergency_no' => 'required|numeric',
            'employment_status' => 'nullable|string',
            'employee_status' => 'nullable|string',
            'rank' => 'nullable|string',
        ];
    }
}
