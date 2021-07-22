<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\DocumentContentController;
use App\Http\Controllers\AuthController;
/*

|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth' 
], function($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::get('/document-service', [FolderController::class, 'index']);
Route::post('/document-service/folder', [FolderController::class, 'store']);
Route::get('/document-service/folder/{id}', [FolderController::class, 'show']);
Route::delete('/document-service/folder', [FolderController::class, 'destroy']);

Route::get('/document-service/document/{id}', [DocumentContentController::class, 'show']);
Route::post('/document-service/document', [DocumentContentController::class, 'store']);
Route::put('/document-service/document', [DocumentContentController::class, 'update']);
Route::delete('/document-service/document', [DocumentContentController::class, 'destroy']);


