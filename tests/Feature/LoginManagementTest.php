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
use Illuminate\Support\Facades\Hash;

class LoginManagementTest extends TestCase
{
    use RefreshDatabase;
    protected $player;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:keys --force');
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
            'password' => Hash::make('passwordAdmin'),
            'role' => 'admin'
        ]);

    }

    /**
     * A basic feature test example.
     */
    public function test_player_login() : void
    {
        $response = $this->post('api/login', [
            'email' => $this->player->email,
            "password" => 'password'
        ]);

        $response->assertStatus(200);

        $player = Player::where("email", "player@gmail.com")->first();

        $this->assertTrue(Hash::check('password', $player->password));

        $this->assertArrayHasKey('token', $response['data']);

        $this->assertNotNull($response['data']['token']);
    }

    public function test_admin_login() : void
    {
        $response = $this->post('api/login', [
            'email' => $this->admin->email,
            "password" => 'passwordAdmin'
        ]);

        $response->assertStatus(200);

        $admin = Player::where("email", "admin@gmail.com")->first();

        $this->assertTrue(Hash::check('passwordAdmin', $admin->password));

        $this->assertArrayHasKey('token', $response['data']);

        $this->assertNotNull($response['data']['token']);
    }

    //Lo haría con guest, pero como el login es exactamente igual que player y no influye el nickname, no considero que haga falta

    public function test_player_login_failed_password() : void
    {
        $response = $this->post('api/login', [
            'email' => $this->player->email,
            "password" => 'passwordFail'
        ]);

        $response->assertStatus(404);

        $player = Player::where("email", "player@gmail.com")->first();

        $this->assertFalse(Hash::check('passwordFail', $player->password));

        $this->assertArrayNotHasKey('token', $response['data']);

        //$this->assertNull($response['data']['token']);
    }

    
}
