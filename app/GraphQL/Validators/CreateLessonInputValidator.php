<?php

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class CreateLessonInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name_lesson' => ['required','string','max:500', 'min:4'],
            'calendar_id' => ['required','exists:calendar,id','string','min:36'],
            'description_lesson' => ['string','max:500', 'min:4'],
            'duration_lesson' => ['required','date_format:H:i:s']
        ];
    }
}
