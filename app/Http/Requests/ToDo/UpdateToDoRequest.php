<?php

namespace App\Http\Requests\ToDo;

use App\Models\ToDo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateToDoRequest extends FormRequest
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
        $rules = ToDo::VALIDATION_RULES;
        $rules['redirect'] = ['in:tasks,projects'];
        unset($rules['task_id']);
        
        return $rules;
    }
}
