<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sumission\CreateRequest;
use App\StudieRoute;
use App\Submission;
use App\Task;
use Auth;

class SubmissionsController extends Controller
{
    public function getSubmit(StudieRoute $studieRoute, Task $task)
    {
        return view('submission.create', compact('studieRoute', 'task'));
    }

    public function postSubmit(CreateRequest $request, StudieRoute $studieRoute, Task $task)
    {
        $data = $request->only('description');
        $data['user_id'] = Auth::user()->id;
        $data['task_id'] = $task->id;

        $submission = new Submission($data);

        if ($submission->save()) {
            return redirect()->route('task.show', [$studieRoute, $task]);
        } else {
            return 'ER';
        }
    }
}