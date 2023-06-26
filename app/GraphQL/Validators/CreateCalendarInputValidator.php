<?php

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class CreateCalendarInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name_calendar' => ['required','string','max:500', 'min:4'],
            'description_calendar' => ['string','max:500', 'min:4'],
            'user_id' => ['required','string','min:36']
        ];
    }
}
