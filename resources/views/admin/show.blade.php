@extends('layouts.app')

@section('content')
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">{{ $user->first_name }}</h1>
            <h1 class="display-3">{{ $user->last_name }}</h1>
            <h1 class="display-3">{{ $user->birthdate }}</h1>
            <h1 class="display-3">{{ $user->email }}</h1>
            <h1 class="display-3">{{ $user->phonenr }}</h1>
        </div>
    </div>
@endsection