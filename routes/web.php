<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-timesheet', function () {
        return view('timesheet');
    })->name('timesheet');
    Route::post('/save-timesheet', function () {
        // Handle form submission logic here
        return back()->with('success', 'Time entry saved successfully!');
    })->name('save-timesheet');
});


require __DIR__ . '/auth.php';
