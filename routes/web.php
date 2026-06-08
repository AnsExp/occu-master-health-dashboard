<?php

use App\Http\Controllers\AudiologyController;
use App\Http\Controllers\AuditoryController;
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

Route::get('/users', [UserController::class, 'index'])->middleware('auth')->name('users');
Route::post('/users', [UserController::class, 'store'])->middleware('auth')->name('users.store');
Route::get('/users/create', [UserController::class, 'create'])->middleware('auth')->name('users.create');
Route::get('/users/edit/{user}', [UserController::class, 'edit'])->middleware('auth')->name('users.edit');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('auth')->name('users.destroy');
Route::patch('/users/profile/{user}', [UserController::class, 'updateProfile'])->middleware('auth')->name('users.update.profile');
Route::patch('/users/password/{user}', [UserController::class, 'updatePassword'])->middleware('auth')->name('users.update.password');

Route::get('/plans', [PlanController::class, 'index'])->middleware('auth')->name('plans');
Route::get('/plans/create', [PlanController::class, 'create'])->middleware('auth')->name('plans.create');
Route::get('/plans/edit/{plan}', [PlanController::class, 'edit'])->middleware('auth')->name('plans.edit');
Route::post('/plans', [PlanController::class, 'store'])->middleware('auth')->name('plans.store');
Route::put('/plans/{plan}', [PlanController::class, 'update'])->middleware('auth')->name('plans.update');
Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->middleware('auth')->name('plans.destroy');
Route::get('/plans/json', [PlanController::class, 'json'])->middleware('auth')->name('plans.json');

Route::get('/orders/create', [OrderController::class, 'create'])->middleware('auth')->name('orders.create');
Route::get('/orders/{order:order_number}', [OrderController::class, 'pdf'])->middleware('auth')->name('orders.pdf');
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth')->name('orders');
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');

Route::get('/patients', [PatientController::class, 'index'])->middleware('auth')->name('patients');

Route::get('/certificates', [CertificateController::class, 'index'])->middleware('auth')->name('certificates');

Route::get('/forms', [FormController::class, 'index'])->middleware('auth')->name('forms');
Route::get('/forms/audiology', [AudiologyController::class, 'create'])->middleware('auth')->name('form.audiology');
Route::get('/forms/occupational', [OccupationalController::class, 'create'])->middleware('auth')->name('form.occupational');
Route::get('/forms/ophthalmology', [OphthalmologyController::class, 'create'])->middleware('auth')->name('form.ophthalmology');
Route::post('/forms/audiology', [AudiologyController::class, 'store'])->middleware('auth')->name('form.audiology.store');
Route::post('/forms/occupational', [OccupationalController::class, 'store'])->middleware('auth')->name('form.occupational.store');
Route::post('/forms/ophthalmology', [OphthalmologyController::class, 'store'])->middleware('auth')->name('form.ophthalmology.store');

Route::get('/documents/{certificate:certificate_number}', [PDFController::class, 'generate'])->middleware('auth')->name('certificates.pdf');

Route::get('/audit', [AuditoryController::class, 'index'])->middleware('auth')->name('audit.index');
Route::get('/audit/{log}', [AuditoryController::class, 'detail'])->middleware('auth')->name('audit.detail');