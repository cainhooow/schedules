<?php

use App\Http\Controllers\Api\Account\AccountController;
use App\Http\Controllers\Api\Account\AddressController;
use App\Http\Controllers\Api\Account\ProfileController;
use App\Http\Controllers\Api\Account\Services\SchedulesController;
use App\Http\Controllers\Api\Account\Services\UserServiceController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\Providers\GoogleOAuthController;
use App\Http\Controllers\Api\CommitmentController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Middleware\JwtAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get(
    '/user',
    fn(Request $request) => $request->user()
)->middleware('auth:sanctum');

Route::get(
    '/ping',
    fn() => response()->json([
        'message' => 'pong',
    ], Response::HTTP_OK)
);

Route::prefix('/v1')->group(function () {
    // Route::get("me", [AuthController::class, 'user'])->name('user.me')
    //     ->middleware([JwtAuthenticate::class]);

    Route::prefix('/auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('forgot-password', [AccountController::class, 'forgotPassword'])->name('auth.account.forgot-password');
        Route::post('reset-password', [AccountController::class, 'resetPassword'])->name('auth.account.reset-password');

        Route::get('providers/google', [GoogleOAuthController::class, 'redirect'])->name('oauth.google.redirect');
        Route::get('providers/google/callback', [GoogleOAuthController::class, 'callback'])->name('oauth.google.callback');

    });

    Route::prefix('me')->middleware([JwtAuthenticate::class])->group(function () {
        Route::get('/', [AuthController::class, 'user']);

        Route::prefix('/account')->group(function () {
            Route::post('type', [
                AccountController::class,
                'defineType',
            ])
                ->name('account.type')
                ->middleware('flags:AccountTaskLevel1');
        });

        Route::resource('profile', ProfileController::class, [
            'index',
            'store',
            'update',
            'destroy',
        ])->names([
                    'index' => 'me.profile.index',
                    'store' => 'me.profile.store',
                    'update' => 'me.profile.update',
                    'destroy' => 'me.profile.destroy',
                ])->middlewareFor(['store'], 'flags:AccountTaskLevel2');

        Route::post('/address/create', [AddressController::class, 'create'])
            ->name('me.address.create')
            ->middleware('flags:AccountTaskLevel3');
        Route::resource('address', AddressController::class, [
            'index',
            'show',
            'store',
            'update',
            'destroy',
        ])->names([
                    'index' => 'me.address.index',
                    'show' => 'me.address.show',
                    'store' => 'me.address.store',
                    'update' => 'me.address.update',
                    'destroy' => 'me.address.destroy',
                ])->parameters(['address' => 'addressId']);

        Route::resource('services', UserServiceController::class)
            ->names([
                'index' => 'me.services.index',
                'show' => 'me.services.show',
                'store' => 'me.services.store',
                'update' => 'me.services.update',
                'destroy' => 'me.services.destroy',
            ])
            ->middleware(['owns.service:ServiceServices,serviceId'])
            ->middlewareFor([
                'store',
                'update',
                'destroy',
            ], [
                'flags:CanCreateServices,CanUpdateServices',
            ])
            ->parameters(['services' => 'serviceId']);

        Route::resource('services.schedules', SchedulesController::class)
            ->names([
                'index' => 'me.schedules.index',
                'show' => 'me.schedules.show',
                'store' => 'me.schedules.store',
                'update' => 'me.schedules.update',
                'destroy' => 'me.schedules.destroy',
            ])
            ->middleware([
                'owns.service:ServiceServices,serviceId',
            ])
            ->middlewareFor([
                'store',
                'update',
                'destroy',
            ], [
                'flags:CanCreateServices,CanUpdateServices',
            ])->parameters([
                    'services' => 'serviceId',
                    'schedules' => 'scheduleId',
                ]);

        Route::get('/commitments', [CommitmentController::class, 'index'])
            ->name('me.commitments.index');
        Route::get('/commitments/{commitmentId}', [CommitmentController::class, 'show'])
            ->name('me.commitments.show');
    });

    Route::get('/services', [ServicesController::class, 'index'])
        ->name('services.index');

    Route::get('/services/{serviceId}', [ServicesController::class, 'show'])
        ->name('services.show');

    Route::get('/services/{serviceId}/schedules', [ServicesController::class, 'getSchedules'])
        ->name('services.schedules');

    Route::middleware([JwtAuthenticate::class])
        ->post('/services/{serviceId}/schedule/{scheduleId}', [ServicesController::class, 'toSchedule'])
        ->middleware('flags:CanContractServices')
        ->name('services.schedule');
});
