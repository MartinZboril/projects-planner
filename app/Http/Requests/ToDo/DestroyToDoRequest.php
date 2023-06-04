<?php

namespace App\Http\Requests\ToDo;

use App\Models\ToDo;
use Illuminate\Foundation\Http\FormRequest;

class DestroyToDoRequest extends FormRequest
{
    /**
     * @return bool
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
            'redirect' => 'nullable|boolean',
        ];
    }
}
