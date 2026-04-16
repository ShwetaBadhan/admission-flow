<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Colleges Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f4f4f4; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Colleges Report</h2>
        <p>Generated: {{ date('d M Y H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Phone</th>
                <th>State</th><th>City</th><th>Status</th><th>Courses</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colleges as $college)
            <tr>
                <td>{{ $college->name }}</td>
                <td>{{ $college->email }}</td>
                <td>{{ $college->phone }}</td>
                <td>{{ $college->state->name ?? 'N/A' }}</td>
                <td>{{ $college->city->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($college->status) }}</td>
                <td>{{ implode(', ', $college->course_names ?? []) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>