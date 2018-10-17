@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.index') }}">Profile</a></li>
        <li>Edit Profile</li>
    </ul>

    <div class="container">
        {!! Form::open(['url' => 'profile/' .$profile->id, 'method' => 'PATCH']) !!}
        {!! Form::token() !!}

        {!! Form::label('first_name', 'First name'); !!}
        {!! Form::text('first_name', $profile->user->first_name, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('first_name'))
            <div class="invalid-feedback">
                @foreach ($errors->get('first_name') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {!! Form::label('last_name', 'Last name'); !!}
        {!! Form::text('last_name', $profile->user->last_name, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('last_name'))
            <div class="invalid-feedback">
                @foreach ($errors->get('last_name') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {!! Form::label('birthdate', 'Age'); !!}
        {!! Form::date('birthdate', $profile->user->birthdate, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('birthdate'))
            <div class="invalid-feedback">
                @foreach ($errors->get('birthdate') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {!! Form::label('email', 'Email'); !!}
        {!! Form::email('email', $profile->user->email, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('email'))
            <div class="invalid-feedback">
                @foreach ($errors->get('email') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {!! Form::label('phonenr', 'Phonenr'); !!}
        {!! Form::text('phonenr', $profile->user->phonenr, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('phonenr'))
            <div class="invalid-feedback">
                @foreach ($errors->get('phonenr') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {!! Form::label('description', 'Bio'); !!}
        {!! Form::text('description', $profile->description, ['class' => 'form-control']); !!}
        @if ($errors->any() && $errors->has('description'))
            <div class="invalid-feedback">
                @foreach ($errors->get('description') as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <div class="form-group">
            {!! Form::submit('Edit profile', ['class' => 'form-control btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>

@endsection
