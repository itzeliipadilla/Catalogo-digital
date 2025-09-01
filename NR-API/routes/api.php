<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::get('/familias', [FamilyController::class, 'get']);
Route::get('/informacion-productos/{family}',[ProductController::class,'getPublic']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::patch('/update-password', [UserController::class, 'updatePassword']);
    Route::get('/getImage/{imagen}',[CatalogController::class,'getImage']);
    Route::post('/productos-catalogo',[CatalogController::class,'getInfo']);
});

Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function () {

    //users
    Route::get('/get-users', [UserController::class, 'get']);
    Route::get('/get-user/{id}', [UserController::class, 'getOne']);
    Route::post('/register-user', [UserController::class, 'register']);
    Route::put('/update-user/{id}', [UserController::class, 'update']);
    Route::delete('/delete-user/{id}', [UserController::class, 'delete']);

    //products
    Route::post('/registrar-producto', [ProductController::class, 'register']);
    Route::get('/productos', [ProductController::class, 'get']);
    Route::get('/producto/{id}', [ProductController::class, 'getOne']);
    Route::delete('/eliminar-producto/{id}', [ProductController::class, 'delete']);
    Route::post('/actualizar-producto/{id}', [ProductController::class, 'update']);

    //families
    Route::post('/registrar-familia', [FamilyController::class, 'register']);
});

Route::middleware(['auth:sanctum', 'role:General'])->group(function () {

});
