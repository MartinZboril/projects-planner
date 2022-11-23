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
                            @include('reports.partials.card', ['title' => 'Total time', 'value' => $data->get('total_timers_count') . ' hours', 'colour' => 'text-body'])
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
                        @foreach ($data['quarterly_recorded_timers'] as $value)
                            @include('reports.partials.list', ['title' => $value['title'], 'values' => $value['values']])
                        @endforeach
                    </div>
                </div>
            </div>            
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        new Chart("yearly-overview-chart", {
            type: "line",
            data: {
                labels: @json($data->get('report_months')),
                datasets: [{ 
                    data: @json($data->get('total_timers_by_month')),
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