<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('empresas', [EmpresaController::class, 'index']);
Route::get('empresas/{cnpj}', [EmpresaController::class, 'get']);
Route::post('empresas', [EmpresaController::class, 'store']);
Route::put('empresas/{cnpj}', [EmpresaController::class, 'update']);
Route::delete('empresas/{cnpj}',[EmpresaController::class, 'destroy']);
Route::get('empresas/details/{cnpj}', [EmpresaController::class, 'show']);
