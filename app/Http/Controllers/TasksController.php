<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudieroute;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use App\Task;
use App\StudieRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all()->sortBy('name');

        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StudieRoute $studieRoute)
    {
        return view('task.create', compact('studieRoute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTask $request)
    {
        if ($studieRoute = StudieRoute::find($request->get('studie_route_id'))) {
            $task = new Task(array_merge($request->only('name', 'description', 'end_date'), ['studie_route_id' => $studieRoute->id]));

            if ($request->hasFile('my_document')) {
                $filenameWithExt = $request->file('my_document')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('my_document')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('my_document')->storeAs('public/documents/'.$studieRoute->id.'/', $fileNameToStore);

                $task->document = $fileNameToStore;
            }
            $task->save();

            return redirect()->route('task.show', [$studieRoute->key, $task]);
        } else return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param StudieRoute $studieRoute
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(StudieRoute $studieRoute, Task $task)
    {
        if ($task->studieRoute->id == $studieRoute->id) {
            return view('task.show', compact('task', 'studieRoute'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StudieRoute $studieRoute
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(StudieRoute $studieRoute, Task $task)
    {
        return view('task.edit', compact('task', 'studieRoute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(StudieRoute $studieRoute, EditTask $request, Task $task)
    {
        $oldFileNameToStore = $task->document;

        if($request->hasFile('my_document')){
            $filenameWithExt = $request->file('my_document')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('my_document')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('my_document')->storeAs('public/documents/'.$studieRoute->id.'/', $fileNameToStore);

            if ($fileNameToStore !== $oldFileNameToStore && !is_null($oldFileNameToStore)){
                unlink(storage_path('app/public/documents/'.$studieRoute->id.'/'.$oldFileNameToStore));
            }

            $task->document = $fileNameToStore;
        }

        $task->update($request->only('name', 'description', 'end_date', 'document'));

        return redirect()->route('task.show', [$studieRoute->key, $task]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function getDownload(StudieRoute $studieRoute, Task $task)
    {
        //PDF file is stored under project/public/download/info.pdf
        $file = storage_path() . '/app/public/documents/'.$studieRoute->id.'/'.$task->document;

        return response()->download($file, $task->document);
    }
}
