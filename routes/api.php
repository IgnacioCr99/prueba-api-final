<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;


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
Route::get('/usuarios', [UsersController::class, 'index']);
Route::get('/usuarios/{nombre}', [UsersController::class, 'getByName']);
Route::get('/usuarios/email/{email}', [UsersController::class, 'getByEmail']);
Route::get('/usuarios/status/{status}',[UsersController::class,'getByStatus']);
Route::post('/usuarios',[UsersController::class,'postUsers']);
Route::put('/usuarios/{id}',[UsersController::class,'putUsers']);
Route::patch('/usuarios',[UsersController::class,'patchByEmail']);
//

