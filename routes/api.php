<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ConfirmationCodeController;
use App\Http\Controllers\TamagotchiController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-confirmation-code', [ConfirmationCodeController::class, 'sendConfirmationCode']);
Route::post('/confirmEmail', [ConfirmationCodeController::class, 'ConfirmEmail']);

Route::post('/register_post', [UserController::class, 'register_post']);
Route::post('/auth_post', [UserController::class, 'auth_post']);

Route::post('/tamagotchiCreate', [TamagotchiController::class, 'createTamagotchi']);

Route::post('/addTask', [TaskController::class, 'addTask']);
Route::post('/getTasks', [TaskController::class, 'getTasks']);

Route::post('/updateTaskStatus', [TaskController::class, 'updateTaskStatus']);
Route::post('/addPoints', [UserController::class, 'addPoints']);

Route::post('/tamagotchi', [TamagotchiController::class, 'getTamagotchiStatus']);
Route::post('/updateTamagotchiStatus', [TamagotchiController::class, 'updateTamagotchiStatus']);

Route::get('/achievements/{userId}', [AchievementController::class, 'getAchievements']);
