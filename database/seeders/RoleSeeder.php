<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $player = Role::create(['name' => 'Player']);

        $admin->givePermissionTo([
            /*'create-player',
            'edit-player',
            'delete-player',*/
            'delete-games'
        ]);

        $player->givePermissionTo([
            'edit-player',
            'view-game',
            'create-game',
            'view-ranking',
            'view-worst',
            'view-best'
        ]);
    }
}
