<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
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
            'files' => 'required',
            'parent_id' => 'required', 'integer',
            'type' => 'in:client,ticket'
        ];
    }
}
