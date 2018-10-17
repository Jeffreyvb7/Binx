@extends('layouts.new')

@section('content')
    <div class="container">
        <h1>Create new portfolio</h1>
        {!! Form::open(['URL' => route('portfolio.store', [$profile]), 'method' => 'PUT']) !!}

        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', old('name') ?: '', ['class' => 'form-control']) !!}
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
            {!! Form::textarea('description', old('description') ?: '', ['class' => 'form-control']) !!}
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('description') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@endsection