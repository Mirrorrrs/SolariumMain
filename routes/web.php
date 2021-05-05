<?php

use App\Http\Controllers\Core\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/dl/excels/{id}', [\App\Http\Controllers\Core\SystemController::class, "download"])->name("apidl.excels");


