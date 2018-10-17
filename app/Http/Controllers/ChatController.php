<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatroomStoreRequest;

use \App\Chatroom;
use App\User;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function retrieve()
    {
        return response()->json(Auth::user()->chatrooms);
    }

    /**
     * Returns chat AngularJS app
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('chat.overview');
    }

    /**
     * Returns AngularJS apps index template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_temp()
    {
        return view('chat.templates.index');
    }

    /**
     * Returns AngularJS apps chat template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chat_temp()
    {
        return view('chat.templates.chat');
    }

    /**
     * Return initial chat information
     *
     * @param Chatroom $chat
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Chatroom $chat)
    {
        return response()->json(Chatroom::with(['messages', 'users'])->find($chat->id));
    }

    /**
     * Return socket authentication token
     *
     * @return string
     */
    public function getToken()
    {
        return Auth::user()->socket_auth_token;
    }

    public function filterUsers(Request $request)
    {
        if (!$request->has('search')) abort(400);

        $q = '%' . $request->get('search') . '%';
        $e = ($request->has('exclude') ? (is_array($request->get('exclude')) ? $request->get('exclude') : []) : []);

        return response()->json(User::whereNotIn('id', $e)
            ->where(function($query) use ($q) {
                return $query->where('first_name', 'like', $q)
                    ->orWhere('last_name', 'like', $q)
                    ->orWhere('email', 'like',  $q)
                    ->orWhere('id', 'like', $q);
            })->get());
    }

    /**
     * Storing new chatrooms
     *
     * @param ChatroomStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(ChatroomStoreRequest $request)
    {
        $chat = new Chatroom($request->only('name'));
        if ($chat->save()) {
            Auth::user()->chatrooms()->save($chat, ['admin' => true]);
            foreach ($request->get('users') as $userId) {
                if ($user = User::find($userId)) {
                    $user->chatrooms()->save($chat);
                }
            }

            return response()->json($chat);
        } else {
            return response()->setStatusCode(500);
        }
    }

    public function removeUserFromChat(Chatroom $chat, User $user)
    {
        $me = $chat->users()->where('users.id', Auth::user()->id)->first();

        $admins = $chat->admins()->count();

        if($admins <= 1) return response()->json((object) ['message' => 'Too few admins'])->setStatusCode(403);
        if ($me && $me->pivot->admin) return $chat->users()->detach($user);
        return response()->json((object) ['message' => 'Unknown error'])->setStatusCode(500);
    }

    public function updateUserInChat(Request $request, Chatroom $chat, User $user)
    {
        if ($request->has('admin')) {
            if ($me = $chat->users()->where('users.id', Auth::user()->id)->first()) {
                if ($me->pivot->admin) {
                    if($me->id === $user->id && $chat->admins()->count()-1 <= 0) return response()->json((object) ['message' => 'Too few admins'])->setStatusCode(403);

                    return $chat->users()->updateExistingPivot($user->id, ['admin' => $request->get('admin')]);
                } else return response()->json((object) ['message' => 'Insufficient rights'])->setStatusCode(403);
            }
        }
        return response()->json((object) ['message' => 'Invalid request'])->setStatusCode(400);
    }

    public function getUserInChat(Chatroom $chat, User $user)
    {
        if ($me = $chat->users()->where('users.id', Auth::user()->id)->first()) {
            if($chat->users->contains($me->id)) {
                return response()->json($chat->users()->where('users.id', $user->id)->firstOrFail());
            }
        }

        return abort(403);
    }

    public function addUsersToChat(Request $request, Chatroom $chat)
    {
        if($request->has('users') && is_array($request->get('users'))) {
            if ($me = $chat->users()->where('users.id', Auth::user()->id)->first()) {
                if($me->pivot->admin) {
                    $added = [];
                    foreach ($request->get('users') as $id) {
                        if(!$chat->users->contains($id)) {
                            $user = User::find($id);

                            $chat->users()->save($user);

                            $added[] = $id;
                        }
                    }

                    return response()->json($added);
                } else return abort(403);
            } else return abort(403);
        } else return abort(400);
    }

    public function updateChat(Request $request, Chatroom $chat)
    {
        $request->validate([
            'name' => 'sometimes|min:4|max:255'
        ], [
            'name.min' => 'Name should be longer than 4 characters',
            'name.max' => 'Name should be shorter than 255 characters'
        ]);

        if($request->has('name')) {
            $chat->name = $request->get('name');
        }

        if(!$chat->save()) {
            return response()->setStatusCode(500);
        }
    }
}
