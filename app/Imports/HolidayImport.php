<?php

namespace App\Imports;

use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Exception;

class HolidayImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Try to parse date from various formats
     * @param string $date
     * @return string
     * @throws Exception
     */
    protected function parseDate($date)
    {
        // If it's already a Carbon instance
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }

        // If it's an Excel date number
        if (is_numeric($date)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
        }

        // Try common date formats
        $formats = [
            'Y-m-d',           // 2024-03-21
            'd-m-Y',           // 21-03-2024
            'm-d-Y',           // 03-21-2024
            'Y/m/d',           // 2024/03/21
            'd/m/Y',           // 21/03/2024
            'm/d/Y',           // 03/21/2024
            'F j, Y',          // March 21, 2024
            'M j, Y',          // Mar 21, 2024
            'd F Y',           // 21 March 2024
            'd M Y',           // 21 Mar 2024
            'Y-m-d H:i:s',     // 2024-03-21 15:30:00
            'Y-m-d\TH:i:s',    // 2024-03-21T15:30:00
            'Y-m-d\TH:i:sP',   // 2024-03-21T15:30:00+00:00
        ];

        // Replace various separators with a standard one
        $date = str_replace(['/', '.'], '-', trim($date));

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (Exception $e) {
                continue;
            }
        }

        // If all formats fail, try Carbon's parse method as a last resort
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (Exception $e) {
            throw new Exception("Unable to parse date: {$date}");
        }
    }

    public function model(array $row)
    {
        try {
            return new Holiday([
                'title' => $row['title'],
                'type'  => $row['type'],
                'date'  => $this->parseDate($row['date']),
            ]);
        } catch (Exception $e) {
            throw new Exception("Error processing row: " . json_encode($row) . ". Error: " . $e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', Holiday::types()),
            'date' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'type.in' => 'The holiday type must be one of: ' . implode(', ', Holiday::types()),
            'date.required' => 'The date field is required.',
        ];
    }
} 