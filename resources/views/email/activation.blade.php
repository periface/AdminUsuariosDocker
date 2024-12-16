<!DOCTYPE html>
<html>
<head>
    <title>Activate Your Account</title>
</head>
<body>
    <h1>Welcome to {{ config('app.name') }}</h1>
    <p>Thank you for registering. Please activate your account using the link below:</p>
    <a href="{{ $activationUrl }}">Activate Account</a>
</body>
</html>
