@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Studieroutes</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Studieroutes</div>
                <div class="card-tools">
                    @can('create studieroute')
                        <a href="{{ route('studieroute.create') }}"><i class="fas fa-plus"></i></a>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                @foreach($studieRoutes as $studieRoute)
                    <hr>

                    <div class="panel-body">
                        <h3>
                            <b>
                                <a href="{{ route('studieroute.show', $studieRoute) }}">
                                    {{ $studieRoute->name }}
                                </a>
                            </b>
                        </h3>

                        <p>{{ $studieRoute->description }}</p>

                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection


