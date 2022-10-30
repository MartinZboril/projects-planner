<?php

namespace App\Http\Requests\Rate;

use App\Models\Rate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRateRequest extends FormRequest
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
        $rules = Rate::VALIDATION_RULES;
        unset($rules['user_id']);

        return $rules;    }
}
