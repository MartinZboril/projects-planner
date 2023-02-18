<div class="card card-primary card-outline">
    <div class="card-header">{{ $title }}</div>
    <div class="card-body" style="height: {{ $height ?? '400' }}px">
        <canvas id="{{ $chartId }}" style="w-100"></canvas>
    </div>
</div>

@push('scripts')
    <script>
        new Chart("{{ $chartId }}", {
            type: "doughnut",
            data: {
                labels: @json($labels),
                datasets: [{
                    backgroundColor: @json($colours),
                    data: @json($data)
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
    </script>
@endpush