<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Report</title>
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
    <h1>Hiring Report</h1>
    <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Department</th>
                <th>Date Posted</th>
                <th>Status</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hirings as $hiring)
            <tr>
                <td>{{ $hiring->position }}</td>
                <td>{{ $hiring->department }}</td>
                <td>{{ $hiring->created_at->format('M d, Y') }}</td>
                <td>{{ $hiring->status }}</td>
                <td>{{ $hiring->location }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Positions: {{ $totalPositions }}</p>

        <h3>Positions by Department</h3>
        @foreach($positionsByDepartment as $department => $count)
        <p>{{ $department }}: {{ $count }}</p>
        @endforeach

        <h3>Positions by Status</h3>
        @php
            $statusCounts = $hirings->groupBy('status')->map->count();
        @endphp
        @foreach($statusCounts as $status => $count)
        <p>{{ $status }}: {{ $count }}</p>
        @endforeach
    </div>

    <footer>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
