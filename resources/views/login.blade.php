<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wink. — Login</title>

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

                <h4 class="mb-0">— Log In</h4>
            </div>

            @if ($errors->any())
                <div class="font-semibold text-danger mb-4">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @else
                        {{ $errors->first('password') }}
                    @endif
                </div>
            @endif

            @if(session()->has('loggedOut'))
                <div class="font-semibold text-success mb-4">
                    You've been logged out.
                </div>
            @endif

            <form method="POST" action="{{route('wink.auth.attempt')}}">
                @csrf

                <div class="form-group border-bottom pb-3">
                    <label for="email" class="inline-form-control-label">Email Address</label>
                    <input type="email" class="inline-form-control text-body-color"
                           name="email" id="email"
                           placeholder="mail@example.com">
                </div>

                <div class="form-group border-bottom pb-3">
                    <label for="password" class="inline-form-control-label">Password</label>
                    <input type="password" class="inline-form-control text-body-color"
                           name="password" id="password"
                           placeholder="******">
                </div>

                <div class="d-flex align-items-center justify-content-between mb-5">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <a href="{{route('wink.password.forgot')}}">Forgot your password?</a>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
