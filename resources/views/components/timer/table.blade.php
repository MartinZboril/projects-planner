<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>            
                <th>Project</th>
                <th>Type</th>
                <th>User</th>
                <th>Total time (Hours)</th>
                <th>Amount</th>
                <th>Start</th>
                <th>Stop</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>

@push('scripts')
    <script>
        $(function () {
            const type = '{{ $type }}';
            const projectId = '{{ $projectId }}';
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('timers.load') }}",
                    data: function(data) { 
                        data.type = type; 
                        data.project_id = projectId; 
                    },
                },
                columns: [
                    {data: 'note_popover', name: 'note_popover'},
                    {data: 'project', name: 'project', visible: type === 'timers' ? true : false},
                    {data: 'rate', name: 'rate'},
                    {data: 'user', name: 'user'},
                    {data: 'total_time', name: 'total_time'},
                    {data: 'amount', name: 'amount'},
                    {data: 'start', name: 'start'},
                    {data: 'stop', name: 'stop'},
                    {data: 'date', name: 'date'},
                    {data: 'buttons', name: 'buttons'},
                ]
            });
            $('#{{ $tableId }}').on('draw.dt', function() {
                $('[data-toggle="popover"]').popover();
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
@endpush