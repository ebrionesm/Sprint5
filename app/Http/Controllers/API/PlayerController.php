<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\GameController;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function createDice(int $id)
    {
        $gameController = new GameController();
        $response = $gameController->create($id);
        return response()->json(['response' => $response]);
        //GameController::create($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayerRequest $request)
    {
        //
        $request->validate([
            'nickname' => 'required|string|min:3|max:25',
            'email' => 'required|string',
            'password' => 'required|string',
            'role' => ['required', Rule::in(['admin', 'player'])],
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //return response()->json(['id' => $id]);
        
        // Buscar el jugador por su ID
        //$player = Player::find($id);
        //return response()->json(['playerNickname' => $request->input('nickname')]);

        $player = Player::find($id);

        if (!$player) 
        {
            return response()->json(['message' => 'Player not found'], 404);
        }
        //dd(json_decode($request->getContent(), true));
        // Obtener el valor de 'nickname' del request
        //return response()->json(['request' => $request]);
        //$nickname = $request->input('nickname');
        $nickname = $request->json('nickname');
        

        // Si el valor de nickname no es proporcionado
        if (!$nickname) 
        {
            return response()->json(['message' => 'Nickname is required'], 400);
        }

        // Validar y actualizar los datos del jugador
        $validatedData = $request->validate([
            'nickname' => 'required|string|min:3|max:25',
            /*'email' => 'required|string',
            'password' => 'required|string',
            'role' => 'required', /*Rule::in(['admin', 'player'])],*/
        ]);
        

        if (!$player) 
        {
            return response()->json(['message' => 'Player not found'], 404);
        }

        

        // Actualizar los campos
        $player->nickname = $validatedData['nickname'];
        $player->save();

        // Retornar una respuesta de éxito
        return response()->json([
            'message' => 'Player updated successfully',
            'player' => $player,
        ], 200);
    }

    public function deleteDice(int $id)
    {
        $gameController = new GameController();
        $gameController->delete($id);
        return response()->json(['response' => "All rows deleted"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //
    }
}
