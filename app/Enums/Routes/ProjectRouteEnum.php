<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum ProjectRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'projects.index';
    case Detail = 'projects.show';
    case Milestones = 'projects.milestones';
    case MilestonesDetail = 'projects.milestones.show';
    case Timesheets = 'projects.timers.index';
    case Tasks = 'projects.tasks.index';
    case TasksDetail = 'projects.tasks.show';
    case Tickets = 'projects.tickets.index';
    case TicketsDetail = 'projects.tickets.detail';
    case Notes = 'projects.notes.index';
}