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
        $authenticatedPlayer = auth()->user();

        if ($authenticatedPlayer->id != $id) {
            return response()->json(['error' => 'Access to other player denied.'], 403);
        }

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
        $gameController = new GameController();
        $playerVictoryPct = [];
        foreach(Player::all() as $player)
        {
            $victoriesPercentage = $gameController->getAllGamesFromPlayer($player->id);
            $playerVictoryPct[$player->nickname] = $victoriesPercentage . '%';
            //echo $victoriesPercentage . " ";
        }
        
        return json_encode($playerVictoryPct);
    }

    public function showDice(int $id)
    {
        $authenticatedPlayer = auth()->user();

        if ($authenticatedPlayer->id != $id) {
            // Comprobar si el usuario autenticado es un administrador
            if (!$authenticatedPlayer->hasRole('admin')) { // Usa el método correspondiente según tu implementación de roles
                return response()->json(['error' => 'Access to other player denied.'], 403);
            }
        }

        $gameController = new GameController();

        $games = $gameController->show($id);

        return json_encode($games);
        
    }

    public function showRanking()
    {
        $gameController = new GameController();
        $victoriesPercentage = 0;
        $totalGames = 0;
        foreach(Player::all() as $player)
        {
            if(is_numeric($gameController->getAllGamesFromPlayer($player->id)))
                $victoriesPercentage += $gameController->getAllGamesFromPlayer($player->id);
        }

        return json_encode($victoriesPercentage/$gameController->getTotalPlayersWithGames() . '%');
    }

    public function showWorst()
    {
        $gameController = new GameController();
        $playerVictoryPctName = "-";
        $worstPct = 100;
        foreach(Player::all() as $player)
        {
            $victoriesPercentage = $gameController->getAllGamesFromPlayer($player->id);
            if($victoriesPercentage < $worstPct && is_numeric($victoriesPercentage))
            {
                $worstPct = $victoriesPercentage;
                $playerVictoryPctName = $player->nickname;
            }
        }
        
        return json_encode([$playerVictoryPctName, $worstPct]);
    }

    public function showBest()
    {
        $gameController = new GameController();
        $playerVictoryPctName = "-";
        $bestPct = 0;
        foreach(Player::all() as $player)
        {
            $victoriesPercentage = $gameController->getAllGamesFromPlayer($player->id);
            if($victoriesPercentage > $bestPct && is_numeric($victoriesPercentage))
            {
                $bestPct = $victoriesPercentage;
                $playerVictoryPctName = $player->nickname;
            }
        }
        
        return json_encode([$playerVictoryPctName, $bestPct]);
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

        $authenticatedPlayer = auth()->user();

        if ($authenticatedPlayer->id != $id) {
            return response()->json(['error' => 'Access to other player denied.'], 403);
        }


        $player = Player::find($id);

        if (!$player) 
        {
            return response()->json(['message' => 'Player not found'], 404);
        }
        
        $nickname = $request->json('nickname');
        

        // Si el valor de nickname no es proporcionado
        if (!$nickname) 
        {
            $nickname = 'Anonymous';
        }

        // Validar y actualizar los datos del jugador
        $validatedData = $request->validate([
            'nickname' => 'required|string|min:3|max:25',
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
        $authenticatedPlayer = auth()->user();

        if ($authenticatedPlayer->id != $id) {
            return response()->json(['error' => 'Access to other player denied.'], 403);
        }

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
