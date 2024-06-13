<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RobotController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('robot')->group(function () {
    Route::get('/list', [RobotController::class, 'getRobotsList'])->name('robot.list');
    Route::post('/', [RobotController::class, 'createRobot'])->name('robot.create');
    Route::get('/status/{id}', [RobotController::class, 'getRobotStatus'])->name('robot.status');
    Route::post('/off/{id}', [RobotController::class, 'turnOff'])->name('robot.off');
    Route::post('/on/{id}', [RobotController::class, 'turnOn'])->name('robot.on');
    Route::post('/feedback', [RobotController::class, 'addFeedback'])->name('robot.add-feedback');
    Route::get('/feedback/{id}', [RobotController::class, 'getFeedbacks'])->name('robot.get-feedback');
});

require __DIR__.'/auth.php';
