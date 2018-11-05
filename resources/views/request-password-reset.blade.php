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

                <h4 class="mb-0">— Reset Password</h4>
            </div>

            @if ($errors->any())
                <div class="font-semibold text-danger mb-4">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @endif
                </div>
            @endif

            @if(session()->has('invalidResetToken'))
                <div class="font-semibold text-danger mb-4">
                    Invalid reset token.
                </div>
            @endif

            @if (session()->has('sent'))
                <div class="font-semibold text-success mb-4">
                    You should receive an email in a bit.
                </div>
            @endif

            <form method="POST" action="{{route('wink.password.email')}}">
                @csrf

                <div class="form-group border-bottom pb-3 mb-5">
                    <label for="email" class="inline-form-control-label">Email Address</label>
                    <input type="email" class="inline-form-control text-body-color"
                           name="email" id="email"
                           placeholder="mail@example.com">
                </div>

                <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
