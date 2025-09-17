<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;

Route::get('/', HomeController::class)->name('home');
Route::get('/software', [SoftwareController::class, 'index'])->name('software');
Route::get('/software/{slug}', [SoftwareController::class, 'show'])->name('software.show');
Route::get('/music', [MusicController::class, 'index'])->name('music');
Route::get('/music/{slug}', [MusicController::class, 'show'])->name('music.show');
Route::get('/photography', [PhotographyController::class, 'index'])->name('photography');
Route::get('/photography/{slug}', [PhotographyController::class, 'show'])->name('photography.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');



