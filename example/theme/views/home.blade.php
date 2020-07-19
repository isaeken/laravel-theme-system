<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ theme_detail('name') }}</title>
    <link rel="stylesheet" href="{{ theme_asset('css/app.css') }}">
    <script src="{{ theme_asset('js/app.js') }}"></script>
</head>
<body>
    <ul>
        <li {{ ispage('homepage') ? 'class="active"' : '' }}>Home Page</li>
        <li {{ ispage('settings') ? 'class="active"' : '' }}>Settings</li>
    </ul>
    <hr>
    <h1>Current Theme: {{ theme_detail('name') }}</h1>
    @themeSetting('dark')
        <h2>This theme is dark layout</h2>
    @else
        <h2>This theme is light layout</h2>
    @endif
    @page('homepage')
        <h3>Current page is <i>homepage</i></h3>
    @elsepage('settings')
        <h3>Current page is <i>settings</i></h3>
    @else
        <h3>Another page</h3>
    @endpage
</body>
</html>
