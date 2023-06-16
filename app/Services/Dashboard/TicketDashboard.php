<?php

namespace App\Services\Dashboard;

use App\Enums\TicketStatusEnum;
use App\Interfaces\DashboardInterface;
use App\Models\Ticket;
use App\Services\Report\TicketReport;
use Illuminate\Support\Collection;

class TicketDashboard implements DashboardInterface
{
    /**
     * Get dashboard for tickets.
     */
    public function getDashboard(): Collection
    {
        $year = now()->format('Y');
        $data = collect([
            'today_tickets_count' => Ticket::whereDate('created_at', now()->format('Y-m-d'))->count(),
            'this_week_tickets_count' => Ticket::whereBetween('created_at', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d'),
            ])->count(),
            'month_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->whereMonth('created_at', now()->month)->count(),
            'last_month_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->whereMonth('created_at', now()->subMonth()->month)->count(),
            'total_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->count(),
            'active_tickets_count' => Ticket::active()->count(),
            'done_tickets_count' => Ticket::done()->count(),
            'overdue_tickets_count' => Ticket::active()->overdue()->count(),
            'overdue_tickets' => Ticket::active()->overdue()->get(),
            'unassigned_tickets' => Ticket::active()->unassigned()->get(),
            'open_tickets_count' => Ticket::status(TicketStatusEnum::open)->count(),
            'close_tickets_count' => Ticket::status(TicketStatusEnum::close)->count(),
            'archive_tickets_count' => Ticket::status(TicketStatusEnum::archive)->count(),
            'report' => (new TicketReport)->getReportPerYear($year),
        ]);

        return $data;
    }
}
