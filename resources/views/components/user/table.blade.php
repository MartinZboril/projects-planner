<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Job title</th>
                <th>Mobile</th>
                <th>City</th>
                <th>Date</th>
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
                ajax: "{{ route('users.load') }}",
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'email', name: 'email'},
                    {data: 'job_title', name: 'job_title'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'city', name: 'city'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush