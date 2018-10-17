<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $appends = ['latest'];


    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Chatroom m2m User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('admin');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class)->withPivot('admin')->wherePivot('admin', 1);
    }

    /**
     * Chatroom has messages relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    /**
     * Custom latest attribute
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getLatestAttribute()
    {
        return $this->hasMany(ChatMessage::class)->latest()->take(1)->first();
    }

    /**
     * Check if user is added in chatroom
     *
     * @param User $user
     * @return boolean
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user->id);
    }
}
