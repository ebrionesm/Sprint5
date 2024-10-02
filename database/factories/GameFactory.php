<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dieOne = rand(1, 6);
        $dieTwo = rand(1, 6);
        if($dieOne + $dieTwo === 7)
        {
            $victory = true;
        }
        else
        {
            $victory = false;
        }
        return [
            'dieOne' => $dieOne, 
            'dieTwo' => $dieTwo,   
            'victory' => $victory,                 
            'id_player' => Player::factory(),
        ];
    }
}
