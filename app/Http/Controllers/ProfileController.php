<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfilePost;
use App\Http\Requests\UpdateProfilePost;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'index']);
    }

    public function index()
    {
        $profile = Auth::user()->profile;
        return view('profile.index', compact('profile'));
    }

    public function create()
    {
        return view('profile.create');
    }

    public function store(StoreProfilePost $request)
    {
        $validated = $request->validated();

        $profile = new Profile();
        $profile->user_id = Auth::user()->id;
        $profile->description = $request['description'];
        $profile->save();

        return redirect()->action('ProfileController@index')->with('correct', 'Profile saved');
    }

    public function show(Profile $profile)
    {
        return view('profile.show', compact('profile'));
    }

    public function edit(Profile $profile)
    {
        return view('profile.edit', compact('profile'));
    }

    public function update(UpdateProfilePost $request, Profile $profile)
    {
        $validated = $request->validated();

        $profile->description = $request['description'];
        $profile->save();

        $user = $profile->user;
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        $user->save();

        return redirect()->action('ProfileController@index')->with('correct', 'Profile updated');
    }

        public function destroy(Profile $profile)
        {
            $profile->delete();

            return redirect()->action('ProfileController@index')->with('correct', 'Profile deleted');
        }

}