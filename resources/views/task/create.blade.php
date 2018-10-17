@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroute</a></li>
        <li><a href="{{ route('studieroute.show', $studieRoute) }}">{{ $studieRoute->name }}</a></li>
        <li>Create Task</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create Task
                </div>
            </div>

            <div class="card-body">

                {!! Form::open(['url' => 'task', 'method' => 'POST', 'enctype' => 'multipart/form-data','files' => true]) !!}
                {!! Form::token() !!}

                {!! Form::hidden('studie_route_id', $studieRoute->id) !!}


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
                    <div class="form-group">
                        {{ Form::file('my_document') }}
                        @if ($errors->any() && $errors->has('my_document'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('my_document') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{ Form::label('end_date', 'Due Date') }}
                    {{ Form::date('end_date', '', ['class' => 'form-control', 'placeholder' => \Carbon\Carbon::now()->format('Y-m-d') ]) }}
                    @if ($errors->any() && $errors->has('due_date'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('due_date') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::submit('Opslaan', ['class' => 'form-control']) !!}
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection
