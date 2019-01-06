<!DOCTYPE html>
<html lang="en" class="font-sans antialiased">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wink. — Reset Password</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather|Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">

    <!-- Style sheets-->
    <link href='{{mix('light.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>

    <!-- Icon-->
    <link rel="icon" type="image/png" href="/vendor/wink/favicon.png"/>
</head>
<body class="text-text-color mb-20">
<div class="container mt-20">
    <div class="xl:w-1/2 mx-auto">
        <div class="flex items-center mb-10">
            <h2 class="mr-2 font-semibold font-serif" :class="{'hidden': hideLogoOnSmallScreens, 'sm:block': hideLogoOnSmallScreens}">
                <span class="text-light">W</span>ink.
            </h2>

            <h2 class="font-normal">— Your New Password</h2>
        </div>

        <p class="mb-5 leading-normal">Copy your new password, use it for your
            <a class="text-primary no-underline" href="{{route('wink.auth.login')}}">next login</a>, and then reset it.
        </p>

        <span class="bg-lighter text-sm p-1">{{$password}}</span>
    </div>
</div>

</body>
</html>
