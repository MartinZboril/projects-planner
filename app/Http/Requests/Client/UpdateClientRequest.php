<?php

namespace App\Http\Requests\Client;

use App\Models\Address;
use App\Models\Client;
use App\Models\SocialNetwork;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateClientRequest extends FormRequest
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
        $rules['name'] = ['required', 'string', 'max:255',
            Rule::unique('clients')->ignore($this->client),
        ];
        $rules['logo'] = [
            'nullable',
            File::types(['png', 'jpg'])
                ->max(5 * 1024),
        ];

        return $rules;
    }
}
