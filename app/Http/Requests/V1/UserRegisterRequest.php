<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
            'name' => ['required','string'],
            'email' => ['required','email', Rule::unique(User::class)],
            'password' => ['required','string','min:6','max:50'],
        ];
    }

    protected function failedValidation(Validator $validator): object
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Hubo errores en la validación',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
