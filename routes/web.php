<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CspReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\SoftwareController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::post('/csp-report', CspReportController::class)
    ->middleware('throttle:csp-reports')
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class])
    ->name('csp.report');

// Read-only pages are intentionally stateless. This avoids creating a database
// session and setting a session cookie for every anonymous visitor.
Route::middleware([])
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class])
    ->group(function (): void {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/software', [SoftwareController::class, 'index'])->name('software');
        Route::get('/software/{slug}', [SoftwareController::class, 'show'])->name('software.show');
        Route::get('/music', [MusicController::class, 'index'])->name('music');
        Route::get('/music/{slug}', [MusicController::class, 'show'])->name('music.show');
        Route::get('/photography', [PhotographyController::class, 'index'])->name('photography');
        Route::get('/photography/{slug}', [PhotographyController::class, 'show'])->name('photography.show');
        Route::get('/blog', [BlogController::class, 'index'])->name('blog');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
        Route::get('/about', [AboutController::class, 'index'])->name('about');
    });

// Contact remains stateful for CSRF protection, validation errors, and success messages.
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');
