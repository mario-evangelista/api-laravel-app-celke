<?php

use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecoverPasswordCodeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota pública
Route::post('/login', [LoginController::class, 'login'])->name('login'); // POST - http://127.0.0.1:8000/api/login

// Recuperar a senha
Route::post("/forgot-password-code", [RecoverPasswordCodeController::class, 'forgotPasswordCode']);
Route::post("/reset-password-validate-code", [RecoverPasswordCodeController::class, 'resetPasswordValidateCode']);
Route::post("/reset-password-code", [RecoverPasswordCodeController::class, 'resetPasswordCode']);

// Rota restrita
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/profile', [ProfileController::class, 'show']); // GET - http://127.0.0.1:8000/api/profile
    Route::put('/profile', [ProfileController::class, 'update']); // PUT - http://127.0.0.1:8000/api/profile
    Route::put('/profile-password', [ProfileController::class, 'updatePassword']); // PUT - http://127.0.0.1:8000/api/profile-password

    Route::get('/users', [UserController::class, 'index']); // GET - http://127.0.0.1:8000/api/users?page=2
    Route::get('/users/{user}', [UserController::class, 'show']); // GET - http://127.0.0.1:8000/api/users/1
    Route::post('/users', [UserController::class, 'store']); // POST - http://127.0.0.1:8000/users/bills
    Route::put('/users/{user}', [UserController::class, 'update']); // PUT - http://127.0.0.1:8000/api/users/1
    Route::put('/users-password/{user}', [UserController::class, 'updatePassword']); // PUT - http://127.0.0.1:8000/api/users-password/1
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/users/1

    Route::get('/bills', [BillController::class, 'index']); // GET - http://127.0.0.1:8000/api/bills?page=2
    Route::get('/bills/{bill}', [BillController::class, 'show']); // GET - http://127.0.0.1:8000/api/bills/1
    Route::post('/bills', [BillController::class, 'store']); // POST - http://127.0.0.1:8000/api/bills
    Route::put('/bills/{bill}', [BillController::class, 'update']); // PUT - http://127.0.0.1:8000/api/bills/1
    Route::delete('/bills/{bill}', [BillController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/bills/1

    Route::post('/logout', [LoginController::class, 'logout']); // POST - http://127.0.0.1:8000/api/logout
});
