<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Laravel\Passport\Passport;
use App\Models\Player;
use Tests\TestCase;

class RegisterManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecuta las migraciones
        $this->artisan('migrate');

        // Crea el rol y los permisos
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit-player', 'guard_name' => 'web']);
        
        // Crea un usuario
        $player = Player::create([
            'nickname' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Asegúrate de que la contraseña esté cifrada
            'role' => 'admin'
        ]);

        // Asigna el rol al usuario
        $player->assignRole('admin');

        // Inicia sesión con el token de acceso personal
        Passport::actingAs($player, 'web');
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Crea un usuario
        $user = Player::create([
            'nickname' => 'admin2',
            'email' => 'admin2@example.com',
            'password' => bcrypt('12345678'), // Asegúrate de que la contraseña esté cifrada
            'role' => 'admin'
        ]);

        // Asigna el rol al usuario
        $user->assignRole('admin');

        // Simula el inicio de sesión
        $this->actingAs($user);

        // Realiza la solicitud POST
        $data = [
            'nickname' => 'The Player',
            'email' => 'player@gmail.com',
            'password' => '1234',
            'role' => 'admin',
        ];

        $response = $this->post('/players', $data);

        $response->assertStatus(200);
        }
}
