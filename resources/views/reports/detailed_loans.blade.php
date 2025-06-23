<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Loan Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        h1 {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
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
        .total {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <h1>Detailed Loan Report</h1>
    <p>From {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>

    <h2>SSS Loans</h2>
    <!-- Add a table or list of SSS loans here -->

    <h2>Pag-IBIG Loans</h2>
    <!-- Add a table or list of Pag-IBIG loans here -->

    <h2>Cash Advances</h2>
    <!-- Add a table or list of cash advances here -->

    <h2>Summary</h2>
    <div class="summary">
        <p>Total SSS Loans: <span class="total">Php{{ number_format($totalSssLoan, 2, '.', ',') }}</span></p>
        <p>Total Pag-IBIG Loans: <span class="total">Php{{ number_format($totalPagibigLoan, 2, '.', ',') }}</span></p>
        <p>Total Cash Advances: <span class="total">Php{{ number_format($totalCashAdvance, 2, '.', ',') }}</span></p>
        <p>Total Amount: <span class="total">Php{{ number_format($totalAmount, 2, '.', ',') }}</span></p>
    </div>
</body>
</html>
