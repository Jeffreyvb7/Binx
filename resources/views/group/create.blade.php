@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('group.index') }}">Group page</a></li>
        <li>Create Group</li>
    </ul>

    <div class="container">
        {!! Form::open(['url' => 'group', 'method' => 'POST']) !!}
        {!! Form::token() !!}

        <div class="form-group">
            {!! Form::label('name', 'Name'); !!}
            {!! Form::text('name', '' , ['class' => 'form-control']); !!}
            @if ($errors->any() && $errors->has('name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('name') as $error)
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