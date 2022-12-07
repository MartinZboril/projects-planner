<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
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
        $rules = Project::VALIDATION_RULES;
        $rules['status'] = [new Enum(ProjectStatusEnum::class)];
        unset(
            $rules['status']
        );

        return $rules;
    }
}
