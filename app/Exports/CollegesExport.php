<?php

namespace App\Exports;

use App\Models\College;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollegesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $colleges;

    public function __construct($colleges)
    {
        $this->colleges = $colleges;
    }

    public function collection()
    {
        return $this->colleges;
    }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Email', 'Phone', 'Website', 
            'State', 'City', 'Fees Range', 'Status', 'Courses', 'Created At'
        ];
    }

    public function map($college): array
    {
        return [
            $college->id,
            $college->name,
            $college->email,
            $college->phone,
            $college->website,
            $college->state->name ?? 'N/A',
            $college->city->name ?? 'N/A',
            $college->fees_range,
            $college->status,
            implode(', ', $college->course_names ?? []),
            $college->created_at->format('Y-m-d'),
        ];
    }
}