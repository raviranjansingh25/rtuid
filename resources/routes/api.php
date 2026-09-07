<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\{ChatController};
use Illuminate\Support\Facades\Route;

Route::post('user-on-off', [ChatController::class, 'userOnOff']);

Route::post('thread-list', [ChatController::class, 'threadList']);

Route::post('chat-detail', [ChatController::class, 'chatDetail']);

Route::post('send-message', [ChatController::class, 'sendMessage']);

Route::post('message-read', [ChatController::class, 'messageRead']);

Route::post('message-delete', [ChatController::class, 'messageDelete']);

Route::post('getUser', [ChatController::class, 'getUser']);

Route::post('fileUpload', [ChatController::class, 'fileUpload']);
Route::any('unread_count', [ChatController::class, 'total_count']);

Route::post('createThread', [ChatController::class, 'createThread']);

Route::post('artist-list-demo', [UserController::class, 'index']);
