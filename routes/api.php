<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/get-attendance-today', [AttendanceController::class, 'getAttendanceToday'])->name('get_attendance_today');
    Route::get('/get-schedule', [AttendanceController::class, 'getSchedule'])->name('get_schedule');
    Route::post('/store-attendance', [AttendanceController::class, 'store'])->name('store_attendance');
    Route::get('/get-attendance-by-month-year/{month}/{year}', [AttendanceController::class, 'getAttendanceByMonthYear'])->name('get_attendance_by_month_year');
    Route::post('/banned', [AttendanceController::class, 'banned'])->name('banned');
    Route::get('/get-image', [AttendanceController::class, 'getImage'])->name('get_image');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');