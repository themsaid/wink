<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wink.</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/styles/github.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/highlight.min.js"></script>

    <!-- Style sheets-->
    <link href='{{mix('app.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="/vendor/wink/favicon.png" />
</head>
<body>
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
    window.Wink = <?php echo json_encode(array_merge(
        \Wink\Wink::scriptVariables(), []
    )); ?>;
</script>

<script src="{{mix('app.js', 'vendor/wink')}}"></script>
</body>
</html>
