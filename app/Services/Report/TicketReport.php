<?php

namespace App\Services\Report;

use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketReport
{
    protected $reportYear;
    protected $reportMonths;

    public function __construct()
    {
        $this->reportYear = (new DateReport)->getReportYear();
        $this->reportMonths = (new DateReport)->getReportMonths();
    }

    /**
     * Get report for tickets by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $ticketsByMonths = $this->getTicketsByMonths($year);
        $data = collect([
            'total_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->count(),
            'active_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->active()->count(),
            'done_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->done()->count(),
            'overdue_tickets_count' => Ticket::whereYear('created_at', '<=', $year)->active()->overdue()->count(),
            'report_months' => $this->reportMonths,
            'total_tickets_by_month' => $ticketsByMonths,
            'quarterly_created_tickets' => $this->getTicketsByQuarters($year, $ticketsByMonths),
        ]);

        return $data;
    }

    /**
     * Get tickets count by year
     */
    protected function getTicketsByMonths(string $year): array
    {
        $tickets = [];

        foreach ($this->reportYear as $quarter) {
            foreach ($quarter as $key => $month) {
                $tickets[$month['index']] = Ticket::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
            }
        }

        return $tickets;
    }

    /**
     * Get tickets count by quarters year
     */
    protected function getTicketsByQuarters(string $year, array $ticketsByMonths): array
    {
        $ticketsByQuarters = [];

        foreach ($this->reportYear as $quarter => $months) {
            array_push($ticketsByQuarters, [
                'title' => __('pages.content.dates.' . $quarter) . ', ' . $year,
                'values' => $this->getTicketsByQuartersMonths($ticketsByMonths, $months)
            ]);
        }

        return $ticketsByQuarters;
    }

    /**
     * Get tickets count by quarters months
     */
    protected function getTicketsByQuartersMonths(array $ticketsByMonths, array $months)
    {
        $tickets = [];
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $totalCount += $ticketsByMonths[$month['index']];
            array_push($tickets, ['title' => $month['text'], 'value' => $ticketsByMonths[$month['index']]]);
        }

        array_push($tickets, ['title' => __('pages.content.labels.total'), 'value' => $totalCount]);

        return $tickets;
    }
}