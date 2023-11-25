<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//-----------------------------------------------------------------------------------------------------------
Route::post("register", [AuthController::class, "register"])->name("register");
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::post("login", [AuthController::class, "login"])->name("login");
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post("logout", [AuthController::class, "logout"])->name("logout");
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::apiResource('students', StudentController::class);
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::apiResource('missions', MissionController::class);
//______________________________________________________________________________________________________________________

