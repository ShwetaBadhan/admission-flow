<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Lead::with(['city', 'state', 'leadSource', 'consultant', 'qualification', 'intake', 'priority'])
            ->when($this->filters['search'] ?? null, function($q) {
                $search = $this->filters['search'];
                $q->where(function($query) use ($search) {
                    $query->where('full_name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%")
                          ->orWhere('mobile', 'LIKE', "%{$search}%");
                });
            })
            ->when($this->filters['statuses'] ?? null, function($q) {
                $q->whereIn('status', $this->filters['statuses']);
            })
            ->when($this->filters['created_date'] ?? null, function($q) {
                $q->whereDate('created_at', $this->filters['created_date']);
            })
            ->when($this->filters['lead_owners'] ?? null, function($q) {
                $q->whereIn('consultant_id', $this->filters['lead_owners']);
            })
            // 🔐 Role-based filter
            ->when(function() {
                $user = Auth::user();
                return $user && $user->hasRole('consultant');
            }, function($q) {
                $user = Auth::user();
                $consultant = \App\Models\Consultant::where('email', $user->email)->first();
                return $consultant 
                    ? $q->where('consultant_id', $consultant->id) 
                    : $q->whereRaw('1 = 0');
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Full Name', 'Mobile', 'Email', 'City', 'State', 
            'Qualification', 'Course', 'Intake', 'Lead Source', 
            'Priority', 'Status', 'Consultant', 'Created At'
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->full_name,
            $lead->mobile,
            $lead->email,
            $lead->city?->name ?? 'N/A',
            $lead->state?->name ?? 'N/A',
            $lead->qualification?->name ?? 'N/A',
            $lead->course?->name ?? 'N/A',
            $lead->intake?->name ?? 'N/A',
            $lead->leadSource?->name ?? 'N/A',
            $lead->priority?->name ?? 'N/A',
            ucfirst($lead->status),
            $lead->consultant?->name ?? 'N/A',
            $lead->created_at?->format('Y-m-d H:i') ?? 'N/A'
        ];
    }
}