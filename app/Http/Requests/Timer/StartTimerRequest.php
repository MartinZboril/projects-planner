<?php

namespace App\Http\Requests\Timer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartTimerRequest extends FormRequest
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
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'rate_id' => [
                'required', 'integer', 'exists:rates,id',
                Rule::unique('timers')->where(function ($query) {
                    return $query->where('project_id', $this->project_id)->where('rate_id', $this->rate_id)->whereNull('until_at');
                }),
            ],
        ];
    }
}
