@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Chat</li>
    </ul>
    <div class="container">
        <ng-view></ng-view>
    </div>
@endsection

@section('scripts')
    <script>
        @if(env('SOCKET_URL'))
            var socketURL = '{{ env('SOCKET_URL') }}';
        @else
            var socketURL = '{{ env('APP_URL', 'http://localhost') }}:{{ env('SOCKET_PORT') }}';
        @endif

        var socketAuthToken = '{{ Auth::user()->socket_auth_token }}';
        var userId = {{ Auth::user()->id }};
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-route.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angularjs-scroll-glue/2.2.0/scrollglue.min.js"></script>

    <script src="{{ asset('js/angular/init.js') }}"></script>
    <script src="{{ asset('js/angular/filters.js') }}"></script>
    <script src="{{ asset('js/angular/SocketFactory.js') }}"></script>
    <script src="{{ asset('js/angular/chat/controller.js') }}"></script>
    <script src="{{ asset('js/angular/chat/directive.js') }}"></script>
    <script src="{{ asset('js/angular/chat/filters.js') }}"></script>
    <script src="{{ asset('js/angular/chat/service.js') }}"></script>

    <script src="{{ asset('js/angular/userlist/directive.js') }}"></script>
@endsection