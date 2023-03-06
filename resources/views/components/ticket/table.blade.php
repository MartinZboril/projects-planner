<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Project</th>
                <th>Reporter</th>
                <th>Assignee</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>

<script src="{{ asset('js/actions/ticket.js') }}" defer></script>

@push('scripts')
    <script>
        $(function () {
            const type = '{{ $type }}';
            const projectId = '{{ $projectId }}';
            const overdue = '{{ $overdue }}';
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tickets.load') }}",
                    data: function(data) { 
                        data.type = type; 
                        data.project_id = projectId; 
                        data.overdue = overdue; 
                    },
                },
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'project', name: 'project', visible: type === 'tickets' ? true : false},
                    {data: 'reporter', name: 'reporter'},
                    {data: 'assignee', name: 'assignee'},
                    {data: 'date', name: 'date'},
                    {data: 'status_badge', name: 'status_badge'},
                    {data: 'type', name: 'type'},
                    {data: 'priority', name: 'priority'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush