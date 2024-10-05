<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Laravel\Passport\Passport;
use App\Models\Player;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class RegisterManagementTest extends TestCase
{
    use RefreshDatabase;
    protected $player;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client --name=<client-name> --no-interaction --personal');

        $this->artisan('db:seed', ['--class' => PermissionSeeder::class]);
        $this->artisan('db:seed', ['--class' => RoleSeeder::class]);

    }

    /**
     * A basic feature test example.
     */
    public function test_player_register(): void
    {
        $this->withoutExceptionHandling();

        $nickname = 'player';
        $email = 'player@gmail.com';

        $response = $this->post('api/players', [
            'nickname' => $nickname,
            'email' => $email,
            'password' => 'password',
            //'role' => 'player'
        ]);

        $response->assertStatus(200);

        $player = Player::where('nickname', $nickname)->first();

        $this->assertNotNull($player);
        $this->assertEquals($player->nickname, $nickname);
        $this->assertEquals($player->email, $email);
        $this->assertEquals($player->role, 'player');
    }

    public function test_admin_register(): void
    {
        $this->withoutExceptionHandling();

        $nickname = 'EL admin';
        $email = 'admin@gmail.com';

        $response = $this->post('api/players', [
            'nickname' => $nickname,
            'email' => $email,
            'password' => 'password',
            'role' => 'admin'
        ]);

        $response->assertStatus(200);

        $player = Player::where('nickname', $nickname)->first();

        $this->assertNotNull($player);
        $this->assertEquals($player->nickname, $nickname);
        $this->assertEquals($player->email, $email);
        $this->assertEquals($player->role, 'admin');
    }

    public function test_guest_register(): void
    {
        $this->withoutExceptionHandling();

        $email = 'guest@gmail.com';

        $response = $this->post('api/players', [
            'nickname' => NULL,
            'email' => $email,
            'password' => 'password',
            'role' => 'player'
        ]);

        $response->assertStatus(200);

        $player = Player::where('email', $email)->first();

        $this->assertNotNull($player);
        $this->assertEquals($player->nickname, 'Anonymous');
        $this->assertEquals($player->email, $email);
        $this->assertEquals($player->role, 'player');
    }

    
}
