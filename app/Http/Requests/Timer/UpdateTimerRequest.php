<?php

namespace App\Http\Requests\Timer;

use App\Models\Timer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimerRequest extends FormRequest
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
        $rules = Timer::VALIDATION_RULES;
        unset(
            $rules['user_id'],
            $rules['project_id']
        );

        return $rules;
    }
}
