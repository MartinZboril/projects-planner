@extends('layouts.master')

@section('title', __('pages.title.report'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('analysis.projects') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-chart-bar mr-1"></i>Analyze</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header">Report for projects</div>
                <div class="card-body">
                    <!-- Content -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <b>Total</b> <a class="float-right text-body">{{ $data->get('total_projects_count') }}</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <b>Active</b> <a class="float-right text-danger">{{ $data->get('active_projects_count') }}</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <b>Done</b> <a class="float-right text-success">{{ $data->get('done_projects_count') }}</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <b>Avg. Spent Time</b> <a class="float-right text-body">{{ round($data->get('spent_time_avg')) }} Hours</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <b>Avg. Amount</b> <a class="float-right text-body">{{ number_format(round($data->get('amount_avg')), 2) }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-primary">2022 – Yearly Overview</div>
                                <div class="card-body">
                                    <canvas id="yearly-overview-chart" class="w-100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Quarterly reports  -->
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">1st Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>January</b> <span class="float-right">{{ $data['total_projects_by_month']['Jan'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>February</b> <span class="float-right">{{ $data['total_projects_by_month']['Feb'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>March</b> <span class="float-right">{{ $data['total_projects_by_month']['Mar'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_projects'][1] }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">2nd Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>April</b> <span class="float-right">{{ $data['total_projects_by_month']['Apr'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>May</b> <span class="float-right">{{ $data['total_projects_by_month']['May'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>June</b> <span class="float-right">{{ $data['total_projects_by_month']['Jun'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_projects'][2] }}</span>
                                </li>                            
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">3rd Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>July</b> <span class="float-right">{{ $data['total_projects_by_month']['Jul'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>August</b> <span class="float-right">{{ $data['total_projects_by_month']['Aug'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>September</b> <span class="float-right">{{ $data['total_projects_by_month']['Sep'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_projects'][3] }}</span>
                                </li>        
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">4th Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>October</b> <span class="float-right">{{ $data['total_projects_by_month']['Oct'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>November</b> <span class="float-right">{{ $data['total_projects_by_month']['Nov'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>December</b> <span class="float-right">{{ $data['total_projects_by_month']['Dec'] }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_projects'][4] }}</span>
                                </li>                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- Yearly Overview -->
    <script>
        console.log();
        new Chart("yearly-overview-chart", {
            type: "line",
            data: {
                labels: [{!! $data->get('report_months') !!}],
                datasets: [{ 
                    data: @json($data->get('total_projects_by_month')),
                    borderColor: '#007bff',
                    fill: false,
                    label: 'Total'
                }]
            },
            options: {
                responsive: true,
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
    </script>
@endpush