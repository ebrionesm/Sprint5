<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

route::post('/players', [PlayerController::class, 'create']);
var_dump("hola");
route::put('/players/{id}', [PlayerController::class, 'edit'])->name('players.edit');
route::post('/players/{id}/games/', [PlayerController::class, 'createDice'])->name('players.createDice');
route::delete('/players/{id}/games', [PlayerController::class, 'deleteDice'])->name('players.deleteDice');
route::get('/players', [PlayerController::class, 'showPlayers'])->name('players.showPlayers');
route::get('/players/{id}/games', [PlayerController::class, 'showDice'])->name('players.showDice');
route::get('/players/ranking', [PlayerController::class, 'showRanking'])->name('players.showRanking');
route::get('/players/ranking/loser', [PlayerController::class, 'showWorst'])->name('players.showWorst');
route::get('/players/ranking/winner', [PlayerController::class, 'showBest'])->name('players.showBest');