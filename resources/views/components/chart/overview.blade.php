<div class="card">
    <div class="card-header bg-primary">{{ $year }} – Yearly Overview</div>
    <div class="card-body">
        <canvas id="{{ $tableId }}" class="w-100"></canvas>
    </div>
</div>

@push('scripts')
    <script>
        new Chart("{{ $tableId }}", {
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