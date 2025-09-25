<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('api')
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

Route::middleware(['auth:sanctum'])->prefix('api')->group(function () {
    // public (logged-in) endpoints
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::post('/books/borrow/{book}', [BorrowingController::class, 'borrow']);
    Route::post('/books/return/{book}', [BorrowingController::class, 'return']);

   Route::middleware(['admin'])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
   });
});