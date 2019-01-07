<!DOCTYPE html>
<html lang="en" class="font-sans antialiased">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wink.</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather|Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">

    <!-- Icon-->
    <link rel="icon" type="image/png" href="/vendor/wink/favicon.png"/>

    <!-- Highlight JS sheets -->
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/highlight.min.js"></script>

    <!-- Style sheets-->
    @if(@auth('wink')->user()->meta['theme'] == 'dark')
        <link href='{{mix('dark.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/styles/sunburst.min.css">
    @else
        <link href='{{mix('light.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/styles/github.min.css">
    @endif
</head>

<body class="text-text-color mb-20">

<div id="wink" v-cloak>
    <alert :message="alert.message"
           :type="alert.type"
           :auto-close="alert.autoClose"
           :confirmation-proceed="alert.confirmationProceed"
           :confirmation-cancel="alert.confirmationCancel"
           v-if="alert.type"></alert>


    <notification :message="notification.message"
                  :type="notification.type"
                  :auto-close="notification.autoClose"
                  v-if="notification.type"></notification>


    <router-view></router-view>
</div>

<!-- Global Wink Object -->
<script>
    window.Wink = @json($winkScriptVariables);
</script>

<script src="{{mix('app.js', 'vendor/wink')}}"></script>
</body>
</html>
