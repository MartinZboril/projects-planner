<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Project</th>
                <th>Milestone</th>
                <th>User</th>
                <th>Due Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>

<script src="{{ asset('js/actions/task.js') }}" defer></script>

@push('scripts')
    <script>
        $(function () {
            const type = '{{ $type }}';
            const projectId = '{{ $projectId }}';
            const milestoneId = '{{ $milestoneId }}';
            const overdue = '{{ $overdue }}';
            const status = '{{ $status }}';
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tasks.load') }}",
                    data: function(data) { 
                        data.type = type; 
                        data.project_id = projectId; 
                        data.milestone_id = milestoneId; 
                        data.overdue = overdue; 
                        data.status = status; 
                    },
                },
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'project', name: 'project', visible: type === 'tasks' ? true : false},
                    {data: 'milestone', name: 'milestone'},
                    {data: 'user', name: 'user'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'status_badge', name: 'status_badge', visible: !status ? true : false},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush