<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wink. — Reset Password</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/styles/github.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/highlight.min.js"></script>

    <!-- Style sheets-->
    <link href='{{mix('app.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="/vendor/wink/favicon.png" />
</head>
<body>
<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-5">
                <h3 class="mb-0 mr-3 logo"><span class="text-secondary">W</span>ink.</h3>

                <h4 class="mb-0">— New Password</h4>
            </div>

            <p class="mb-4">Copy your new password, use it for your <a href="/wink/login">next login</a>, and then reset it.</p>

            <span style="font-size:8px;" class="bg-info">{{$password}}</span>
        </div>
    </div>
</div>

</body>
</html>
