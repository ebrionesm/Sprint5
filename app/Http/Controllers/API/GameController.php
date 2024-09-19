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
            $victory = 'true';
        }
        else
        {
            $victory = 'false';
        }

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

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
