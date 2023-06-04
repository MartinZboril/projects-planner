<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use App\Models\Address;
use App\Models\SocialNetwork;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
        $rules = Client::VALIDATION_RULES + Address::VALIDATION_RULES + SocialNetwork::VALIDATION_RULES;
        $rules['logo'] = [
            'nullable',
            File::types(['png', 'jpg'])
                ->max(5 * 1024),
        ];

        return $rules;
    }
}
