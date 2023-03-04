<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>

<script src="{{ asset('js/actions/client.js') }}" defer></script>

@push('scripts')
    <script>
        $(function () {
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('clients.load') }}",
                columns: [
                    {data: 'detail', name: 'detail'},
                    {data: 'contact_person', name: 'contact_person'},
                    {data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush