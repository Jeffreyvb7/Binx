@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.index') }}">Profile</a></li>
        <li><a href="{{ route('portfolio.show', [$portfolio->user, $portfolio]) }}">{{ $portfolio->name }}</a></li>
        <li>Add Submission</li>
    </ul>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Narrow down submissions
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            {!! Form::open(['URL' => route('portfolio.addTo', [$portfolio->user->profile, $portfolio]), 'method' => 'GET']) !!}

                            <table>
                                <tbody>
                                @foreach($tasks as $studieRouteId => $sTasks)
                                    <tr>
                                        <td>
                                            <label>
                                                {!! Form::checkbox('studieRoute[]', \App\StudieRoute::find($studieRouteId)->id, false) !!}
                                                {{ \App\StudieRoute::find($studieRouteId)->name }}
                                            </label>
                                        </td>
                                        <td>
                                            @foreach($sTasks as $task)
                                                <div class="form-group">
                                                    <label>
                                                        {!! Form::checkbox('task[]', $task->id, false) !!}
                                                        {{ $task->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="form-group">
                                {!! Form::submit('Search', ['class' => 'form-control']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <br>

                <div class="card">
                    <div class="card-header">
                        Results
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            @if(count($submissions) > 0)
                                {!! Form::open(['URL' => route('portfolio.addTo', [$portfolio->user->profile, $portfolio]), 'method' => 'POST']) !!}
                                @foreach($submissions as $submission)
                                    <div class="form-group">
                                        <label>
                                            {!! Form::checkbox('submissions[]', $submission->id, false) !!}
                                            {{ $submission->description }}
                                        </label>

                                    </div>
                                @endforeach
                                <div class="form-group">
                                    {!! Form::submit('Add', ['class' => 'btn']) !!}
                                </div>
                                {!! Form::close() !!}
                            @else
                                <span>You don't have any submissions to add to "{{ $portfolio->name }}"</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection