<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
        $rules = Comment::VALIDATION_RULES;
        $rules['parent_id'] = ['required', 'integer'];
        $rules['type'] = ['in:client,project,milestone'];
        unset($rules['user_id']);

        return $rules;
    }
}
