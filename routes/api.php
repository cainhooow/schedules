<?php

use App\Http\Controllers\Api\Account\AccountController;
use App\Http\Controllers\Api\Account\AddressController;
use App\Http\Controllers\Api\Account\ProfileController;
use App\Http\Controllers\Api\Account\Services\UserServiceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\JwtAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get(
    '/user',
    fn(Request $request) =>
    $request->user()
)->middleware('auth:sanctum');

Route::get(
    '/ping',
    fn() =>
    response()->json([
        'message' => 'pong'
    ], Response::HTTP_OK)
);

Route::prefix('/v1')->group(function () {
    Route::get("me", [AuthController::class, 'user'])->name('user.me')
        ->middleware([JwtAuthenticate::class]);

    Route::prefix('/auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    });

    Route::prefix('/account')->middleware([JwtAuthenticate::class])->group(function () {
        Route::post('type', [
            AccountController::class,
            'defineType'
        ])
            ->name('account.type')
            ->middleware('flags:ACCOUNT_TASK_LEVEL_1');

        Route::resource('profile', ProfileController::class, [
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

        Route::resource('services', UserServiceController::class)
            ->names([
                'index' => 'services.index',
                'show' => 'services.show',
                'store' => 'services.store',
                'update' => 'services.update',
                'destroy' => 'services.destroy'
            ])
            ->middlewareFor([
                'store',
                'update',
                'destroy'
            ], [
                JwtAuthenticate::class,
                'flags:CAN_CREATE_SERVICES,CAN_UPDATE_SERVICES'
            ])
            ->parameters(['services' => 'serviceId']);
    });

    Route::prefix('/app')->group(function () {});
});
