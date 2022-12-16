<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum ProjectRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'projects.index';
    case Detail = 'projects.detail';
    case Milestones = 'projects.milestones';
    case MilestonesDetail = 'projects.milestones.detail';
    case Timesheets = 'projects.timesheets';
    case Tasks = 'projects.tasks';
    case TasksDetail = 'projects.task.detail';
    case Tickets = 'projects.tickets';
    case TicketsDetail = 'projects.ticket.detail';
    case Notes = 'projects.notes';
}