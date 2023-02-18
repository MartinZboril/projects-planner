<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">Budget</div>
            <div class="card-body">
                <div class="text-center">
                    <h6><x-site.amount :value="$project->amount" /></h6>
                    <span class="text-muted">Budget: <x-site.amount :value="$project->budget" /></span>
                </div>
                <div id="budget-progress-bar" class="mt-2"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">Plan</div>
            <div class="card-body">
                <div class="text-center">
                    <h6>{{ $project->total_time }} Hours</h6>
                    <span class="text-muted">Est. Hours: {{ $project->estimated_hours }} Hours</span>
                </div>
                <div id="plan-progress-bar" class="mt-2"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">Tasks</div>
            <div class="card-body">
                <div class="text-center">
                    <h6>{{ $project->pending_tasks_count }} Pending</h6>
                    <span class="text-muted">{{ $project->done_tasks_count }} Done</span>
                </div>
                <div id="tasks-progress-bar" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        createProgressBar('#budget-progress-bar', {{ $project->budget_plan >= 100 ? '1.0' : ($project->budget_plan / 100) }}, '{{ $project->budget_plan }} %', '#{{ $project->budget_plan > 100 ? "dc3545" : "28a745" }}');
        createProgressBar('#plan-progress-bar', {{ $project->time_plan >= 100 ? '1.0' : ($project->time_plan / 100) }}, '{{ $project->time_plan }} %', '#{{ $project->time_plan > 100 ? "dc3545" : "28a745" }}');
        createProgressBar('#tasks-progress-bar', {{ $project->tasks_plan >= 100 ? '1.0' : ($project->tasks_plan / 100) }}, '{{ $project->tasks_plan }} %', '#{{ $project->tasks_plan >= 100 ? "28a745" : "dc3545" }}');

        function createProgressBar(ident, value, text, color) {
            var budgetProgressBar = new ProgressBar.Circle(ident, {
                strokeWidth: 15,
                color: color,
                trailColor: '#eee',
                trailWidth: 15,
                text: {
                    value: text,
                    style: {
                        color: color,
                        position: 'absolute',
                        left: '50%',
                        top: '50%',
                        padding: 0,
                        margin: 0,
                        fontSize: '1.5rem',
                        fontWeight: 'bold',
                        transform: {
                            prefix: true,
                            value: 'translate(-50%, -50%)',
                        },
                    },
                }
            });

            budgetProgressBar.animate(value);            
        }
    </script>
@endpush