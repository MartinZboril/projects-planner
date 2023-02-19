<?php

namespace App\View\Components\Ticket\UI;

use Illuminate\View\Component;
use App\Enums\TicketTypeEnum;

class Type extends Component
{
    public $title;

    public function __construct(TicketTypeEnum $type)
    {
        switch ($type) {
            case TicketTypeEnum::error:
                $this->title = __('pages.content.tickets.types.error');
                break;

            case TicketTypeEnum::inovation:
                $this->title = __('pages.content.tickets.types.inovation');
                break;                

            case TicketTypeEnum::help:
                $this->title = __('pages.content.tickets.types.help');
                break;

            case TicketTypeEnum::other:
                $this->title = __('pages.content.tickets.types.other');
                break;

            default:
                $this->title = 'NaN';
                break;
        }
    }

    public function render()
    {
        return view('components.ticket.ui.type');
    }
}
