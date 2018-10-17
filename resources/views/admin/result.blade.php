@forelse($users as $user)
    <tr>
        <th scope="row">{{$user->id}}</th>
        <td>{{$user->first_name }}</td>
        <td>{{$user->last_name}}</td>
        <td title="{{ $user->birthdate }}">{{ $user->age }}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->phonenr}}</td>
        <td>
            @if($user->hasRole('student'))
                Student
            @elseif($user->hasRole('teacher'))
                Teacher
            @elseif($user->hasRole('admin'))
                Admin
            @endif
        </td>

        <td><a href="{{ route('admin.edit', $user) }}"><i class="fas fa-edit"></i></a></td>
        <td>
            {{ Form::open(array('url' => 'admin/' .$user->id, 'class' => 'pull-right')) }}
            {{ Form::hidden('_method', 'DELETE') }}
            <a href="#" onclick="$(this).closest('form').submit(); return false;"><i
                        class="fas fa-trash"></i></a>
            {{ Form::close() }}
        </td>
    </tr>

@empty
    <tr>
        <td colspan="8">No results</td>
    </tr>
@endforelse