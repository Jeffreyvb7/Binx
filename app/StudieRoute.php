<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StudieRoute extends Model
{
    protected $fillable = ['name', 'key', 'due_date', 'description'];

    protected $table = 'studieroutes';

    public function tasks()
    {
       return $this->hasMany(Task::class);
    }

    /**
     *  Get the route key for the model
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'key';
    }

    public function dueDate()
    {
        $due_date = Carbon::parse($this->due_date);
        return $due_date->diffInDays(Carbon::now());
    }
}
