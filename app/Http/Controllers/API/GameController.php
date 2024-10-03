<?php

namespace App\Http\Controllers\API;

use App\Models\Game;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $id)
    {
        $dieOne = rand(1, 6);
        $dieTwo = rand(1, 6);
        //echo $dieOne;
        //echo $dieTwo;
        if($dieOne + $dieTwo === 7)
        {
            $victory = true;
        }
        else
        {
            $victory = false;
        }

        $game = new Game();

        $game->dieOne = $dieOne;
        $game->dieTwo = $dieTwo;
        $game->victory = $victory;
        $game->id_player = $id;

        $game->created_at = date('Y-m-d H:i:s');

        $game->save();
        //echo $victory;

        return response()->json(['dieOne' => $dieOne, 'dieTwo' => $dieTwo, 'result' => $victory]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        //
    }

    public function getAllGamesFromPlayer(int $id)
    {
        if(Game::where('id_player', '=', $id)->count() > 0)
        {
            $totalWins = Game::where('id_player', '=', $id)->where('victory', '=', '1')->count();
            $totalGames = Game::where('id_player', '=', $id)->count();
        }
        
        return Game::where('id_player', '=', $id)->count() > 0 ? ($totalWins/$totalGames) * 100 : "-";
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $games = Game::where('id_player', '=', $id)->get();

        return response()->json($games); 
    }

    public function getTotalPlayersWithGames()
    {
        return Game::distinct('id_player')->count('id_player');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    public function delete(int $id)
    {
        Game::where('id_player', $id)->delete();

        //$decks = DB::table('deck')->get();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
