<?php

namespace App\Services\Report;

use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketReport
{
    public function __construct(
        public BuilderReport $builderReport=new BuilderReport
    ) {}

    /**
     * Get report for tickets by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $ticketsByMonths = $this->getTicketsByMonths($year);
        $data = collect([
            'year' => $year,
            'total_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->count(),
            'active_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->active()->count(),
            'done_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->done()->count(),
            'overdue_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->active()->overdue()->count(),
            'report_months' => $this->builderReport->reportMonthsIndexes,
            'total_tickets_by_month' => $ticketsByMonths,
            'quarterly_created_tickets' => $this->builderReport->getItemsByQuarters($year, $ticketsByMonths),
        ]);

        return $data;
    }

    /**
     * Get tickets count by year
     */
    protected function getTicketsByMonths(string $year): Collection
    {
        $tickets = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use($tickets, $year) {
            $tickets->put($month['index'], Ticket::whereYear('created_at', $year)->whereMonth('created_at', $key + 1)->count());
        });

        return $tickets;
    }
}