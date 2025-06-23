<?php

namespace App\Exports;

use App\Models\ItInventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItInventoryExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ItInventory::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param ItInventory $inventory
     * @return array
     */
    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->name,
            $inventory->description,
            $inventory->created_at,
            $inventory->updated_at
        ];
    }
} 