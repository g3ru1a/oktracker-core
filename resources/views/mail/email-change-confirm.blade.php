<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <p>Hi {{ $user->name }},<br><br> Looks like you requested an email change, follow the link below to verify the new
        email address: </p>
    <br>
    <a
        href="{{ env('APP_URL') }}/api/v1/auth/email-change/{{ $user->id }}/{{ $encrypted_email }}/{{ $user->remember_token }}">
        {{ env('APP_URL') }}/api/v1/auth/email-change/{{ $user->id }}/{{ $encrypted_email }}/{{ $user->remember_token }}
    </a>

    <p>Regards,<br> OkTracker Team</p>
</body>

</html>
