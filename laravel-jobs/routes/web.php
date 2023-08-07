<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Public Routes
// =============


Auth::routes(['register'=>false]);

Route::get('/', function () {
    // Deafult go to /jobs
    return redirect('/jobs');
    
});

// Private Routes - require auth
// =============================
Route::middleware('auth')->group(function(){


    // Job Index
    Route::GET('/jobs', [JobController::class, 'index']);
    // Job Create
    Route::GET('/jobs/create', [JobController::class, 'create']);
    // Job Store
    Route::POST('/jobs', [JobController::class, 'store']);
    // Job Show
    Route::GET('/jobs/{id}', [JobController::class, 'show']);
    // Job Update
    Route::PUT('/jobs/{id}', [JobController::class, 'update']);
    // Job Destroy - We don't need this for jobs


    //Customer
    //          URL                                      method
    Route::GET('/customers', [CustomerController::class, 'index']);
    Route::GET('/customers/create',[CustomerController::class,'create']);
    Route::POST('/customers', [CustomerController::class, 'store']);
    Route::GET('/customers/{id}', [CustomerController::class, 'show']);
    Route::GET('customers/{id}/edit', [CustomerController::class, 'edit']);
    Route::PUT('/customers/{id}', [CustomerController::class, 'update']);

    //Notifications
    Route::GET('/notifications',[NotificationController::class,'index']);
    Route::GET('/notifications/read/{id}', [NotificationController::class, 'read']);
    Route::GET('/notifications/count', [NotificationController::class,'count']);
    Route::GET('/notifications/readAll', [NotificationController::class,'readAll']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
