<!DOCTYPE html>
<html>
<head>
    <title>Your Profile Has Been Updated</title>
</head>
<body>
    <h1>Hello, {{ $user->first_name }}</h1>
    <p>Your profile has been updated successfully.</p>

    <h2>Changed Data:</h2>
    <ul>
        @foreach($changedData as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>

    <p>Thank you for keeping your profile up to date!</p>
</body>
</html>
