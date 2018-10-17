<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::created(function($model){
            if(!$model->profile) {
                $profile = new Profile(['user_id' => $model->id, 'description' => 'Hello my name is ' . $model->first_name]);
                $model->profile()->save($profile);
            }
        });
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $appends = ['fullName', 'age'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'socket_auth_token'
    ];

    public function group()
    {
        return $this->hasOne(Group::class);
    }

    /**
     * User m2m Chatroom relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function chatrooms()
    {
        return $this->belongsToMany(Chatroom::class);
    }

    /**
     * User profile relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * User submission relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * User portfolio relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Custom fullName attribute
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Custom age attribute
     *
     * @return string
     */
    public function getAgeAttribute()
    {
        return (new Carbon($this->birthdate))->diff(Carbon::now())->format('%y');
    }
}
