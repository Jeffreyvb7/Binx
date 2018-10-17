<?php

Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/new', 'HomeController@newTest')->middleware('auth')->name('newTest');

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/studieroute/search', 'StudieRoutesController@postSearch')->middleware('auth')->name('studieroute.search');

Route::post('/admin/search', 'Admin\UserController@postSearch')->middleware('auth')->name('admin.search');

Route::resource('/studieroute', 'StudieRoutesController')->except('destroy')->middleware('auth');
Route::delete('/studieroute/{studieroute}/delete', 'StudieRoutesController@destroy')->name('studieroute.destroy')->middleware('auth');

Route::resource('/profile', 'ProfileController')->middleware('auth');

Route::resource('/task', 'TasksController')->except(['show', 'create', 'edit', 'update'])->middleware('auth');
Route::get('/studieroute/{studieroute}/task/create', 'TasksController@create')->name('task.create')->middleware('auth', 'permission:create task');
Route::get('/studieroute/{studieroute}/task/{task}', 'TasksController@show')->name('task.show')->middleware('auth');
Route::get('/studieroute/{studieroute}/task/{task}/edit', 'TasksController@edit')->name('task.edit')->middleware('auth', 'permission:edit task');
Route::patch('/studieroute/{studieroute}/task/{task}', 'TasksController@update')->name('task.update')->middleware('auth');
Route::get('/studieroute/{studieroute}/task/{task}/download', 'TasksController@getDownload')->name('task.download')->middleware('auth');

Route::get('/studieroute/{studieroute}/task/{task}/submit', 'SubmissionsController@getSubmit')->name('task.submit')->middleware('auth', 'permission:can submit');
Route::post('/studieroute/{studieroute}/task/{task}/submit', 'SubmissionsController@postSubmit')->name('task.store')->middleware('auth', 'permission:can submit');

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('/admin', 'Admin\UserController');

    Route::resource('/group', 'GroupController');

});

Route::get('test', function() {
    return view('test');
});