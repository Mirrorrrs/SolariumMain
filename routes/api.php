<?php

use App\Http\Controllers\Core\AuthorizationController;
use App\Http\Controllers\Core\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthorizationController::class, "login"]);
Route::get("/info",[\App\Http\Controllers\Core\SystemController::class,"info"]);
Route::post("/user/create/excel", [RegistrationController::class, "storeExcel"]);

Route::middleware('auth:api')->group(function () {
    Route::get("/user/get",[\App\Http\Controllers\Controller::class,"getUser"]);
    Route::get("/school_class/get",[\App\Http\Controllers\OrgManagement\SchoolClassController::class,"class_get"]);
    Route::post("/user/create",[RegistrationController::class,"store"]);
});
