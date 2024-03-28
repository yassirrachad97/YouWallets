<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');


Route::post('createcompte',[WalletController::class,'createCompte']);
Route::get('searchcompte/{name}',[WalletController::class,'searchCompte'])->name('searchcompte');

Route::get('historique/{numero_compte}',[WalletController::class,'compteHistorique'])->name('historique');



Route::post('createtransaction',[TransactionController::class,'createTransaction'])->name('createtransaction')->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
