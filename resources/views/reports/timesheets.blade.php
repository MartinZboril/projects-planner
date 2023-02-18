@extends('layouts.master', ['chartJS' => true])

@section('title', __('pages.title.report'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('analysis.timesheets') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-chart-bar mr-1"></i>Analyze</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">Report for Timesheets</div>
                    <div class="card-body">
                        <!-- Content -->
                        <div class="row">
                            <div class="col-md-4">
                                <x-report.ui.card title="Total time" :value="$data->get('total_timers_count') . ' hours'" colour="text-body" />
                            </div>
                            <div class="col-md-8">
                                <x-chart.overview :report-months="$data->get('report_months')" :total-count="$data->get('total_timers_by_month')" :year="$data->get('year')" chart-id="yearly-overview-chart" />
                            </div>
                        </div>
                        <hr>
                        <!-- Quarterly reports  -->
                        <x-report.ui.list :records="$data['quarterly_recorded_timers']" />
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection