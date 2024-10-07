<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RankingController;

Route::get('/ranking/{movementId}', [RankingController::class, 'show']);
