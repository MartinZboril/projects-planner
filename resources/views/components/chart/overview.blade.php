<div class="card card-primary card-outline">
    <div class="card-header bg-primary">{{ $year }} – Yearly Overview</div>
    <div class="card-body" style="height: {{ $height ?? '400' }}px">
        <canvas id="{{ $chartId }}" class="w-100"></canvas>
    </div>
</div>

@push('scripts')
    <script>
        new Chart("{{ $chartId }}", {
            type: "line",
            data: {
                labels: @json($reportMonths),
                datasets: [{ 
                    data: @json($totalCount),
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
    </script>
@endpush