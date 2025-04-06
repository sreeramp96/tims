<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;
use App\Models\Project;

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

    // Route::post('/save-timesheet', function () {
    //     return back()->with('success', 'Time entry saved successfully!');
    // })->name('save-timesheet');

    Route::get('/timesheet', [TimesheetController::class, 'index']);

    Route::post('/save-timesheet', [TimesheetController::class, 'store'])->name('save-timesheet');

    Route::get('/projects', function () {
        return response()->json(Project::all());
    });
    Route::get('/timesheet-entries', [TimeSheetController::class, 'getEntries']);

    Route::delete('/timesheet-entries/{id}', [TimesheetController::class, 'destroy'])->name('timesheet.destroy');

    Route::put('/timesheet-entries/{id}', [TimesheetController::class, 'update'])->name('timesheet.update');
});


require __DIR__ . '/auth.php';
