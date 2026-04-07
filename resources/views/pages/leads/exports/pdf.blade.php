<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leads Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background: #f4f4f4; font-weight: bold; }
        .header { text-align: center; margin-bottom: 15px; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .bg-warning { background: #ffc107; color: #000; }
        .bg-info { background: #0dcaf0; color: #000; }
        .bg-primary { background: #0d6efd; color: #fff; }
        .bg-success { background: #198754; color: #fff; }
        .bg-danger { background: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h3>Leads Report</h3>
        <p>Generated: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Mobile</th><th>Email</th>
                <th>City</th><th>State</th><th>Status</th><th>Source</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leads as $lead)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lead->full_name }}</td>
                <td>{{ $lead->mobile }}</td>
                <td>{{ $lead->email ?? 'N/A' }}</td>
                <td>{{ $lead->city?->name ?? 'N/A' }}</td>
                <td>{{ $lead->state?->name ?? 'N/A' }}</td>
                <td>
                    @if(isset($statuses[$lead->status]))
                        <span class="badge bg-{{ $statuses[$lead->status]['color'] }}">
                            {{ $statuses[$lead->status]['label'] }}
                        </span>
                    @else
                        {{ ucfirst($lead->status) }}
                    @endif
                </td>
                <td>{{ $lead->leadSource?->name ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;">No leads found</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>