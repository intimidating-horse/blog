<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Listing;

Route::get('/', function () {
    $listings = Listing::all();
    return view('home', ['listings' => $listings]);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/createListing', [ListingController::class, 'createListing']);
