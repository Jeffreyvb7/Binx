@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Your profile</li>
    </ul>

    <div class="container">
        <h1>{{ $profile->user->fullName }}</h1>
        <form>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('first_name', 'First name'); !!}
                    {!! Form::text('first_name', $profile->user->first_name, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('last_name', 'Last name'); !!}
                    {!! Form::text('last_name', $profile->user->last_name, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('birthdate', 'Age'); !!}
                    {!! Form::date('birthdate', $profile->user->birthdate, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('email', 'Email'); !!}
                    {!! Form::text('email', $profile->user->email, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('phonenr', 'Phonenr'); !!}
                    {!! Form::text('phonenr', $profile->user->phonenr, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <div class="field">
                    {!! Form::label('description', 'Bio'); !!}
                    {!! Form::text('description', $profile->description, ['class' => 'form-control', 'disabled' => 'disabled']); !!}
                </div>
            </div>

            <div class="form-group">
                <a href="{{route('profile.edit', $profile)}}" class="btn btn-primary">Edit</a>
            </div>
        </form>

        @role('student')
            <h2 style="display: inline-block;">Portfolios</h2>
            <a href="{{ route('portfolio.create', $profile) }}"><i class="fas fa-plus"></i></a>
            @if(count($profile->user->portfolios) > 0)
                <table class="table table-striped table-hover">
                    <thead>
                    <th width="90%">Name</th>
                    <th colspan="3">Actions</th>
                    </thead>
                    <tbody>
                    @foreach($profile->user->portfolios as $portfolio)
                        <tr>
                            <td>
                                <a href="{{ route('portfolio.show', [$profile, $portfolio]) }}">{{ $portfolio->name }}</a>
                            </td>
                            <td>
                                <a href="{{ route('portfolio.addTo', [$profile, $portfolio]) }}"><i
                                            class="fas fa-plus"></i></a>
                            </td>
                            <td>
                                <a href="{{ route('portfolio.edit', [$profile, $portfolio]) }}"><i
                                            class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <a href="{{ route('portfolio.destroy', [$profile, $portfolio]) }}"><i
                                            class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <span>This user doesn't have any portfolio's</span>
            @endif
        @endrole
    </div>

@endsection