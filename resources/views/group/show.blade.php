@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li><a href="{{ route('group.index') }}">Group page</a></li>
        <li>{{ $group->name }}</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{ $group->name }}</div>
            </div>

            <div class="card-body">

                @forelse($group->user as $user)
                    <hr>

                    <div class="panel-body">
                        <h3>
                            <b>
                                <a href="{{ route('studieroute.show', $studieRoute) }}">
                                    {{ $user->first_name.' '.$user->last_name }}
                                </a>
                            </b>
                        </h3>
                    </div>
                @empty
                    No students assigned
                @endforelse
            </div>
        </div>
    </div>
@endsection