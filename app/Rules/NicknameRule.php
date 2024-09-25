<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Player;

class NicknameRule implements ValidationRule
{

    protected $nickname;

    // Constructor para pasar la excepción (user_id)
    public function __construct($nickname)
    {
        $this->nickname = $nickname;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Buscar si hay un email duplicado en la base de datos, excepto para el ID del usuario actual
        return !Player::where('nickname', $value)
                    ->where('nickname', '!=', 'Anónimo')  // Excluir el usuario actual
                    ->exists();
    }

    public function message()
    {
        return 'Nickname already in use.';
    }
}
