@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li>Create Studieroute</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create Studieroutes
                </div>
            </div>

            <div class="card-body">

                {!! Form::open(['url' => 'studieroute', 'method' => 'POST']) !!}
                {!! Form::token() !!}


                <div class="form-group">
                    {{ Form::label('name', 'Title') }}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    {{ Form::label('key', 'Key') }}
                    {!! Form::text('key', old('key'), ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('key'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('key') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {!! Form::textarea('description', old('description') , ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('description'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('description') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    {{ Form::label('due_date', 'Due Date') }}
                    {{ Form::date('due_date', '', ['class' => 'form-control', 'placeholder' => \Carbon\Carbon::now()->format('Y-m-d') ]) }}
                    @if ($errors->any() && $errors->has('due_date'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('due_date') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                </div>

                <br>

                <div class="form-group">
                    {!! Form::submit('Opslaan', ['class' => 'form-control btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection