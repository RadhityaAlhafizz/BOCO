<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/all', [NewsController::class, 'all'])->name('news.all');
Route::get('/{slug}', [NewsController::class, 'category'])->name('news.category');

Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/author/{username}', [AuthorController::class, 'show'])->name('author.show');


