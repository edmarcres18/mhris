<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Controller Analysis Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .controller-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
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
            background-color: #f4f4f4;
        }
        .metrics-table {
            margin-bottom: 15px;
        }
        .methods-table {
            font-size: 0.9em;
        }
        .permissions {
            margin-bottom: 15px;
        }
        .dependencies {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Controller Analysis Report</h1>
        <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </div>

    @foreach($controllerData as $controller)
    <div class="controller-section">
        <h2>{{ $controller['name'] }}</h2>

        <h3>Metrics</h3>
        <table class="metrics-table">
            <tr>
                <th>Total Lines</th>
                <td>{{ $controller['metrics']['total_lines'] }}</td>
                <th>Code Lines</th>
                <td>{{ $controller['metrics']['code_lines'] }}</td>
            </tr>
            <tr>
                <th>Methods Count</th>
                <td>{{ $controller['metrics']['methods_count'] }}</td>
                <th>Complexity</th>
                <td>{{ $controller['metrics']['complexity'] }}</td>
            </tr>
            <tr>
                <th>File Size</th>
                <td>{{ $controller['file_size'] }} KB</td>
                <th>Last Modified</th>
                <td>{{ $controller['last_modified'] }}</td>
            </tr>
        </table>

        <h3>Methods</h3>
        <table class="methods-table">
            <thead>
                <tr>
                    <th>Method Name</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($controller['methods'] as $method)
                <tr>
                    <td>{{ $method['name'] }}</td>
                    <td>{{ $method['type'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="permissions">
            <h3>Permissions</h3>
            @if(count($controller['permissions']) > 0)
                @foreach($controller['permissions'] as $permission)
                    {{ $permission }}@if(!$loop->last), @endif
                @endforeach
            @else
                No permissions specified
            @endif
        </div>

        <div class="dependencies">
            <h3>Dependencies</h3>
            @if(count($controller['dependencies']) > 0)
                @foreach($controller['dependencies'] as $dependency)
                    {{ $dependency }}@if(!$loop->last), @endif
                @endforeach
            @else
                No dependencies found
            @endif
        </div>
    </div>
    @endforeach
</body>
</html>
