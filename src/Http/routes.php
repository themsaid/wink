<?php

// Blog Posts...
Route::get('/api/posts', 'PostsController@index');
Route::get('/api/posts/{id?}', 'PostsController@show');
Route::post('/api/posts/{id}', 'PostsController@store');
Route::delete('/api/posts/{id}', 'PostsController@delete');


// Blog Tags...
Route::get('/api/tags', 'TagsController@index');
Route::get('/api/tags/{id?}', 'TagsController@show');
Route::post('/api/tags/{id}', 'TagsController@store');
Route::delete('/api/tags/{id}', 'TagsController@delete');


// Blog Authors...
Route::get('/api/team', 'TeamController@index');
Route::get('/api/team/{id?}', 'TeamController@show');
Route::post('/api/team/{id}', 'TeamController@store');
Route::delete('/api/team/{id}', 'TeamController@delete');


// Blog Image Uploads
Route::post('/api/uploads', 'ImageUploadsController@upload');


// Blog Pages...
Route::get('/api/pages', 'PagesController@index');
Route::get('/api/pages/{id?}', 'PagesController@show');
Route::post('/api/pages/{id}', 'PagesController@store');
Route::delete('/api/pages/{id}', 'PagesController@delete');


// Logout Route...
Route::get('/logout', 'LoginController@logout')->name('logout');


// Catch-all Route...
Route::get('/{view?}', 'SPAViewController')->where('view', '(.*)');
