<?php

namespace App\Http\Requests\Note;

use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
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
        $rules = Note::VALIDATION_RULES;
        unset(
            $rules['user_id'],
            $rules['is_basic']
        );

        return $rules;
    }
}
