<?php

namespace App\Http\Requests\ToDo;

use App\Models\ToDo;
use Illuminate\Foundation\Http\FormRequest;

class StoreToDoRequest extends FormRequest
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
        $rules = ToDo::VALIDATION_RULES;
        unset($rules['task_id']);
        unset($rules['is_finished']);

        return $rules;
    }
}
