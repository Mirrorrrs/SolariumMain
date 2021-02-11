<?php

use App\Http\Controllers\Core\AuthorizationController;
use App\Http\Controllers\Core\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthorizationController::class, "login"]);
Route::get("/info",[\App\Http\Controllers\Core\SystemController::class,"info"]);

Route::middleware('auth:api')->group(function () {
    Route::get("/user/get",[\App\Http\Controllers\Controller::class,"getUser"]);
    Route::post("/user/create",[RegistrationController::class,"store"]);
    Route::post("/user/create/excel", [RegistrationController::class, "storeExcel"]);
});
