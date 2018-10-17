<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Binx</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <!-- Styles -->
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">

    <base href="/">
</head>
<body>
<div id="modalUnderlay" class="darkUnderlay"></div>
@include('layouts.partials.navigation')

<main>
    @yield('content')
</main>

@include('layouts.partials.footer')

<div id="toasts"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

@yield('scripts')

<script src="{{ asset('/assets/js/binx.js') }}"></script>
@if(\Session::has(\Alert::getSessionKey()))
    <script>
        @foreach(session(\Alert::getSessionKey()) as $alert)
        binx.toast('{{ $alert['title'] }}', '{{ $alert['message'] }}', '{{ 'toast-' . $alert['type'] }}');
        @endforeach
    </script>
@endif
</body>
</html>
