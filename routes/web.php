<?php

use App\Http\Controllers\AudiologyController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OccupationalController;
use App\Http\Controllers\OphthalmologyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Here is where you can register web routes for your application. These routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 */
// Route::get('/welcome', fn() => view('welcome'))->name('welcome');
// Route::get('/403', fn() => view('errors.403'))->name('error.403');
// Route::get('/404', fn() => view('errors.404'))->name('error.404');
// Route::get('/500', fn() => view('errors.500'))->name('error.500');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');
Route::post('/login', [AuthenticationController::class, 'authenticate'])->name('login.authenticate');

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{user?}', [UserController::class, 'edit'])->name('users.edit');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::patch('/users/profile/{user}', [UserController::class, 'updateProfile'])->name('users.update.profile');
Route::patch('/users/password/{user}', [UserController::class, 'updatePassword'])->name('users.update.password');

Route::get('/plans', [PlanController::class, 'index'])->name('plans');
Route::get('/plans/edit/{plan?}', [PlanController::class, 'edit'])->name('plans.edit');
Route::get('/plans/json', [PlanController::class, 'json'])->name('plans.json');
Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');

Route::get('/orders/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::get('/orders/{order:order_number}', [OrderController::class, 'pdf'])->name('orders.pdf');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/patients', [PatientController::class, 'index'])->name('patients');

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates');

Route::get('/forms', [FormController::class, 'index'])->name('forms');
Route::get('/forms/audiology', [AudiologyController::class, 'index'])->name('form.audiology');
Route::get('/forms/occupational', [OccupationalController::class, 'index'])->name('form.occupational');
Route::get('/forms/ophthalmology', [OphthalmologyController::class, 'index'])->name('form.ophthalmology');
Route::post('/forms/audiology', [AudiologyController::class, 'store'])->name('form.audiology.store');
Route::post('/forms/occupational', [OccupationalController::class, 'store'])->name('form.occupational.store');
Route::post('/forms/ophthalmology', [OphthalmologyController::class, 'store'])->name('form.ophthalmology.store');

Route::get('/documents/{certificate}', [PDFController::class, 'generate'])->name('certificates.pdf');
