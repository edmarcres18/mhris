<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1, h2 {
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
    <h1>Attendance Report</h1>
    <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Hours Worked</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->employee->employee_id }}</td>
                <td>{{ $attendance->employee->full_name }}</td>
                <td>{{ $attendance->date_attended->format('M d, Y') }}</td>
                <td>{{ $attendance->time_in }}</td>
                <td>{{ $attendance->time_out }}</td>
                <td>{{ number_format($attendance->hours_worked, 2) }}</td>
                <td>{{ $attendance->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Attendance Records: {{ $attendances->count() }}</p>
        <p>Total Hours Worked: {{ number_format($totalHoursWorked, 2) }}</p>
        <p>Average Hours Worked Per Day: {{ number_format($totalHoursWorked / $attendances->count(), 2) }}</p>
    </div>

    <footer>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
