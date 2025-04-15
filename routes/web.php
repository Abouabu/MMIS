<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\ReceptionistAuthController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
Route::post('/patients/{patient}/assign-doctor', [PatientController::class, 'assignDoctor'])->name('patients.assignDoctor');

Route::get('/receptionist/login', [ReceptionistAuthController::class, 'showLoginForm'])->name('receptionist.login');
Route::post('/receptionist/login', [ReceptionistAuthController::class, 'login'])->name('receptionist.login.submit');
Route::post('/receptionist/logout', [ReceptionistAuthController::class, 'logout'])->name('receptionist.logout');

// Add a route for receptionist dashboard after login if needed
// Route::get('/receptionist/home', function () {
//     return view('receptionist.home'); // Create this view later
// })->middleware('auth:receptionist')->name('receptionist.home');
