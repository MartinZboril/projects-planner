<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
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
        $rules = Task::VALIDATION_RULES;
        $rules['status'] = [new Enum(TaskStatusEnum::class)];
        $rules['redirect'] = ['in:tasks,projects'];
        unset(
            $rules['author_id'],
            $rules['status']
        );

        return $rules;
    }
}
