<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <p>Hi {{$user->name}},<br><br> Looks like you requested a password change, use the code below to update your password: </p>
    <h2> {{$user->remember_token}} </h2>

    <p>Regards,<br> OkTracker Team</p>
</body>
</html>