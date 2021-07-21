<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\DocumentContentController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/document-service', [FolderController::class, 'index']);
Route::post('/document-service/folder', [FolderController::class, 'store']);
Route::get('/document-service/folder/{id}', [FolderController::class, 'show']);
Route::delete('/document-service/folder', [FolderController::class, 'destroy']);

Route::get('/document-service/document/{id}', [DocumentContentController::class, 'show']);
Route::post('/document-service/document', [DocumentContentController::class, 'store']);
Route::put('/document-service/document', [DocumentContentController::class, 'update']);
Route::delete('/document-service/document', [DocumentContentController::class, 'destroy']);


