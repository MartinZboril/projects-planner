@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'chartJS' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'tickets'])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <x-dashboard.widget text="Today" :value="$data->get('today_tickets_count')" icon="fas fa-calendar-day" colour="lightblue color-palette" :link="route('tickets.index')" />
                    <x-dashboard.widget text="This week" :value="$data->get('this_week_tickets_count')" icon="fas fa-calendar-week" colour="lightblue color-palette" :link="route('tickets.index')" />
                    <x-dashboard.widget text="This month" :value="$data->get('month_tickets_count')" icon="fas fa-calendar" colour="lightblue color-palette" :link="route('tickets.index')" />
                    <x-dashboard.widget text="Last month" :value="$data->get('last_month_tickets_count')" icon="far fa-calendar" colour="lightblue color-palette" :link="route('tickets.index')" />
                    <x-dashboard.widget text="Open" :value="$data->get('active_tickets_count')" icon="fas fa-bell" colour="info" :link="route('reports.tickets')" />
                    <x-dashboard.widget text="Closed" :value="$data->get('done_tickets_count')" icon="fas fa-check" colour="success" :link="route('reports.tickets')" />
                    <x-dashboard.widget text="Overdue" :value="$data->get('overdue_tickets_count')" icon="fas fa-exclamation-circle" colour="danger" :link="route('reports.tickets')" />
                    <x-dashboard.widget text="Total" :value="$data->get('total_tickets_count')" icon="fas fa-life-ring" colour="primary" :link="route('reports.tickets')" />
                </div>
                @if($data->get('unassigned_tickets')->count() > 0)
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            Unassigned Tickets
                            <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('unassigned_tickets')->count() }}</span>
                        </div>
                        <div class="card-body">
                            <x-ticket.table :tickets="$data->get('unassigned_tickets')" table-id="unassigned-tickets-table" />
                        </div>
                    </div> 
                @endif
                @if($data->get('overdue_tickets')->count() > 0)     
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            Overdue Tickets
                            <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_tickets')->count() }}</span>
                        </div>
                        <div class="card-body">
                            <x-ticket.table :tickets="$data->get('overdue_tickets')" table-id="overdue-tickets-table" />
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <x-chart.doughnut title="Ticket Statuses" chart-id="ticket-statuses-chart" :labels="['Opened', 'Closed', 'Archived']" :colours="['#17a2b8', '#28a745', '#007bff']" :data="[$data->get('open_tickets_count'), $data->get('close_tickets_count'), $data->get('archive_tickets_count')]" />
                    </div>
                    <div class="col-md-8">
                        <x-chart.overview :report-months="$data->get('report')->get('report_months')" :total-count="$data->get('report')->get('total_tickets_by_month')" :year="now()->format('Y')" chart-id="yearly-overview-chart" />
                    </div>
                </div>           
            </div>
        </section>
    </div>
@endsection