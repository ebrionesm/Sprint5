<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'nickname' => $this->faker->unique()->userName, 
            'email' => $this->faker->unique()->safeEmail,   
            'password' => bcrypt('password'),                 
            'role' => 'admin',                               
        ];
    }
}
