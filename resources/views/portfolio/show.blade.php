@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.index') }}">Profile</a></li>
        <li>{{ $portfolio->name }}</li>
    </ul>

    <div class="container">
        <h1 style="display: inline-block;">{{ $portfolio->name }}</h1>
        @if(Auth::check() && Auth::user()->id === $portfolio->user->id)
            <a href="{{ route('portfolio.addTo', [$portfolio->user->profile, $portfolio]) }}"><i class="fas fa-plus"></i></a>
            <a href="{{ route('portfolio.edit', [$portfolio->user->profile, $portfolio]) }}"><i class="fas fa-edit"></i></a><br>
        @endif
        <p>{{ $portfolio->description }}</p>
        <hr>
        @forelse($portfolio->submissions as $submission)
            <div class="card">
                <div class="card-header">
                    @if(Auth::check())
                        <a href="{{ route('task.show', [$submission->task->studieRoute, $submission->task]) }}"><b>{{ $submission->task->name }}</b></a>
                        @if(Auth::user()->id === $portfolio->user->id)
                            <a href="{{ route('portfolio.removeSubmission', [$submission->user->profile, $portfolio, $submission]) }}"><i
                                        class="fas fa-times"></i></a>
                        @endif
                    @else
                        <b>{{ $submission->task->name }}</b>
                    @endif
                </div>

                <div class="card-body">
                    <div class="panel-body">
                        {{ $submission->description }}
                    </div>
                </div>
            </div>
            <br>
        @empty
            <span>There are no submissions in this portfolio!</span>
        @endforelse
    </div>
@endsection