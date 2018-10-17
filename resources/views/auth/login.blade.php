@extends('layouts.fullwidth')

@section('content')
    <div class="container">
        <div class="center card xl-5 lg-6 md-8 sm-10 xs-12">
            <div class="card-header">
                <div class="card-title">{{__('Login')}}</div>
            </div>

            <div class="card-body">
                <div class="panel-body">

                    {!! Form::model(['route' => route('login') ,'method' => 'POST']) !!}
                    {!! Form::token() !!}


                    <div class="form-group">
                        {{ Form::label('email', __('E-Mail Address')) }}
                        {!! Form::email('email', old('email'), ['class' => 'form-control']) !!}
                        @if ($errors->any() && $errors->has('email'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('email') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        {{ Form::label('password', __('Password')) }}
                        {!! Form::password('password', old('password'), ['class' => 'form-control']) !!}
                        @if ($errors->any() && $errors->has('password'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('password') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="card-tools">
                        {!! Form::submit('Login', ['class' => 'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
@endsection

