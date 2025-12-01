<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TechnicianController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');

// Technician pages
Route::get('/technician', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
Route::get('/technician/jobs', [TechnicianController::class, 'index'])->name('technician.jobs.index');
Route::get('/technician/jobs/create', [TechnicianController::class, 'create'])->name('technician.jobs.create');
Route::post('/technician/jobs', [TechnicianController::class, 'store'])->name('technician.jobs.store');
Route::get('/technician/jobs/{id}', [TechnicianController::class, 'show'])->name('technician.jobs.show');
