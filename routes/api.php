<?php

use App\Http\Controllers\Api\Account\AccountController;
use App\Http\Controllers\Api\Account\AddressController;
use App\Http\Controllers\Api\Account\ProfileControler;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\JWTAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong'
    ], Response::HTTP_OK);
});

Route::prefix('/v1')->group(function () {
    Route::get("me", [AuthController::class, 'user'])->name('user.me')
        ->middleware([JWTAuthenticate::class]);

    Route::prefix('/auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    });

    Route::prefix('/account')->middleware([JWTAuthenticate::class])->group(function () {
        Route::post('type', [
            AccountController::class,
            'defineType'
        ])
            ->name('account.type')
            ->middleware('flags:ACCOUNT_TASK_LEVEL_1');

        Route::resource('profile', ProfileControler::class, [
            'index',
            'store',
            'update',
            'destroy'
        ])->names([
            'index' => 'account.profile.index',
            'store' => 'account.profile.store',
            'update' => 'account.profile.update',
            'destroy' => 'account.profile.destroy',
        ])->middlewareFor(['store'], 'flags:ACCOUNT_TASK_LEVEL_2');

        Route::post('/address/create', [AddressController::class, 'create'])
            ->name('account.address.create')
            ->middleware('flags:ACCOUNT_TASK_LEVEL_3');
        Route::resource('address', AddressController::class, [
            'index',
            'show',
            'store',
            'update',
            'destroy'
        ])->names([
            'index' => 'account.address.index',
            'show' => 'account.address.show',
            'store' => 'account.address.store',
            'update' => 'account.address.update',
            'destroy' => 'account.address.destroy',
        ])->parameters(['address' => 'addressId']);
    });

    Route::prefix('/app')->group(function () {});
});
