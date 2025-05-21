<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProbabilityController;
use App\Http\Controllers\InferentialController;
use App\Http\Controllers\VisualizationController;

// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/basic', [AuthController::class, 'basic'])->name('basic');
    Route::get('/inferential', [AuthController::class, 'inferential'])->name('inferential');

    Route::get('/probability', [ProbabilityController::class, 'show'])->name('probability.show');
    Route::post('/probability', [ProbabilityController::class, 'calculate'])->name('probability.calculate');
    Route::get('/exponential-distribution', [ProbabilityController::class, 'exponentialDistribution']);

    Route::get('/inferential', [InferentialController::class, 'index']);
    Route::post('/confidence-interval-t', [InferentialController::class, 'confidenceIntervalT']);
    Route::post('/confidence-interval', [InferentialController::class, 'confidenceInterval']);
    Route::post('/two-sample-test', [InferentialController::class, 'twoSampleTest']);
    Route::post('/hypothesis-test', [InferentialController::class, 'hypothesisTest']);
    Route::post('/chi-square', [InferentialController::class, 'chiSquare']);

    Route::get('/visualization', [VisualizationController::class, 'show'])->name('visualization.show');
    Route::post('/visualization', [VisualizationController::class, 'render'])->name('visualization.render');
});