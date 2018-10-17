<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudieroute;
use App\Http\Requests\EditStudieroute;
use App\StudieRoute;
use Illuminate\Http\Request;

class StudieRoutesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create studieroute', ['only' => ['create']]);
        $this->middleware('permission:edit studieroute',   ['only' => ['edit']]);
//        $this->middleware('permission:delete studieroute',   ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('studieroute.search');
    }

    public function postSearch(Request $request)
    {
        if($request->has('query')) {
            $studieRoutes = StudieRoute::where('name', 'LIKE', '%' . $request->get('query') .  '%')->get();
            return view('studieroute.searchresults', compact('studieRoutes'));
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
        return view('studieroute.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudieroute $request)
    {
        $studieroute = new StudieRoute($request->only('name', 'key', 'description', 'due_date'));
        $studieroute->save();

        return redirect()->route('studieroute.show', $studieroute);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudieRoute  $studieRoute
     * @return \Illuminate\Http\Response
     */
    public function show(StudieRoute $studieRoute)
    {
//        $studieRoute = StudieRoute::find(1);
        return view('studieroute.show', compact('studieRoute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudieRoute  $studieRoute
     * @return \Illuminate\Http\Response
     */
    public function edit(StudieRoute $studieRoute)
    {
        return view('studieroute.edit', compact('studieRoute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudieRoute  $studieRoute
     * @return \Illuminate\Http\Response
     */
    public function update(EditStudieroute $request, StudieRoute $studieRoute)
    {
        $studieRoute->update($request->only('name', 'key', 'description', 'due_date'));
//        $studieRoute->save();

        return redirect()->route('studieroute.show', $studieRoute);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudieRoute  $studieRoute
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudieRoute $studieRoute)
    {
//        $studieRoute->tasks()->delete();
        $studieRoute->delete();
        return redirect()->action('StudieRoutesController@index')->with('correct', 'Studieroute deleted');
    }
}
