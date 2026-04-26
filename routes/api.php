<?php

use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::get('events/public', [EventController::class, 'publicList']);
    Route::get('events/public/{id}', [EventController::class, 'publicShow']);
    Route::get('events/featured', [EventController::class, 'featured']);
    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('venues/list', [VenueController::class, 'getList']);
    Route::get('venues/cities', [VenueController::class, 'getCities']);
    Route::get('event-types/list', [EventTypeController::class, 'getList']);
    Route::get('artists/list', [ArtistController::class, 'getList']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);

    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/{event}/toggle', [FavoriteController::class, 'toggle']);
    Route::post('favorites/{event}', [FavoriteController::class, 'store']);
    Route::delete('favorites/{event}', [FavoriteController::class, 'destroy']);

    Route::get('registrations', [RegistrationController::class, 'index']);
    Route::get('admin/registrations', [RegistrationController::class, 'adminIndex'])
        ->middleware('permission:event-list');
    Route::post('registrations/{event}', [RegistrationController::class, 'store']);
    Route::put('registrations/{event}', [RegistrationController::class, 'update']);
    Route::delete('registrations/{event}', [RegistrationController::class, 'destroy']);

    Route::apiResource('artists', ArtistController::class);
    Route::apiResource('event-types', EventTypeController::class)->parameters(['event-types' => 'eventType']);
    Route::apiResource('venues', VenueController::class);

    Route::apiResource('/categories', CategoryController::class);

    Route::apiResource('/events', EventController::class);
    
    Route::apiResource('/posts', PostController::class);

    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class,'updateimg']);


    Route::apiResource('roles', RoleController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    
    Route::get('/user', [ProfileController::class, 'user']);
    Route::get('/user/signin', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});


