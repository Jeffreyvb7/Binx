@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li>Dashboard</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Dashboard</div>
                {{--<div class="card-tools">--}}
                    {{--<a href=""><i class="fas fa-edit"></i></a>--}}
                    {{--<a href=""><i class="fas fa-times"></i></a>--}}
                {{--</div>--}}
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
                <br>
                @role('student', 'web')
                I am a student!
                @else
                    I am not the student...
                    @endrole
                    <br>
                    @role('teacher', 'web')
                    I am a teacher!
                    @else
                        I am not the teacher...
                        @endrole
                        <br>
                        @role('admin', 'web')
                        I am a admin!
                        @else
                            I am not the admin...
                            @endrole

            </div>
        </div>
    </div>
@endsection
