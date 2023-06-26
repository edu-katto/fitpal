<?php

namespace App\GraphQL\Validators;

use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateLessonClassInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required','exists:lesson,id',Rule::unique(Lesson::class)->ignore($this->arg('id'), 'id')],
            'name_lesson' => ['required','string','max:500', 'min:4'],
            'description_lesson' => ['string','max:500', 'min:4'],
            'calendar_id' => ['required','string','min:36'],
            'duration_lesson' => ['required','date_format:H:i:s']
        ];
    }
}
