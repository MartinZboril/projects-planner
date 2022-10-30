<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = User::VALIDATION_RULES;
        $rules['email'] = [
            'required', 'string', 'email', 'max:255',
            Rule::unique('users')->ignoreModel($this->user)
        ];
        $rules['username'] = [
            'required', 'string', 'max:255',
            Rule::unique('users')->ignoreModel($this->user)
        ];

        return $rules;
    }
}
