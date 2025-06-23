<!-- resources/views/leaves/report_pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>All Leaves Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>All Leaves Report</h1>

    @if($departmentId)
        <h3>Department: {{ $departments->find($departmentId)->name }}</h3>
    @endif

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th>Date From</th>
                <th>Date To</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->name }}</td>
                    <td>{{ $leave->employee->department->name }}</td>
                    <td>{{ $leave->date_from }}</td>
                    <td>{{ $leave->date_to }}</td>
                    <td>{{ $leave->type->name }}</td>
                    <td>{{ $leave->reason_to_leave }}</td>
                    <td>{{ $leave->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
