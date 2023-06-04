@if ($ticket->status != App\Enums\TicketStatusEnum::convert)
    <a href="#" style="{{ $ticket->assignee_id && $ticket->status != App\Enums\TicketStatusEnum::close && $ticket->status != App\Enums\TicketStatusEnum::archive ? '' : 'display: none;' }}" id="ticket-{{ $ticket->id }}-convert-to-task" class="btn btn-{{ $buttonSize }} btn-primary" onclick="convertTicket('{{ route('tickets.convert_to_task', $ticket) }}')">
        <x-site.ui.icon icon="fas fa-tasks" :text="$hideButtonText ?? 'Convert to task'" />
    </a>

    <a href="#" style="{{ $ticket->status === App\Enums\TicketStatusEnum::open ? '' : 'display: none;' }}" id="ticket-{{ $ticket->id }}-close-status" class="btn btn-{{ $buttonSize }} btn-success" onclick="changeTicketStatus('{{ route('tickets.change_status', $ticket) }}', {{ App\Enums\TicketStatusEnum::close }}, '{{ $type }}', '{{ __('pages.content.tickets.statuses.close') }}', 'success', '{{ $tableIdentifier }}')">
        <x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Close'" />
    </a>

    <a href="#" style="{{ $ticket->status === App\Enums\TicketStatusEnum::close || $ticket->status === App\Enums\TicketStatusEnum::archive ? '' : 'display: none;' }}" id="ticket-{{ $ticket->id }}-open-status" class="btn btn-{{ $buttonSize }} btn-info" onclick="changeTicketStatus('{{ route('tickets.change_status', $ticket) }}', {{ App\Enums\TicketStatusEnum::open }}, '{{ $type }}', '{{ __('pages.content.tickets.statuses.open') }}', 'info', '{{ $tableIdentifier }}')">
        <x-site.ui.icon icon="fas fa-bell" :text="$hideButtonText ?? 'Open'" />
    </a>

    <a href="#" style="{{ $ticket->status != App\Enums\TicketStatusEnum::close && $ticket->status != App\Enums\TicketStatusEnum::archive ? '' : 'display: none;' }}" id="ticket-{{ $ticket->id }}-archive-status" class="btn btn-{{ $buttonSize }} btn-primary" onclick="changeTicketStatus('{{ route('tickets.change_status', $ticket) }}', {{ App\Enums\TicketStatusEnum::archive }}, '{{ $type }}', '{{ __('pages.content.tickets.statuses.archive') }}', 'primary', '{{ $tableIdentifier }}')">
        <x-site.ui.icon icon="fas fa-archive" text="" />
    </a>
@endif

<a href="#" class="btn btn-{{ $buttonSize }} btn-primary ticket-mark-button" onclick="markTicket('{{ route('tickets.mark', $ticket) }}', '{{ $type }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($ticket->is_marked ? 'fas' : 'far') }} fa-bookmark" id="ticket-{{ $ticket->id }}-marked"></i>
</a>