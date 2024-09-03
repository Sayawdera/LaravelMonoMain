<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\{
    CountryController, 
    EmailVerificationController, 
    UserController, 
    AddressesController,
};
use App\Http\Middleware\CanRolePermissions;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('/auth')->group( function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/check-user-token', [AuthController::class, 'checkUserToken']);
    Route::post('/update-your-self', [AuthController::class, 'updateYourself']);
})->middleware(CanRolePermissions::class);


Route::post('/email-verification', [EmailVerificationController::class, 'sendEmailVerification']);
Route::post('/check-email-verification', [EmailVerificationController::class, 'checkEmailVerification']);



Route::prefix('/application')->group( function (): void {
    Route::apiResource('/countries', CountryController::class);
    Route::apiResource('/addresses', AddressesController::class);
    Route::apiResource('/users', UserController::class);
})->middleware(CanRolePermissions::class);