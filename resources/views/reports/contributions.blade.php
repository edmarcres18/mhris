<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribution Report</title>
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
    <h1>Contribution Report</h1>
    <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>SSS</th>
                <th>PhilHealth</th>
                <th>Pag-IBIG</th>
                <th>TIN</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contributions as $contribution)
            <tr>
                <td>{{ $contribution->employee->employee_id }}</td>
                <td>{{ $contribution->employee->full_name }}</td>
                <td>{{ $contribution->date->format('M d, Y') }}</td>
                <td>₱{{ number_format($contribution->sss_contribution, 2) }}</td>
                <td>₱{{ number_format($contribution->philhealth_contribution, 2) }}</td>
                <td>₱{{ number_format($contribution->pagibig_contribution, 2) }}</td>
                <td>₱{{ number_format($contribution->tin_contribution, 2) }}</td>
                <td>₱{{ number_format($contribution->sss_contribution + $contribution->philhealth_contribution + $contribution->pagibig_contribution + $contribution->tin_contribution, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total SSS Contributions: ₱{{ number_format($totalContributions['sss'], 2) }}</p>
        <p>Total PhilHealth Contributions: ₱{{ number_format($totalContributions['philhealth'], 2) }}</p>
        <p>Total Pag-IBIG Contributions: ₱{{ number_format($totalContributions['pagibig'], 2) }}</p>
        <p>Total TIN Contributions: ₱{{ number_format($totalContributions['tin'], 2) }}</p>
        <p><strong>Total Contributions: ₱{{ number_format(array_sum($totalContributions), 2) }}</strong></p>
    </div>

    <footer>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
