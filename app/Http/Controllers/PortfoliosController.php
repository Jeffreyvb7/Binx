<?php

namespace App\Http\Controllers;

use App\Http\Requests\Portfolio\CreateRequest;
use App\Portfolio;
use App\Profile;
use App\StudieRoute;
use App\Submission;
use App\Task;
use Auth;
use Illuminate\Http\Request;

class PortfoliosController extends Controller
{
    public function show(Profile $profile, Portfolio $portfolio)
    {
        if($portfolio->user->id !== $profile->user->id) return abort(404);

        return view('portfolio.show', compact('portfolio'));
    }

    public function getAddTo(Request $request, Profile $profile, Portfolio $portfolio)
    {
        $notIn = $portfolio->submissions()->pluck('submissions.id');

        $tasks = [];
        foreach($profile->user->submissions as $submission) {
            $task = $submission->task;

            if(isset($tasks[$task->studieRoute->id])) {
                if(!in_array($task, $tasks[$task->studieRoute->id])) $tasks[$task->studieRoute->id][] = $task;
            } else {
                $tasks[$task->studieRoute->id] = [];
                $tasks[$task->studieRoute->id][] = $task;
            }
        }

        $studieRoute = [];
        if($request->has('studieRoute')) {
            $studieRoute = array_filter(array_values($request->get('studieRoute')), function($val) {
                return $val !== 0;
            });
        }

        $task = [];
        if($request->has('task')) {
            $task = array_filter(array_values($request->get('task')), function($val) {
                return $val !== 0;
            });
        }

        $submissions = (array) Auth::user()->submissions()->with(['task'])->whereNotIn('submissions.id', $notIn)->get();
        $submissions = $submissions[array_keys($submissions)[0]];

        if(!empty($studieRoute)) {
            $submissions = array_filter($submissions, function($submission) use ($studieRoute) {
                return in_array($submission->task->studieRoute->id, $studieRoute);
            });
        }

        if(!empty($task)) {
            $submissions = array_filter($submissions, function($submission) use ($task) {
                return in_array($submission->task->id, $task);
            });
        }

        return view('portfolio.addTo', compact('submissions', 'portfolio', 'tasks'));
    }

    public function postAddTo(Request $request, Profile $profile, Portfolio $portfolio)
    {
        $toAdd = [];

        foreach (array_values($request->get('submissions')) as $id) {
            if($submission = Submission::find($id)) {
                if($submission->user->id === Auth::user()->id) {
                    if(!$portfolio->submissions->contains($submission->id)) $toAdd[] = $submission;
                }
            }
        }

        if($portfolio->submissions()->saveMany($toAdd)) {
            return redirect()->route('portfolio.show', [$profile, $portfolio]);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Profile $profile, Portfolio $portfolio)
    {
        if(Auth::user()->id === $profile->user->id) {
            try {
                $portfolio->submissions()->sync([]);

                if($portfolio->delete()) {
                    return redirect()->back();
                } else {
                    return redirect()->back();
                }
            } catch(\Exception $e) {
                return redirect()->back();
            }
        } return abort(403);
    }

    public function create(Profile $profile)
    {
        if($profile->user->id === Auth::user()->id) {
            return view('portfolio.create', compact('profile'));
        } else {
            return redirect()->back();
        }
    }

    public function store(CreateRequest $request, Profile $profile)
    {
        $portfolio = new Portfolio($request->only('name', 'description'));
        if($profile->user->portfolios()->save($portfolio)) {
            return redirect()->route('portfolio.show', [$profile, $portfolio]);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function removeFrom(Profile $profile, Portfolio $portfolio, Submission $submission)
    {
        if(Auth::user()->id === $profile->user->id) {
            if($portfolio->user->id === $profile->user->id) {
                if($portfolio->submissions->contains($submission->id)) {
                    $portfolio->submissions()->detach($submission);
                }
            }
        }
        return redirect()->back();
    }

    public function edit(Profile $profile, Portfolio $portfolio)
    {
        if(Auth::check() && Auth::user()->id === $profile->user->id && $portfolio->user->id === Auth::user()->id) {
            return view('portfolio.edit', compact('portfolio'));
        } else {
            return redirect()->back();
        }
    }

    public function update(CreateRequest $request, Profile $profile, Portfolio $portfolio)
    {
        if($portfolio->update($request->only('name', 'description'))) {
            return redirect()->route('portfolio.show', [$profile, $portfolio]);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
