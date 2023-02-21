<?php

namespace App\View\Components\Ticket\UI;

use Illuminate\View\Component;
use App\Enums\TicketPriorityEnum;

class Priority extends Component
{
    public $title;

    public function __construct(TicketPriorityEnum $priority)
    {
        switch ($priority) {
            case TicketPriorityEnum::low:
                $this->title = __('pages.content.tickets.priorities.low');
                break;

            case TicketPriorityEnum::medium:
                $this->title = __('pages.content.tickets.priorities.medium');
                break;                

            case TicketPriorityEnum::high:
                $this->title = __('pages.content.tickets.priorities.high');
                break;

            case TicketPriorityEnum::urgent:
                $this->title = __('pages.content.tickets.priorities.urgent');
                break;

            default:
                $this->title = 'NaN';
                break;
        }
    }

    public function render()
    {
        return view('components.ticket.ui.priority');
    }
}
