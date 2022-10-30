<?php

namespace App\Http\Requests\Timer;

use Illuminate\Foundation\Http\FormRequest;

class StartTimerRequest extends FormRequest
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
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'rate_id' => ['required', 'integer', 'exists:rates,id'],
        ];
    }
}
