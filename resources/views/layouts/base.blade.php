<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @section('title')
            city_matters
        @show
    </title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
    <style type="text/css">
        body {
            margin: 0;
        }
    </style>
    @stack('additonalHeaderStyles')
    @stack('additonalHeaderScripts')
</head>
<body>
<div id="app">
    @section('app')
        No content loaded.
    @show
</div>
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>

@stack('additonalScripts')

</body>
</html>
