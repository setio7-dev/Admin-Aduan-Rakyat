<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get("/beranda", function() {
    return view("pages.news");
});

Route::get("/pengaduan", function() {
    return view("pages.complaint");
});