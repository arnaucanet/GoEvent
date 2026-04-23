<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::get('events/public', [EventController::class, 'publicList']);
    Route::get('events/public/{id}', [EventController::class, 'publicShow']);
    Route::get('events/featured', [EventController::class, 'featured']);
    Route::get('cities/list', [CityController::class, 'getList']);
    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);

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


