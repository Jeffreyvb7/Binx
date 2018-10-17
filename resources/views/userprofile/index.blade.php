@extends('layout.app')

@section('content')
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ URL::to('books/'.$user->id }}">{{ $user->first_name }}</a></td>
                <td> {{$user->last_name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>