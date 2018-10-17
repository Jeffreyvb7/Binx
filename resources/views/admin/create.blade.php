@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.index') }}">Admin Page</a></li>
        <li>Create User</li>
    </ul>
    <div class="container">
        {!! Form::open(['url' => 'admin', 'method' => 'POST']) !!}
        {!! Form::token() !!}

        <div class="form-group">
            {!! Form::label('first_name', 'First name'); !!}
            {!! Form::text('first_name', '' , ['class' => 'form-control']); !!}
            @if ($errors->any() && $errors->has('first_name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('first_name') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('last_name', 'Last name'); !!}
            {!! Form::text('last_name', '' , ['class' => 'form-control']);!!}
            @if ($errors->any() && $errors->has('last_name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('last_name') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('birthdate', 'Birthdate'); !!}
            {!! Form::date('birthdate', '' , ['class' => 'form-control', 'placeholder' => \Carbon\Carbon::now()->format('Y-m-d')]) !!}
            @if ($errors->any() && $errors->has('birthdate'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('birthdate') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('email', 'Email'); !!}
            {!! Form::text('email', '' , ['class' => 'form-control']); !!}
            @if ($errors->any() && $errors->has('email'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('email') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('password', 'Password'); !!}
            {!! Form::password('password', ['class' => 'form-control']); !!}
            @if ($errors->any() && $errors->has('password'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('password') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('phonenr', 'Phonenr'); !!}
            {!! Form::number('phonenr', '' , ['class' => 'form-control']); !!}
            @if ($errors->any() && $errors->has('phonenr'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('phonenr') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('role', 'Role'); !!}<br>
            <hr>
            @foreach(\App\Role::all() as $role)
                <label>{!! Form::radio('role', $role->id , false); !!} {{ ucfirst($role->name) }}</label><br>
                <hr>
            @endforeach
            @if ($errors->any() && $errors->has('role'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('role') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::submit('Submit', ['class' => 'btn btn-default']); !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection