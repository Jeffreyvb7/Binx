@extends('layouts.new')

@section('content')
    {!! Form::open(['url' => 'profile', 'method' => 'POST']) !!}
    {!! Form::token() !!}
    <div class="form-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::text('description' ,'', array('class' => 'form-control')) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Add Description') !!}
        {!! Form::close() !!}
    </div>
@endsection