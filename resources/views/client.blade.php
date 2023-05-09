<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/scss/client/app.scss', 'resources/js/client/app.js'])
</head>
<body>

<div id="app"></div>

</body>
</html>
