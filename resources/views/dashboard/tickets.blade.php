@extends('layouts.master', ['datatables' => true, 'chartJS' => true])

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
                <div class="row">
                    @include('dashboard.partials.widget', ['text' => 'Today', 'value' => $data->get('today_tickets_count'), 'icon' => 'fas fa-calendar-day', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
                    @include('dashboard.partials.widget', ['text' => 'This week', 'value' => $data->get('this_week_tickets_count'), 'icon' => 'fas fa-calendar-week', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
                    @include('dashboard.partials.widget', ['text' => 'This month', 'value' => $data->get('month_tickets_count'), 'icon' => 'fas fa-calendar', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
                    @include('dashboard.partials.widget', ['text' => 'Last month', 'value' => $data->get('last_month_tickets_count'), 'icon' => 'far fa-calendar', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
                    @include('dashboard.partials.widget', ['text' => 'Open', 'value' => $data->get('active_tickets_count'), 'icon' => 'fas fa-bell', 'colour' => 'info', 'link' => route('reports.tickets')])
                    @include('dashboard.partials.widget', ['text' => 'Closed', 'value' => $data->get('done_tickets_count'), 'icon' => 'fas fa-check', 'colour' => 'success', 'link' => route('reports.tickets')])
                    @include('dashboard.partials.widget', ['text' => 'Overdue', 'value' => $data->get('overdue_tickets_count'), 'icon' => 'fas fa-exclamation-circle', 'colour' => 'danger', 'link' => route('reports.tickets')])
                    @include('dashboard.partials.widget', ['text' => 'Total', 'value' => $data->get('total_tickets_count'), 'icon' => 'fas fa-life-ring', 'colour' => 'primary', 'link' => route('reports.tickets')])
                </div>
                @if($data->get('unassigned_tickets')->count() > 0)
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            Unassigned Tickets
                            <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('unassigned_tickets')->count() }}</span>
                        </div>
                        <div class="card-body">
                            <!-- Content -->
                            @include('tickets.partials.table', ['id' => 'unassigned-tickets-table', 'tickets' => $data->get('unassigned_tickets'), 'display' => ['project'], 'redirect' => 'ticket'])
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
                            <!-- Content -->
                            @include('tickets.partials.table', ['id' => 'overdue-tickets-table', 'tickets' => $data->get('overdue_tickets'), 'display' => ['project'], 'redirect' => 'ticket'])
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-header">Ticket Statuses</div>
                            <div class="card-body" style="height: 400px">
                                <canvas id="ticket-statuses-chart" style="w-100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">{{ now()->format('Y') }} – Yearly Overview</div>
                            <div class="card-body" style="height: 400px">
                                <canvas id="yearly-overview-chart" class="w-100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>           
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart("ticket-statuses-chart", {
            type: "doughnut",
            data: {
                labels: ['Opened', 'Closed', 'Archived'],
                datasets: [{
                    backgroundColor: ['#17a2b8', '#28a745', '#007bff'],
                    data: [{{ $data->get('open_tickets_count') }}, {{ $data->get('close_tickets_count') }}, {{ $data->get('archive_tickets_count') }}]
                }]
            },
            options: {
                title: {
                    display: true,
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart("yearly-overview-chart", {
            type: "line",
            data: {
                labels: @json($data->get('report')->get('report_months')),
                datasets: [{ 
                    data: @json($data->get('report')->get('total_tickets_by_month')),
                    borderColor: '#007bff',
                    fill: false,
                    label: 'Total'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                    scales: {
                    y: {
                        min: 0,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            },
        });

        $(function () {
            $('#unassigned-tickets-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('#overdue-tickets-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush