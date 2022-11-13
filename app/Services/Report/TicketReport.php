<?php

namespace App\Services\Report;

use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketReport
{
    /**
     * Get report for tickets by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $data = collect([
            'total_tickets_count' => Ticket::whereYear('created_at', $year)->count(),
            'active_tickets_count' => Ticket::whereYear('created_at', $year)->active()->count(),
            'done_tickets_count' => Ticket::whereYear('created_at', $year)->done()->count(),
            'overdue_tickets_count' => Ticket::whereYear('created_at', $year)->active()->overdue()->count(),
            'report_months' => sprintf("'%s'", implode("','", $this->getReportMonths())),
            'total_tickets_by_month' => $this->getTicketsByMonths($year),
            'quarterly_created_tickets' => [
                1 => (
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 1)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 2)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 3)->count()
                ),
                2 => (
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 4)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 5)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 6)->count()
                ),
                3 => (
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 7)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 8)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 9)->count()
                ),
                4 => (
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 10)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 11)->count() +
                    Ticket::whereYear('created_at', $year)->whereMonth('created_at', 12)->count()
                ),
            ]
        ]);

        return $data;
    }

    /**
     * Get tickets count by year
     */
    protected function getTicketsByMonths(string $year): array
    {
        $tickets = [];
        $months = $this->getReportMonths();

        foreach ($months as $key => $month) {
            $tickets[$month] = Ticket::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
        }

        return $tickets;
    }

    protected function getReportMonths(): array
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
    }
}