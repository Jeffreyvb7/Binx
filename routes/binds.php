<?php

use App\Chatroom;
use App\Portfolio;
use App\StudieRoute;
use App\Submission;
use App\User;

Route::model('studieroute', StudieRoute::class);
Route::model('chat', Chatroom::class);
Route::model('user', User::class);
Route::model('portfolio', Portfolio::class);
Route::model('admin', User::class);
Route::model('submission', Submission::class);