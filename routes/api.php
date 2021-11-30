<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;



Route::middleware(['auth:sanctum','verified'])->get('auth/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum','verified'])->get('auth/logout',[AuthController::class,'logout']);

Route::middleware(['signed'])->get('verify-email/{id}/{hash}',[AuthController::class,'verify'])->name('verification.verify');
Route::middleware('auth:sanctum')->post('email/resend',[AuthController::class,'resend'])->name('verification.resend');
Route::post('/auth/forgot-password',[AuthController::class,'forgotPassword']);
Route::post('/auth/reset-password',[AuthController::class,'reset']);
//social login
Route::get('/auth/google/redirect',[AuthController::class,'GoogleAuth']);
Route::get('/auth/redirect',[AuthController::class,'Redirect']);
Route::post('/auth/register',[AuthController::class,'Register']);
Route::post('/auth/login',[AuthController::class,'Login']);