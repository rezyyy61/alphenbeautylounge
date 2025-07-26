<?php

use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\BlockedPeriodController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TestimonialController;

Route::get('/services', fn () => Service::all());

Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/taken/{day}', [AppointmentController::class, 'takenTimes']);

Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::post('/testimonials', [TestimonialController::class, 'store']);
Route::apiResource('testimonials', TestimonialController::class);

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/blocked-periods', [BlockedPeriodController::class, 'index']);
Route::post('/blocked-periods', [BlockedPeriodController::class, 'store']);
Route::put('/blocked-periods/{blockedPeriod}', [BlockedPeriodController::class, 'update']);
Route::delete('/blocked-periods/{blockedPeriod}', [BlockedPeriodController::class, 'destroy']);
Route::get('/blocked-periods/active', [BlockedPeriodController::class, 'active']);

Route::get('/appointments/conflicts', [AdminAppointmentController::class, 'checkConflicts']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', fn () => ['message' => 'API ok']);
