<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Project</th>                        
                <th>Owner</th>
                <th>Progress</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>  

@push('scripts')
    <script>
        $(function () {
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('milestones.load') }}",
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'project', name: 'project'},
                    {data: 'owner', name: 'owner'},
                    {data: 'progress', name: 'progress'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush