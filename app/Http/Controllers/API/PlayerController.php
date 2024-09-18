<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use Illuminate\Http\Request;

class PlayerController extends Controller
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
    public static function create()
    {
        //store($request);
        var_dump("create");
        return 5;
    }

    public function createDice(Player $player)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayerRequest $request)
    {
        //
        $request->validate([
            'nickname' => 'required|string|min:3|max:25',
            'email'
        ]);

        $player = new Player;

        $player->nickname = $request->nickname;

        $player->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        //
    }

    public function showPlayers()
    {

    }

    public function showDice(Player $player)
    {

    }

    public function showRanking()
    {

    }

    public function showWorst()
    {

    }

    public function showBest()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        echo "AAAA";
        // Buscar el jugador por su ID
        $player = Player::findOrFail($id);

        // Validar y actualizar los datos del jugador
        $validatedData = $request->validate([
            'nickname' => 'required|string|max:255',
        ]);

        // Actualizar los campos
        $player->update($validatedData);

        // Retornar una respuesta de éxito
        return response()->json([
            'message' => 'Player updated successfully',
            'player' => $player,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlayerRequest $request, Player $player)
    {
        //
    }

    public function deleteDice(Player $player)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //
    }
}
