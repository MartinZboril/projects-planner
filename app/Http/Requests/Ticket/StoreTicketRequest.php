<?php

namespace App\Http\Requests\Ticket;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
        $rules = Ticket::VALIDATION_RULES;
        $rules['redirect'] = ['in:tickets,projects'];
        unset(
            $rules['reporter_id'],
            $rules['status']
        );

        return $rules;
    }
}
