<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'end_date', 'studie_route_id', 'document'];

    public function studieRoute()
    {
        return $this->belongsTo(StudieRoute::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function getMySubmissions()
    {
        return (Auth::check() ? $this->submissions()->where('user_id', Auth::user()->id)->orderBy('updated_at', 'DESC')->get() : null);
    }

    public function getAllSubmissions()
    {
        return (Auth::check() ? $this->submissions()->orderBy('updated_at', 'DESC')->get() : null);
    }
}
