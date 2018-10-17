@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li><a href="{{ route('studieroute.show', $studieRoute) }}">{{ $studieRoute->name }}</a></li>
        <li>Editing Task: {{ $task->name }}</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Editing Task: {{ $task->name }}
                </div>
            </div>

            <div class="card-body">

                {!! Form::open(['url' => route('task.update', [$studieRoute, $task]), 'method' => 'PATCH', 'enctype' => 'multipart/form-data','files' => true]) !!}
                {!! Form::token() !!}


                <div class="form-group">
                    {{ Form::label('name', 'Title') }}
                    {!! Form::text('name', $task->name, ['class' => 'form-control']) !!}
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
                    {!! Form::textarea('description', $task->description , ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('description'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('description') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        Current document:
                        @if($task->document == !null)
                            {{ $task->document }} <br>
                        @else
                            none <br>
                        @endif
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
                    {{ Form::date('end_date', $task->end_date, ['class' => 'form-control', 'placeholder' => \Carbon\Carbon::now()->format('Y-m-d') ]) }}
                    @if ($errors->any() && $errors->has('end_date'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('end_date') as $error)
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
