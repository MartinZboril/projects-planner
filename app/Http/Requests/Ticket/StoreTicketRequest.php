<?php

namespace App\Http\Requests\Ticket;

use App\Enums\TicketStatusEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketTypeEnum;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
        $rules['status'] = [new Enum(TicketStatusEnum::class)];
        $rules['priority'] = [new Enum(TicketPriorityEnum::class)];
        $rules['type'] = [new Enum(TicketTypeEnum::class)];
        unset(
            $rules['reporter_id'],
            $rules['status']
        );

        return $rules;
    }
}
