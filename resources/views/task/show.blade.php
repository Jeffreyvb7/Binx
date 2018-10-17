@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li><a href="{{ route('studieroute.show', $studieRoute) }}">{{ $studieRoute->name }}</a></li>
        <li>{{ $task->name }}</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{ $task->name }}
                    @can('create studieroute')
                        <a href="{{ route('task.edit', [$studieRoute, $task]) }}">
                            <sup>edit</sup>
                        </a>
                    @endcan</div>
            </div>

            <div class="card-body">
                <div class="panel-body">
                    <p>{{ $task->description }}</p>
                    @if($task->document == !null)
                        <br>
                        File: {{$task->document}}
                        <a href="{{ route('task.download', [$studieRoute, $task]) }}">
                            download
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @role('student')
        <div class="center card">
            <div class="card-header">
                <div class="card-title">My Submissions
                    <a href="{{ route('task.submit', [$studieRoute, $task]) }}"><i class="fas fa-plus"></i></a>
                </div>
            </div>

            <div class="card-body">

                @if($task->getMySubmissions() != null)
                    <ul>
                        @forelse($task->getMySubmissions() as $submission)
                            @if($submission->description)
                                <p>{{ $submission->description }}</p>
                                @if($task->document == !null)
                                    <br>
                                    File: {{$task->document}}
                                    <a href="{{ route('task.download', [$studieRoute, $task]) }}">
                                        download
                                    </a>
                                @endif
                                <hr>
                            @else
                                <a href=""></a>
                            @endif
                        @empty
                            <span>You haven't submitted anything here!</span>
                        @endforelse
                    </ul>
                @else
                    <span>You haven't submitted anything here!</span>
                @endif

            </div>
        </div>
        @endrole

        @role('teacher')
        <div class="center card">
            <div class="card-header">
                <div class="card-title">Submissions</div>
            </div>

            <div class="card-body">

                @if($task->getMySubmissions() != null)
                    <ul>
                        @forelse($task->getAllSubmissions() as $submission)
                            @if($submission->description)
                                <p>{{ $submission->description }}</p>
                                <sub>{{ $submission->user->first_name.' '.$submission->user->last_name}}</sub>
                                {!! ($loop->last ? '' : '<hr>') !!}
                            @else
                                <a href=""></a>
                            @endif
                        @empty
                            <span>You haven't submitted anything here!</span>
                        @endforelse
                    </ul>
                @else
                    <span>You haven't submitted anything here!</span>
                @endif

            </div>
        </div>
        @endrole
    </div>
@endsection