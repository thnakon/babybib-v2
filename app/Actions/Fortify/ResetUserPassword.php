<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'confirmed', ...$this->passwordRules()],
        ], [
            'password.regex' => __('Password must contain at least 1 uppercase letter (A-Z).'),
            'password.min'   => __('Password must be at least 8 characters.'),
        ])->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
