<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Report</title>
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
    <h1>Career Report</h1>
    <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Position</th>
                <th>Application Date</th>
                <th>Status</th>
                <th>Experience</th>
                <th>Interview Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($careers as $career)
            <tr>
                <td>{{ $career->first_name }} {{ $career->last_name }}</td>
                <td>{{ $career->hiring->position }}</td>
                <td>{{ $career->created_at->format('M d, Y') }}</td>
                <td>{{ $career->status }}</td>
                <td>{{ $career->experience }}</td>
                <td>{{ $career->interview_date ? $career->interview_date->format('M d, Y') : 'Not scheduled' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Applications: {{ $totalApplications }}</p>

        <h3>Applications by Position</h3>
        @foreach($applicationsByPosition as $position => $count)
        <p>{{ $position }}: {{ $count }}</p>
        @endforeach

        <h3>Application Status</h3>
        @foreach($applicationStatus as $status => $count)
        <p>{{ $status }}: {{ $count }}</p>
        @endforeach

        <h3>Experience Level of Applicants</h3>
        @php
            $experienceLevels = $careers->groupBy('experience')->map->count();
        @endphp
        @foreach($experienceLevels as $level => $count)
        <p>{{ $level }} years: {{ $count }}</p>
        @endforeach
    </div>

    <footer>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
