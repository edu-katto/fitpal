<?php

namespace App\GraphQL\Validators;

use App\Models\Calendar;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateCalendarInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required','exists:calendar,id','string','min:36',Rule::unique(Calendar::class)->ignore($this->arg('id'), 'id')],
            'name_calendar' => ['required','string','max:500', 'min:4'],
            'description_calendar' => ['string','max:500', 'min:4'],
            'user_id' => ['string','min:36']
        ];
    }
}
