@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li><a href="{{ route('studieroute.show', $studieRoute) }}">{{ $studieRoute->name }}</a></li>
        <li><a href="{{ route('task.show', [$studieRoute, $task]) }}">{{ $task->name }}</a></li>
        <li>Create submission</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Create Submission
                </div>
            </div>

            <div class="card-body">
                <div class="panel-body">
                    <div class="container">
                        {!! Form::open(["URL" => route('task.store', [$studieRoute, $task]), "METHOD" => "PUT"]) !!}

                        <div class="form-group">
                            {!! Form::label('description', 'Description') !!}
                            {!! Form::text('description', old('description') ?: '', ['class' => 'form-control']) !!}
                            @if ($errors->has('description'))
                                <div class="invalid-feedback">
                                    @foreach ($errors->get('description') as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
@endsection

