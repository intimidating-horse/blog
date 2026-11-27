<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Listing;

Route::get('/', function () {
    $listings = Listing::with('user')->get();
    return view('home', ['listings' => $listings]);
})->name('home');

Route::get('/listings/{listing}', function (Listing $listing) {
    $listing->load('user');

    return view('listings.show', ['listing' => $listing]);
})->name('listings.show');

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/createListing', [ListingController::class, 'createListing']);
