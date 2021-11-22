<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;



Route::middleware(['auth:sanctum','verified'])->get('auth/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('auth/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
});

Route::middleware('auth:sanctum')->get('verify-email/{id}/{hash}',[AuthController::class,'verify'])->name('verification.verify');
Route::middleware('auth:sanctum')->get('email/resend',[AuthController::class,'resend'])->name('verification.resend');

//social login
Route::get('/auth/google/redirect',[AuthController::class,'GoogleAuth']);
Route::get('/auth/redirect',[AuthController::class,'Redirect']);
Route::post('/auth/register',[AuthController::class,'Register']);
