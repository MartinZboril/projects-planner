<?php

namespace App\View\Components\Ticket\Ui;

use App\Enums\TicketStatusEnum;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public $title;

    public $background;

    public $text;

    public function __construct(TicketStatusEnum $status, string $text)
    {
        $this->text = $text;
        switch ($status) {
            case TicketStatusEnum::open:
                $this->title = __('pages.content.tickets.statuses.open');
                $this->background = 'info';
                break;

            case TicketStatusEnum::close:
                $this->title = __('pages.content.tickets.statuses.close');
                $this->background = 'success';
                break;

            case TicketStatusEnum::archive:
                $this->title = __('pages.content.tickets.statuses.archive');
                $this->background = 'primary';
                break;

            case TicketStatusEnum::convert:
                $this->title = __('pages.content.tickets.statuses.convert');
                $this->background = 'info';
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
