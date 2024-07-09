<?php

use Illuminate\Http\Request;
use App\Http\Controllers\fileuploads;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/upload_file', [fileuploads::class, 'upload']);

Route::get('/retrive_info/{id}', [fileuploads::class, 'retrieve_info']);

Route::get('/retrive_file/{id}', [fileuploads::class, 'retrieve_file']);

Route::delete('/delet_info/{id}', [fileuploads::class, 'delete_info']);

Route::put('/update_info/{id}/{new_name}', [fileuploads::class, 'update_info']);

