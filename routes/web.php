<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $listings = Listing::with('user')->get();

    return view('home', ['listings' => $listings]);
})->name('home');

Route::get('/listings/{listing}', function (Listing $listing) {
    $listing->load('user');

    return view('listings.show', ['listing' => $listing]);
})->name('listings.show');

Route::post('/listings/{listing}/bids', [BidController::class, 'store'])
    ->middleware('auth')
    ->name('listings.bids.store');

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/createListing', [ListingController::class, 'createListing']);
