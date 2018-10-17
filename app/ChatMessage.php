<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $appends = ['senderName'];

    /**
     * Chatmessage belongs to user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Chatmessages belongs to chatroom relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatroom()
    {
        return $this->belongsTo(Chatroom::class);
    }

    /**
     * Custom senderName attribute
     *
     * @return mixed
     */
    public function getSenderNameAttribute()
    {
        return $this->user->fullName;
    }
}
