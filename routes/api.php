<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PermissionController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:passport');*/

route::resource('permissions', PermissionController::class);

Route::post('/players', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group( function()
{
    
    //route::get('/players/ranking', [PlayerController::class, 'showRanking'])->name('players.showRanking');
    //route::get('/players/ranking/loser', [PlayerController::class, 'showWorst'])->name('players.showWorst');
    route::get('/players/ranking/winner', [PlayerController::class, 'showBest'])->name('players.showBest');
    route::put('/players/{id}', [PlayerController::class, 'update'])->name('players.update');
    route::post('/players/{id}/games/', [PlayerController::class, 'createDice'])->name('players.createDice');
    route::delete('/players/{id}/games', [PlayerController::class, 'deleteDice'])->name('players.deleteDice');
    //route::get('/players', [PlayerController::class, 'showPlayers'])->name('players.showPlayers');
    route::get('/players/{id}/games', [PlayerController::class, 'showDice'])->name('players.showDice');

    Route::get('/players', [PlayerController::class, 'showPlayers'])
        ->middleware('role:admin')
        ->name('players.showPlayers');
    
    Route::get('/players/ranking', [PlayerController::class, 'showRanking'])
        ->middleware('role:admin')
        ->name('players.showRanking');

    Route::get('/players/ranking/loser', [PlayerController::class, 'showWorst'])
        ->middleware('role:admin')
        ->name('players.showWorst');

    Route::get('/players/ranking/winner', [PlayerController::class, 'showBest'])
        ->middleware('role:admin')
        ->name('players.showBest');
});

//route::post('/players', [PlayerController::class, 'create']);