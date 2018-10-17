@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.index') }}">Profile</a></li>
        <li><a href="{{ route('portfolio.show', [$portfolio->user, $portfolio]) }}">{{ $portfolio->name }}</a></li>
        <li>Edit Portfolio</li>
    </ul>

    <div class="container">
        <h1>Edit portfolio</h1>
        {!! Form::open(['URL' => route('portfolio.update', [$portfolio->user->profile, $portfolio]), 'method' => 'PATCH']) !!}

        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', old('name') ?: $portfolio->name, ['class' => 'form-control']) !!}
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('name') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description') !!}
            {!! Form::textarea('description', old('description') ?: $portfolio->description, ['class' => 'form-control']) !!}
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('description') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@endsection