@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Group page</li>
    </ul>

    <div class="container">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>

                <th>
                    <a href="{{ route('group.create') }}">
                        <button>+</button>
                    </a>
                </th>
            </tr>
            </thead>

            <tbody>
            @foreach($groups as $group)
                <tr>
                    <th scope="row">{{$group->id}}</th>
                    <td>{{$group->name }}</td>

                    <td><a href="{{ route('group.edit', $group) }}">
                            <button class="btn btn-primary">Edit</button>
                        </a></td>
                    <td>
                        {{ Form::open(array('url' => 'group/' .$group->id, 'group' => 'pull-right')) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::Submit('Delete', array('class' => 'btn btn-warning')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
