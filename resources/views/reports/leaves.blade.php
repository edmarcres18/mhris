<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1, h2, h3 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('vendor/adminlte/dist/img/LOGO4.png') }}" alt="Company Logo" class="logo">
    <h1>Leave Report</h1>
    <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Duration (Days)</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->employee_id }}</td>
                <td>{{ $leave->employee->full_name }}</td>
                <td>{{ $leave->type->name }}</td>
                <td>{{ $leave->date_from->format('M d, Y') }}</td>
                <td>{{ $leave->date_to->format('M d, Y') }}</td>
                <td>{{ $leave->date_from->diffInDays($leave->date_to) + 1 }}</td>
                <td>{{ $leave->status }}</td>
                <td>{{ $leave->reason_to_leave }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary</h2>
        <h3>Leave Types</h3>
        @foreach($leaveTypeCounts as $type => $count)
        <p>{{ $type }}: {{ $count }}</p>
        @endforeach

        <h3>Leave Status</h3>
        @php
            $statusCounts = $leaves->groupBy('status')->map->count();
        @endphp
        @foreach($statusCounts as $status => $count)
        <p>{{ $status }}: {{ $count }}</p>
        @endforeach

        <p><strong>Total Leaves: {{ $leaves->count() }}</strong></p>
    </div>

    <footer>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
