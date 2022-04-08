<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OkTracker</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        body{
        }
        .center {
            position: absolute;
left: 50%;
top: 50%;
transform: translate(-50%, -50%);
padding: 10px;
        }

    </style>
    
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="bg-gray-800">
    <div class="center text-center text-gray-200">
        <h2 class="text-2xl font-semibold">Email Address Verified Successfully!</h2>
        <p class="text-lg">You can go back to the app and Login.</p>
    </div>
</body>

</html>
