<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
Route::post("/register/admin", [UserController::class, "registerAdmin"]);

Route::middleware("auth")->group(function() {
    Route::post("/logout", [UserController::class, "logout"]);
    Route::get("/me", [UserController::class, "me"]);
    Route::put("/user/update", [UserController::class, "update"]);

    Route::resource("/news", NewsController::class);
    Route::resource("/complaints", ComplaintController::class);
});
