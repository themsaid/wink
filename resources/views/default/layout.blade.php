<!DOCTYPE html>
<html lang="en" class="font-serif antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ config('app.name') ?? 'My blog' }}</title>
    
    @if(config('wink.default.google_verification'))
    <meta name="google-site-verification" content="{{ config('wink.default.google_verification') }}"/>
    @endif
    
    @yield('metas')
    
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.13.1/build/styles/github.min.css">
    
    <!-- Styles -->
    {{-- TODO: Generate our own styles instead of using @themsaid 😅 --}}
    <link href="https://themsaid.com/css/theme.css?id=288375f93599036f3a90" rel="stylesheet">
    @yield('styles')

    @if(config('win.default.google_analytics'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('wink.default.google_analytics') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', {{ config('wink.default.google_analytics') }});
    </script>
    @endif
</head>
<body class="text-black mb-20">

<div class="container mx-auto">
    <header class="mb-20 mt-10">
        <h1 class="text-center font-thin">
            <a href="{{ route('wink.default.posts.index')  }}" class="no-underline text-black">{{ config('app.name') ?? 'My Blog' }}</a>
        </h1>
        <span class="text-center block italic text-light mt-4 text-sm">{{ config('wink.default.site_description') }}</span>
    </header>
    
    @yield('content')

</div>
@yield('scripts')
</body>
</html>

