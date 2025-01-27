<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestsController;
use App\Http\Middleware\JwtMiddleware;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/revalidate-token', [AuthController::class, 'revalidateToken']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/travel-requests', [TravelRequestsController::class, 'createTravelRequest']);
    Route::put('/travel-requests/{id}', [TravelRequestsController::class, 'updateTravelRequest']);
    Route::get('/travel-requests/{id}', [TravelRequestsController::class, 'getTravelRequest']);
    Route::get('/travel-requests', [TravelRequestsController::class, 'listTravelRequests']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
