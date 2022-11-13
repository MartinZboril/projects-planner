@extends('layouts.master')

@section('title', __('pages.title.report'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('analysis.timesheets') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-chart-bar mr-1"></i>Analyze</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header">Report for Timesheets</div>
                <div class="card-body">
                    <!-- Content -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <b>Total time</b> <a class="float-right text-body">{{ $data->get('total_timesheets_count') }} hours</a>
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
                                    <b>January</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Jan'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>February</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Feb'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>March</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Mar'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_timesheets'][1] }} hours</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">2nd Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>April</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Apr'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>May</b> <span class="float-right">{{ $data['total_timesheets_by_month']['May'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>June</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Jun'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_timesheets'][2] }} hours</span>
                                </li>                            
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">3rd Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>July</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Jul'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>August</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Aug'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>September</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Sep'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_timesheets'][3] }} hours</span>
                                </li>        
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="lead">4th Quarter, 2022</span>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>October</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Oct'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>November</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Nov'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>December</b> <span class="float-right">{{ $data['total_timesheets_by_month']['Dec'] }} hours</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="float-right">{{ $data['quarterly_created_timesheets'][4] }} hours</span>
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
                    data: @json($data->get('total_timesheets_by_month')),
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