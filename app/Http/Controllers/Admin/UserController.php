<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserPost;
use App\Http\Requests\UserUpdatePost;
use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }


    public function postSearch(Request $request)
    {
        if($request->has('query')) {
            $users = User::leftJoin('model_has_roles', 'model_id', 'users.id')
                ->leftJoin('roles', 'role_id', 'roles.id')
                ->where('model_has_roles.model_id', 'users.id')
                ->orWhere('first_name', 'LIKE', '%' . $request->get('query') .  '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->get('query') .  '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('query') .  '%')
                ->orWhere('roles.name', 'LIKE', '%' . $request->get('query') .  '%')
                ->select('users.*');

            $users = $users->get();
            return view('admin.result', compact('users'));
        } else {
            return abort(400);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPost $request)
    {
        $user = new User();
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->birthdate = request('birthdate');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->phonenr = request('phonenr');

        if($role = Role::find($request->get('role'))) {
//            dd($role);
            $user->save();

            $user->syncRoles([$role->name]);
            return redirect()->action('Admin\UserController@index')->with('Succes', 'Gebruiker toegevoegd');
        } else {
            return redirect()->back()->withErrors(['role' => ['Invalid role']]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdatePost $request, User $user)
    {
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->birthdate = request('birthdate');
        $user->email = request('email');
        $user->phonenr = request('phonenr');

        if(!empty(request('password'))) {
            $user->password = Hash::make(request('password'));
        }


        if($role = Role::find($request->get('role'))) {
            $user->save();

            $user->syncRoles([$role->name]);
            return redirect()->action('Admin\UserController@index')->with('Succes', 'Gebruikeer Gewijzigd');
        } else {
            return redirect()->back()->withErrors(['role' => ['Invalid role']]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->action('Admin\UserController@index')->with('correct', 'Gebruiker Verwijderd');
    }
}
