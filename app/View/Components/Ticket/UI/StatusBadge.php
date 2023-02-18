<?php

namespace App\View\Components\Ticket\UI;

use Illuminate\View\Component;
use App\Enums\TicketStatusEnum;

class StatusBadge extends Component
{
    public $title;
    public $background;
    public $text;

    public function __construct($status, $text)
    {
        $this->text = $text;
        switch($status) {
            case(TicketStatusEnum::open):
                $this->title = __('pages.content.tickets.statuses.open');
                $this->background = 'info';
                break;
                
            case(TicketStatusEnum::close):
                $this->title = __('pages.content.tickets.statuses.close');
                $this->background = 'success';
                break;
    
            case(TicketStatusEnum::archive):
                $this->title = __('pages.content.tickets.statuses.archive');
                $this->background = 'primary';
                break;
    
            default:
                $this->title = 'NaN';
                $this->background = 'info';
        }
    }

    public function render()
    {
        return view('components.ticket.ui.status-badge');
    }
}
