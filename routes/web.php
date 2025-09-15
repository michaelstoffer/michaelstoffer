<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\SoftwareController;

Route::get('/', HomeController::class)->name('home');
Route::get('/software', [SoftwareController::class, 'index'])->name('software');
Route::get('/software/{slug}', [SoftwareController::class, 'show'])->name('software.show');
Route::get('/music', [MusicController::class, 'index'])->name('music');
Route::get('/music/{slug}', [MusicController::class, 'show'])->name('music.show');
