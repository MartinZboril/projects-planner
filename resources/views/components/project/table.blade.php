<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Client</th>
                <th>Status</th>
                <th>Team</th>
                <th>Due date</th>
                <th>Plan</th>
                <th>Total Time</th>
                <th>Budget</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script src="{{ asset('js/actions/project.js') }}" defer></script>

@push('scripts')
    <script>
        $(function () {
            const overdue = '{{ $overdue }}';
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('projects.load') }}",
                    data: function(data) { 
                        data.overdue = overdue; 
                    },
                },               
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'client', name: 'client'},
                    {data: 'status_badge', name: 'status_badge'},
                    {data: 'team', name: 'team'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'time_plan', name: 'time_plan'},
                    {data: 'total_time', name: 'total_time'},
                    {data: 'budget_plan', name: 'budget_plan'},
                    {data: 'amount', name: 'amount'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush