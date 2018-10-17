@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li><a href="{{ route('studieroute.show', $studieRoute) }}">{{ $studieRoute->name }}</a></li>
        <li>Editing {{ $studieRoute->name }}</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Studieroutes
                </div>
            </div>

            <div class="card-body">

                {!! Form::open(['url' => 'studieroute/'.$studieRoute->key, 'method' => 'PATCH']) !!}
                {!! Form::token() !!}


                <div class="form-group">
                    {{ Form::label('name', 'Title') }}
                    {!! Form::text('name', $studieRoute->name, ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    {{ Form::label('key', 'Key') }}
                    {!! Form::text('key', $studieRoute->key, ['class' => 'form-control']) !!}
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
                    {!! Form::textarea('description', $studieRoute->description , ['class' => 'form-control']) !!}
                    @if ($errors->any() && $errors->has('description'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('description') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif


                    {{ Form::label('due_date', 'Due Date') }}
                    {{ Form::date('due_date', $studieRoute->due_date, ['class' => 'form-control', 'placeholder' => \Carbon\Carbon::now()->format('Y-m-d') ]) }}
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