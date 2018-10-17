@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('studieroute.index') }}">Studieroutes</a></li>
        <li>{{ $studieRoute->name }}</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{ $studieRoute->name }}
                    @can('create studieroute')
                        <a href="{{ route('studieroute.edit', $studieRoute) }}">
                            <sup>edit</sup>
                        </a>
                    @endcan
                </div>
                <div class="card-tools">
                    @can('delete studieroute')
                        {{--<a href="#" onclick="binx.modal('newChatroomModal').open(event);"><i class="fas fa-trash"></i></a>--}}
                        {{ Form::open(['method' => 'DELETE', 'route' => ['studieroute.destroy', $studieRoute->key]]) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        <i onclick="$(this).closest('form').submit(); return false;" class="fas fa-trash"></i>
                        {{ Form::close() }}
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="panel-body">
                    <p>{{ $studieRoute->description }}</p>
                </div>
            </div>
        </div>

        <div class="center card md-10 sm-12">
            <div class="card-header">
                <div class="card-title">Tasks</div>
                <div class="card-tools">
                    @can('create task')
                        <a href="{{ route('task.create', $studieRoute) }}"><i class="fas fa-plus"></i></a>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                @forelse($studieRoute->tasks as $task)
                    <div class="panel-body">
                        <h3>
                            <b>
                                <a href="{{ route('task.show', [$studieRoute, $task]) }}">
                                    {{ $task->name }}
                                </a>
                            </b>
                        </h3>

                        <p>{{ $task->description }}</p>
                        {!! ($loop->last ? '' : '<hr>') !!}

                    </div>
                @empty
                    No tasks available
                @endforelse

            </div>
        </div>
    </div>


@endsection
