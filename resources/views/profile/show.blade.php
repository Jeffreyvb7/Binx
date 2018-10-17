@extends('layouts.new')

@section('content')
    <div class="container">
        <h1>{{ $profile->user->fullName }}</h1>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col"> Description</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td> {{ $profile->description }}</td>
                <td> {{ $profile->user->fullName }}</td>
            </tr>
            </tbody>
        </table>

        <h2 style="display: inline-block;">Portfolios</h2>
        @if(Auth::check() && Auth::user()->id === $profile->user->id)
            <a href="{{ route('portfolio.create', $profile) }}"><i class="fas fa-plus"></i></a>
        @endif

        @if(count($profile->user->portfolios) > 0)
            <table class="table table-striped table-hover">
                <thead>
                @if(Auth::check() && Auth::user()->id === $profile->user->id)
                    <th width="90%">Name</th>
                    <th colspan="3">Actions</th>
                @else
                    <th>Name</th>
                @endif

                </thead>
                <tbody>
                @foreach($profile->user->portfolios as $portfolio)
                    <tr>
                        <td>
                            <a href="{{ route('portfolio.show', [$profile, $portfolio]) }}">{{ $portfolio->name }}</a>
                        </td>
                        @if(Auth::check() && Auth::user()->id === $profile->user->id)
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
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <span>This user doesn't have any portfolio's</span>
        @endif
    </div>
@endsection