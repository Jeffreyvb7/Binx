<?php

Route::get('/', 'ChatController@index')->name('chat');

Route::get('retrieve', 'ChatController@retrieve');
Route::get('token', 'ChatController@getToken');
Route::post('/store', 'ChatController@store');

Route::post('filterusers', 'ChatController@filterUsers');

// Keep these last
Route::get('{chat}', 'ChatController@chat');
Route::delete('{chat}/user/{user}', 'ChatController@removeUserFromChat');
Route::post('{chat}/user/{user}', 'ChatController@updateUserInChat');
Route::post('{chat}', 'ChatController@updateChat');
Route::put('{chat}', 'ChatController@addUsersToChat');
Route::get('{chat}/user/{user}', 'ChatController@getUserInChat');