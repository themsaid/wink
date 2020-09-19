<?php

use Wink\Http\Controllers\ImageUploadsController;
use Wink\Http\Controllers\LoginController;
use Wink\Http\Controllers\PagesController;
use Wink\Http\Controllers\PostsController;
use Wink\Http\Controllers\SPAViewController;
use Wink\Http\Controllers\TagsController;
use Wink\Http\Controllers\TeamController;

// Blog Posts...
Route::get('/api/posts', [PostsController::class, 'index'])->name('posts.index');
Route::get('/api/posts/{id?}', [PostsController::class, 'show'])->name('posts.show');
Route::post('/api/posts/{id}', [PostsController::class, 'store'])->name('posts.store');
Route::delete('/api/posts/{id}', [PostsController::class, 'delete'])->name('posts.delete');

// Blog Tags...
Route::get('/api/tags', [TagsController::class, 'index'])->name('tags.index');
Route::get('/api/tags/{id?}', [TagsController::class, 'show'])->name('tags.show');
Route::post('/api/tags/{id}', [TagsController::class, 'store'])->name('tags.store');
Route::delete('/api/tags/{id}', [TagsController::class, 'delete'])->name('tags.delete');

// Blog Authors...
Route::get('/api/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/api/team/{id?}', [TeamController::class, 'show'])->name('team.show');
Route::post('/api/team/{id}', [TeamController::class, 'store'])->name('team.store');
Route::delete('/api/team/{id}', [TeamController::class, 'delete'])->name('team.delete');

// Blog Image Uploads
Route::post('/api/uploads', [ImageUploadsController::class, 'upload'])->name('images.store');

// Blog Pages...
Route::get('/api/pages', [PagesController::class, 'index'])->name('pages.index');
Route::get('/api/pages/{id?}', [PagesController::class, 'show'])->name('pages.show');
Route::post('/api/pages/{id}', [PagesController::class, 'store'])->name('pages.store');
Route::delete('/api/pages/{id}', [PagesController::class, 'delete'])->name('pages.delete');

// Logout Route...
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Catch-all Route...
Route::get('/{view?}', SPAViewController::class)->name('spa')->where('view', '(.*)');
