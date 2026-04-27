<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Event\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::Post('register',[AuthController::class,'register']);
Route::Post('login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',[AuthController::class,'logout']);
});
 // Category routes no auth needed

Route::get('category.all', [CategoryController::class, 'index'])->name('category.all');
Route::get('category.show/{id}', [CategoryController::class, 'show'])->name('category.show');

// Event routes no auth needed
Route::get('event.all', [EventController::class, 'index'])->name('event.all');
Route::get('event.show/{id}', [EventController::class, 'show'])->name('event.show');

Route::middleware(['auth:sanctum','role:admin'])->group(function () {

// // Category  routes for admin
    Route::post('category.create', [CategoryController::class, 'create']);
    Route::delete('category.destroy/{id}', [CategoryController::class, 'destroy']);
    Route::put('category.update/{id}', [CategoryController::class, 'update']);
    Route::get('category.allevents', [CategoryController::class, 'categoryAllEvents'])->name('category.allevents');
    Route::get('category.show.w.events/{id}', [CategoryController::class, 'showCategoryWithEvents'])->name('category.show.w.events');

// //  Event routes  for admin

    Route::post('event.create', [EventController::class, 'create']);
    Route::delete('event.destroy/{id}', [EventController::class, 'destroy']);
    Route::put('event.update/{id}', [EventController::class, 'update']);
    Route::get('event.allcategories', [EventController::class, 'eventAllCategories'])->name('event.allcategories');
    Route::get('event.show.w.categories/{id}', [EventController::class, 'eventWithCategories'])->name('event.show.w.categories');

    // Route::delete('/products/{product}/media/{mediaId}', [EventController::class, 'deleteMedia']);

// Booking routes for admin
    Route::get('booking.alldata', [BookingController::class, 'allWData']);
    Route::get('booking.with.data.show/{id}', [BookingController::class, 'showWithData']);
    Route::put('booking.update/{id}', [BookingController::class, 'update']);
});
    #
Route::middleware(['auth:sanctum' ,'role:user' ])->group(function () {
    Route::get('booking.all', [BookingController::class, 'index']);
    Route::get('booking.show/{id}', [BookingController::class, 'show']);
    Route::post('booking.create/{eventId}', [BookingController::class, 'create']);
    Route::delete('booking.destroy/{id}', [BookingController::class, 'destroy']);
    // Route::delete('/products/{product}/media/{mediaId}', [EventController::class, 'deleteMedia']);
});



