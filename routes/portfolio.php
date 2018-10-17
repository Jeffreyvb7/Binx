<?php

Route::get('/profile/{profile}/portfolio/create', 'PortfoliosController@create')->name('portfolio.create')->middleware('auth', 'permission:create portfolio');
Route::put('/profile/{profile}/portfolio/create', 'PortfoliosController@store')->name('portfolio.store')->middleware('auth', 'permission:create portfolio');

Route::get('/profile/{profile}/portfolio/{portfolio}', 'PortfoliosController@show')->name('portfolio.show');
Route::get('/profile/{profile}/portfolio/{portfolio}/addSubmission', 'PortfoliosController@getAddTo')->name('portfolio.addTo')->middleware('auth');
Route::get('/profile/{profile}/portfolio/{portfolio}/removeSubmission/{submission}', 'PortfoliosController@removeFrom')->name('portfolio.removeSubmission')->middleware('auth');
Route::post('/profile/{profile}/portfolio/{portfolio}/addSubmission', 'PortfoliosController@postAddTo')->middleware('auth');
Route::get('/profile/{profile}/portfolio/{portfolio}/delete', 'PortfoliosController@destroy')->name('portfolio.destroy')->middleware('auth');
Route::get('/profile/{profile}/portfolio/{portfolio}/edit', 'PortfoliosController@edit')->name('portfolio.edit')->middleware('auth');
Route::patch('/profile/{profile}/portfolio/{portfolio}/edit', 'PortfoliosController@update')->name('portfolio.update')->middleware('auth');