<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 150px; /* Adjust size as needed */
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f9f9f9;
            margin: 10px 0;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Please enable images to view this email correctly.</p>
        <img src="https://192.168.1.74:8000/vendor/adminlte/dist/img/LOGO4.png" alt="Company Logo" class="logo">
        <h1>Notifications</h1>
        <ul>
            @foreach ($notifications as $notification)
                <li>{{ $notification }}</li>
            @endforeach
        </ul>
        <div class="footer">
            &copy; {{ date('Y') }} {{config('app.name')}}. All rights reserved.
        </div>
    </div>
</body>
</html>
