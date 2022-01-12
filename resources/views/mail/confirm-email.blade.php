<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <p>Thank you for registering on OkTracker! To verify your account please follow the link below.</p>
    <br>
    <a href="{{env('APP_URL')}}/api/v1/auth/verify/{{$user->id}}/{{$user->remember_token}}"> {{env('APP_URL')}}/api/v1/auth/verify/{{$user->id}}/{{$user->remember_token}} </a>

    <p>Regards,<br> OkTracker Team</p>
</body>
</html>