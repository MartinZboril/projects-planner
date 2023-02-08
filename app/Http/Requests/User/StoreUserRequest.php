<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class handleStoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = User::VALIDATION_RULES;
        $rules['rate_name'] = ['required', 'max:255'];
        $rules['rate_value'] = ['required', 'integer', 'min:0'];
        
        return $rules;
    }
}
