<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Laravel\Passport\Passport;
use App\Models\Player;
use App\Models\Game;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class PlayerManagementTest extends TestCase
{
    use RefreshDatabase;
    protected $player;
    protected $otherPlayer;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client --name=<client-name> --no-interaction --personal');

        $this->artisan('db:seed', ['--class' => PermissionSeeder::class]);
        $this->artisan('db:seed', ['--class' => RoleSeeder::class]);

        $this->player = Player::factory()->create([
            'nickname' => 'player',
            'email' => 'player@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'player'
        ]);

        $this->admin = Player::factory()->create([
            'nickname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $this->otherPlayer = Player::factory()->create([
            'nickname' => 'otherPlayer',
            'email' => 'otherPlayer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'player'
        ]);

    }

    /**
     * A basic feature test example.
     */
    public function test_modify_nickname()
    {
        $token = $this->player->createToken('playerToken')->accessToken;

        $newNickname = 'El player';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put("api/players/{$this->player->id}", [
            'nickname' => $newNickname,
        ]);

        $response->assertStatus(200);

        $this->player->refresh();

        $this->assertEquals($newNickname, $this->player->nickname);

    }

    public function test_create_game()
    {
        $token = $this->player->createToken('playerToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("api/players/{$this->player->id}/games");

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'id_player' => $this->player->id,
        ]);
    }

    public function test_cant_create_game_other_players()
    {
        $token = $this->player->createToken('playerToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("api/players/{$this->otherPlayer->id}/games");

        $response->assertStatus(403);

        $this->assertDatabaseMissing('games', [
            'id_player' => $this->otherPlayer->id,
        ]);
    }

    public function test_delete_all_games()
    {
        $token = $this->player->createToken('playerToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("api/players/{$this->player->id}/games");

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete("api/players/{$this->player->id}/games");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('games', [
            'id_player' => $this->player->id,
        ]);
    }

    public function test_cant_delete_all_games_other_player()
    {
        //Se crea una partida para el jugador 1
        Game::factory()->create([
            'id_player' => $this->player->id,
        ]);

        //Jugador 2 se loguea
        $tokenOther = $this->otherPlayer->createToken('playerToken')->accessToken;

        //Jugador 2 intenta borrar las partidas del jugador 1
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $tokenOther,
        ])->delete("api/players/{$this->player->id}/games");

        //Acceso denegado
        $response->assertStatus(403);

        //Las partidas están en la base de datos aún
        $this->assertDatabaseHas('games', [
            'id_player' => $this->player->id,
        ]);
    }

    public function test_see_all_players()
    {
        $token = $this->admin->createToken('adminToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("api/players");

        $response->assertStatus(200);

    }

    
}
